<?php

namespace App\Http\Controllers;

use App\Models\RepairOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OmdOrderController extends Controller
{
    public function index()
    {
        $orders = RepairOrder::with([
            'user',
            'line',
            'area',
            'product',
            'masterModel',
            'ngType',
            'result',
            'confirmation',
        ])
            ->whereIn('status', [
                'submitted',
                'verified',
                'in_repair',
                'completed',
            ])
            ->latest('created_at')
            ->paginate(10);

        $completedCount = RepairOrder::where('status', 'completed')->count();

        return view('omd.orders.index', compact(
            'orders',
            'completedCount'
        ));
    }

    public function show(RepairOrder $order)
    {
        $order->load([
            'user',
            'line',
            'area',
            'product',
            'masterModel',
            'ngType',
            'result',
            'confirmation',
            'omdVerifier',
            'handedOverBy',
            'items.masterModel',
            'items.product',
            'items.ngType',
        ]);

        return view(
            'omd.orders.show',
            compact('order')
        );
    }


    public function verify(
        Request $request,
        RepairOrder $order
    ) {
        abort_unless(
            $order->status === 'submitted',
            422,
            'Order tidak berada pada status Submitted.'
        );

        $order->update([
            'status' => 'verified',
            'verified_by' => $request->user()->id,
            'verified_at' => now(),
        ]);

        return back()
            ->with(
                'success',
                'Order berhasil diverifikasi.'
            );
    }


    public function startRepair(
        RepairOrder $order
    ) {
        abort_unless(
            $order->status === 'verified',
            422,
            'Order belum diverifikasi.'
        );

        $order->update([
            'status' => 'in_repair',
            'repair_started_at' => now(),
        ]);

        return back()
            ->with(
                'success',
                'Order masuk proses repair.'
            );
    }


    public function complete(
        Request $request,
        RepairOrder $order
    ) {
        abort_unless(
            $order->status === 'in_repair',
            422,
            'Order belum berada pada proses repair.'
        );

        $order->load('items');


        /*
        |--------------------------------------------------------------------------
        | FORMAT BARU
        | 1 Token -> banyak Model/Product/NG
        |--------------------------------------------------------------------------
        */

        if ($order->items->isNotEmpty()) {

            $rules = [];

            foreach ($order->items as $item) {

                $rules['items.' . $item->id . '.after_qty'] = [
                    'required',
                    'integer',
                    'min:0',
                ];

                $rules['items.' . $item->id . '.mismatch_note'] = [
                    'nullable',
                    'string',
                    'max:1000',
                ];
            }


            $data = $request->validate($rules);


            DB::transaction(function () use (
                $order,
                $data
            ) {

                foreach ($order->items as $item) {

                    $itemData =
                        $data['items'][$item->id]
                        ?? [];

                    $item->update([
                        'after_qty' =>
                        $itemData['after_qty'] ?? 0,

                        'mismatch_note' =>
                        $itemData['mismatch_note']
                            ?? null,
                    ]);
                }

                $order->update([
                    'status' => 'completed',
                    'repair_completed_at' => now(),
                ]);
            });


            return redirect()
                ->route('omd.orders.index')
                ->with(
                    'success',
                    'Order Repair Box Selesai dan menunggu verifikasi dari User.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | FORMAT LAMA
        | Tetap dipertahankan untuk order existing
        |--------------------------------------------------------------------------
        */

        $data = $request->validate([
            'ok_qty' => [
                'required',
                'integer',
                'min:0',
            ],

            'scrap_qty' => [
                'required',
                'integer',
                'min:0',
            ],

            'ng_qty' => [
                'required',
                'integer',
                'min:0',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);


        $sum =
            $data['ok_qty'] +
            $data['scrap_qty'] +
            $data['ng_qty'];


        if ($sum > $order->quantity) {

            return back()
                ->withErrors([
                    'ok_qty' =>
                    'Total hasil repair tidak boleh melebihi quantity order.',
                ])
                ->withInput();
        }


        DB::transaction(function () use (
            $request,
            $order,
            $data
        ) {

            $order->update([
                'status' => 'completed',
                'repair_completed_at' => now(),
            ]);


            $order->result()->updateOrCreate(
                [],
                [
                    'ok_qty' =>
                    $data['ok_qty'],

                    'scrap_qty' =>
                    $data['scrap_qty'],

                    'ng_qty' =>
                    $data['ng_qty'],

                    'notes' =>
                    $data['notes'] ?? null,

                    'processed_by' =>
                    $request->user()->id,
                ]
            );
        });


        return back()
            ->with(
                'success',
                'Hasil repair berhasil disimpan.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SERAH TERIMA OMD KE USER
    |--------------------------------------------------------------------------
    */

    public function handover(
        Request $request,
        RepairOrder $order
    ) {
        abort_unless(
            $order->status === 'completed',
            422,
            'Order belum selesai repair.'
        );


        abort_unless(
            !$order->handed_over_at,
            422,
            'Order sudah pernah diserahterimakan.'
        );


        $order->update([
            'handed_over_by' =>
            $request->user()->id,

            'handed_over_at' =>
            now(),
        ]);


        return back()
            ->with(
                'success',
                'Barang repair berhasil diserahterimakan kepada user.'
            );
    }

    public function pendingCount()
    {
        $count = RepairOrder::where('status', 'submitted')->count();

        return response()->json([
            'count' => $count,
        ]);
    }

    public function history()
    {
        $orders = RepairOrder::with([
            'user',
            'line',
            'area',
            'product',
            'masterModel',
            'ngType',
            'result',
            'confirmation',
        ])
            ->where('status', 'confirmed')
            ->latest('created_at')
            ->paginate(10);

        return view('omd.orders.history', compact('orders'));
    }
}
