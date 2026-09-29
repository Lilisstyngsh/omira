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
                'in_repair',
                'completed',
            ])
            ->latest('created_at')
            ->paginate(10);


        $completedCount = RepairOrder::query()
            ->where('status', 'completed')
            ->count();


        return view(
            'omd.orders.index',
            compact(
                'orders',
                'completedCount'
            )
        );
    }


    public function show(RepairOrder $order)
    {
        $order->load([
            'user',
            'line.plant',
            'area',
            'product',
            'masterModel',
            'ngType',
            'result',
            'confirmation',
            'omdVerifier',
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
            'status' => 'in_repair',

            'verified_by' => $request->user()->id,

            'verified_at' => now(),

            'repair_started_at' => now(),
        ]);


        return redirect()
            ->route(
                'omd.orders.show',
                $order
            )
            ->with(
                'success',
                'Order berhasil diverifikasi dan langsung masuk proses repair.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Tetap dipertahankan untuk kompatibilitas route lama
    |--------------------------------------------------------------------------
    */

    public function startRepair(
        RepairOrder $order
    ) {
        abort(
            422,
            'Proses Mulai Repair dilakukan otomatis saat verifikasi.'
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
        | 1 Produk memiliki item P/H/C/S
        |--------------------------------------------------------------------------
        */

        if ($order->items->isNotEmpty()) {

            $rules = [
                'items' => [
                    'required',
                    'array',
                ],
            ];


            foreach ($order->items as $item) {

                $rules['items.' . $item->id . '.after_qty'] = [
                    'nullable',
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
                        $data['items'][$item->id] ?? [];


                    /*
                     * Karena input OMD boleh kosong saat pertama dibuka,
                     * nilai kosong dinormalisasi menjadi 0 saat disimpan.
                     */
                    $afterQty =
                        $itemData['after_qty'] ?? null;


                    if (
                        $afterQty === null ||
                        $afterQty === ''
                    ) {
                        $afterQty = 0;
                    }


                    $updateData = [
                        'after_qty' => (int) $afterQty,
                    ];


                    /*
                     * mismatch_note hanya diperbarui jika memang
                     * dikirim oleh form.
                     */
                    if (
                        array_key_exists(
                            'mismatch_note',
                            $itemData
                        )
                    ) {
                        $updateData['mismatch_note'] =
                            $itemData['mismatch_note'] !== ''
                            ? $itemData['mismatch_note']
                            : null;
                    }


                    $item->update($updateData);
                }


                $order->update([
                    'status' => 'completed',

                    'repair_completed_at' => now(),
                ]);
            });


            return redirect()
                ->route(
                    'omd.orders.index'
                )
                ->with(
                    'success',
                    'Order Repair Box selesai dan menunggu konfirmasi dari User.'
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


        return redirect()
            ->route('omd.orders.index')
            ->with(
                'success',
                'Order Repair Box selesai dan menunggu konfirmasi dari User.'
            );
    }


    public function pendingCount()
    {
        $count = RepairOrder::query()
            ->where('status', 'submitted')
            ->count();


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
            'items.masterModel',
            'items.product',
            'items.ngType',
        ])
            ->where('status', 'confirmed')
            ->latest('created_at')
            ->paginate(10);


        return view(
            'omd.orders.history',
            compact('orders')
        );
    }


    public function historyShow(
        RepairOrder $order
    ) {
        abort_unless(
            $order->status === 'confirmed',
            404
        );


        $order->load([
            'user',
            'line.plant',
            'area',
            'product',
            'masterModel',
            'ngType',
            'result',
            'confirmation',
            'omdVerifier',
            'items.masterModel',
            'items.product',
            'items.ngType',
        ]);


        return view(
            'omd.orders.history-show',
            compact('order')
        );
    }
}
