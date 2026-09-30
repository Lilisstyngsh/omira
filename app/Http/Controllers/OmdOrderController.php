<?php

namespace App\Http\Controllers;

use App\Models\NgType;
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
            'result.processedBy',
            'confirmation.user',
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


    /*
    |--------------------------------------------------------------------------
    | COMPLETE / SELESAI REPAIR
    |--------------------------------------------------------------------------
    |
    | FORMAT BARU:
    | - 1 Produk memiliki item P/H/C/S
    | - Item NG yang sudah ada akan di-update
    | - Item NG yang belum ada dapat dibuat melalui new_items
    | - Keterangan disimpan per Produk melalui product_notes
    |
    */

    public function complete(
        Request $request,
        RepairOrder $order
    ) {
        abort_unless(
            $order->status === 'in_repair',
            422,
            'Order belum berada pada proses repair.'
        );


        $order->load([
            'items',
            'items.product',
            'items.masterModel',
            'items.ngType',
        ]);


        /*
        |--------------------------------------------------------------------------
        | FORMAT BARU
        |--------------------------------------------------------------------------
        */

        if ($order->items->isNotEmpty()) {

            $rules = [

                'items' => [
                    'required',
                    'array',
                ],

                'new_items' => [
                    'nullable',
                    'array',
                ],

                'new_items.*' => [
                    'array',
                ],

                'new_items.*.*' => [
                    'nullable',
                    'integer',
                    'min:0',
                ],

                /*
                |--------------------------------------------------------------------------
                | KETERANGAN PER PRODUK
                |--------------------------------------------------------------------------
                */

                'product_notes' => [
                    'nullable',
                    'array',
                ],

                'product_notes.*' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
            ];


            /*
            |--------------------------------------------------------------------------
            | VALIDASI ITEM YANG SUDAH ADA
            |--------------------------------------------------------------------------
            */

            foreach ($order->items as $item) {

                $rules['items.' . $item->id . '.after_qty'] = [
                    'nullable',
                    'integer',
                    'min:0',
                ];


                /*
                |--------------------------------------------------------------------------
                | BACKWARD COMPATIBILITY
                | Tetap menerima mismatch_note jika form lama masih mengirimnya
                |--------------------------------------------------------------------------
                */

                $rules['items.' . $item->id . '.mismatch_note'] = [
                    'nullable',
                    'string',
                    'max:1000',
                ];
            }


            $data = $request->validate($rules);


            /*
            |--------------------------------------------------------------------------
            | SIMPAN FORMAT BARU
            |--------------------------------------------------------------------------
            */

            DB::transaction(function () use (
                $order,
                $data
            ) {

                $productNotes =
                    $data['product_notes'] ?? [];


                /*
                |--------------------------------------------------------------------------
                | PRODUCT ID YANG MEMANG ADA DALAM ORDER
                |--------------------------------------------------------------------------
                |
                | Digunakan agar new_items tidak dapat memasukkan
                | product_id sembarangan.
                |
                */

                $allowedProductIds = $order->items
                    ->pluck('product_id')
                    ->filter()
                    ->map(function ($id) {
                        return (int) $id;
                    })
                    ->unique()
                    ->values();


                /*
                |--------------------------------------------------------------------------
                | UPDATE ITEM YANG SUDAH ADA
                |--------------------------------------------------------------------------
                */

                foreach ($order->items as $item) {

                    $itemData =
                        $data['items'][$item->id] ?? [];


                    /*
                    |--------------------------------------------------------------------------
                    | QTY SESUDAH
                    |--------------------------------------------------------------------------
                    */

                    $afterQty =
                        $itemData['after_qty'] ?? null;


                    /*
                    | Input kosong disimpan sebagai 0
                    */

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
                    |--------------------------------------------------------------------------
                    | KETERANGAN PER PRODUK
                    |--------------------------------------------------------------------------
                    |
                    | Satu produk memiliki satu keterangan.
                    | Karena mismatch_note berada di repair_order_items,
                    | nilai yang sama disimpan pada item NG produk tersebut.
                    |
                    */

                    $productId = $item->product_id;


                    if (
                        $productId !== null &&
                        array_key_exists(
                            $productId,
                            $productNotes
                        )
                    ) {

                        $note =
                            $productNotes[$productId];


                        $updateData['mismatch_note'] =
                            $note !== ''
                            ? $note
                            : null;
                    } elseif (
                        array_key_exists(
                            'mismatch_note',
                            $itemData
                        )
                    ) {

                        /*
                        | Kompatibilitas dengan form lama
                        */

                        $updateData['mismatch_note'] =
                            $itemData['mismatch_note'] !== ''
                            ? $itemData['mismatch_note']
                            : null;
                    }


                    $item->update($updateData);
                }


                /*
                |--------------------------------------------------------------------------
                | ITEM NG YANG SEBELUMNYA BELUM ADA
                |--------------------------------------------------------------------------
                |
                | Contoh:
                |
                | Sebelum:
                | P = -
                | H = 2
                | C = -
                | S = -
                |
                | Sesudah:
                | P = [1]
                | H = [2]
                | C = [0]
                | S = [1]
                |
                | P/C/S akan dibuat sebagai repair_order_items baru.
                |
                */

                if (!empty($data['new_items'])) {

                    $ngTypes = NgType::whereIn(
                        'code',
                        [
                            'P',
                            'H',
                            'C',
                            'S',
                        ]
                    )
                        ->get()
                        ->keyBy(function ($ngType) {
                            return strtoupper(
                                $ngType->code
                            );
                        });


                    foreach (
                        $data['new_items']
                        as $productId => $codes
                    ) {

                        $productId = (int) $productId;


                        /*
                        |--------------------------------------------------------------------------
                        | Pastikan product memang milik order ini
                        |--------------------------------------------------------------------------
                        */

                        if (
                            !$allowedProductIds->contains(
                                $productId
                            )
                        ) {
                            continue;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Cari item produk yang sudah ada
                        |--------------------------------------------------------------------------
                        */

                        $existingProductItem =
                            $order->items->first(
                                function ($item) use (
                                    $productId
                                ) {
                                    return
                                        (int) $item->product_id
                                        ===
                                        $productId;
                                }
                            );


                        if (!$existingProductItem) {
                            continue;
                        }


                        $masterModelId =
                            $existingProductItem->master_model_id;


                        /*
                        |--------------------------------------------------------------------------
                        | KETERANGAN PRODUK
                        |--------------------------------------------------------------------------
                        */

                        $productNote =
                            $productNotes[$productId]
                            ?? null;


                        foreach (
                            $codes
                            as $code => $afterQty
                        ) {

                            $code = strtoupper($code);


                            /*
                            |--------------------------------------------------------------------------
                            | Hanya P/H/C/S yang diperbolehkan
                            |--------------------------------------------------------------------------
                            */

                            $ngType =
                                $ngTypes->get($code);


                            if (!$ngType) {
                                continue;
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | Cek apakah item sudah ada
                            |--------------------------------------------------------------------------
                            */

                            $existingItem =
                                $order->items->first(
                                    function ($item) use (
                                        $productId,
                                        $ngType
                                    ) {
                                        return
                                            (int) $item->product_id
                                            ===
                                            $productId
                                            &&
                                            (int) $item->ng_type_id
                                            ===
                                            (int) $ngType->id;
                                    }
                                );


                            /*
                            |--------------------------------------------------------------------------
                            | Jika sudah ada
                            |--------------------------------------------------------------------------
                            */

                            if ($existingItem) {

                                $normalizedAfterQty =
                                    (
                                        $afterQty === ''
                                        ||
                                        $afterQty === null
                                    )
                                    ? 0
                                    : (int) $afterQty;


                                $updateData = [
                                    'after_qty' =>
                                    $normalizedAfterQty,
                                ];


                                if (
                                    array_key_exists(
                                        $productId,
                                        $productNotes
                                    )
                                ) {

                                    $updateData['mismatch_note'] =
                                        $productNote !== ''
                                        ? $productNote
                                        : null;
                                }


                                $existingItem->update(
                                    $updateData
                                );


                                continue;
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | Jika belum ada, buat item baru
                            |--------------------------------------------------------------------------
                            */

                            $normalizedAfterQty =
                                (
                                    $afterQty === ''
                                    ||
                                    $afterQty === null
                                )
                                ? 0
                                : (int) $afterQty;


                            $order->items()->create([

                                'master_model_id' =>
                                $masterModelId,

                                'product_id' =>
                                $productId,

                                'ng_type_id' =>
                                $ngType->id,

                                'before_qty' =>
                                0,

                                'after_qty' =>
                                $normalizedAfterQty,

                                'mismatch_note' => (
                                    $productNote !== null
                                    &&
                                    $productNote !== ''
                                )
                                    ? $productNote
                                    : null,

                            ]);
                        }
                    }
                }


                /*
|--------------------------------------------------------------------------
| SIMPAN PETUGAS REPAIR
|--------------------------------------------------------------------------
*/

                $order->result()->updateOrCreate(
                    [
                        'repair_order_id' => $order->id,
                    ],
                    [
                        'processed_by' => auth()->id(),
                    ]
                );


                /*
|--------------------------------------------------------------------------
| ORDER SELESAI
|--------------------------------------------------------------------------
*/

                $order->update([

                    'status' =>
                    'completed',

                    'repair_completed_at' =>
                    now(),

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
        |--------------------------------------------------------------------------
        |
        | Tetap dipertahankan untuk order existing yang belum memiliki
        | repair_order_items.
        |
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


        /*
        |--------------------------------------------------------------------------
        | Validasi total hasil repair format lama
        |--------------------------------------------------------------------------
        */

        $sum =
            $data['ok_qty']
            +
            $data['scrap_qty']
            +
            $data['ng_qty'];


        if ($sum > $order->quantity) {

            return back()
                ->withErrors([
                    'ok_qty' =>
                    'Total hasil repair tidak boleh melebihi quantity order.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Simpan hasil repair format lama
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $request,
            $order,
            $data
        ) {

            $order->update([

                'status' =>
                'completed',

                'repair_completed_at' =>
                now(),

            ]);


            $order->result()->updateOrCreate(

                [
                    'repair_order_id' =>
                    $order->id,
                ],

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
    | NOTIFICATION
    |--------------------------------------------------------------------------
    */

    public function pendingCount()
    {
        $count = RepairOrder::query()
            ->where('status', 'submitted')
            ->count();


        return response()->json([
            'count' => $count,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | HISTORY
    |--------------------------------------------------------------------------
    */

    public function history(Request $request)
    {
        $request->validate([
            'start_date' => [
                'nullable',
                'date',
            ],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'per_page' => [
                'nullable',
                'integer',
                'in:10,25,50,100',
            ],

            'search' => [
                'nullable',
                'string',
                'max:100',
            ],
        ]);


        $startDate =
            $request->input('start_date');


        $endDate =
            $request->input('end_date');

        $search = trim($request->input('search', ''));


        /*
        |--------------------------------------------------------------------------
        | PER PAGE
        |--------------------------------------------------------------------------
        */

        $perPage =
            (int) $request->input(
                'per_page',
                10
            );


        if (
            !in_array(
                $perPage,
                [
                    10,
                    25,
                    50,
                    100,
                ],
                true
            )
        ) {
            $perPage = 10;
        }


        /*
        |--------------------------------------------------------------------------
        | QUERY HISTORY
        |--------------------------------------------------------------------------
        */

        $query = RepairOrder::with([
            'user',
            'line.plant',
            'area',
            'items.masterModel',
            'items.product',
            'items.ngType',
            'result',
            'confirmation',
            'omdVerifier',
        ])
            ->where(
                'status',
                'confirmed'
            )
            ->latest(
                'created_at'
            );

        if ($search !== '') {

            $query->where(function ($q) use ($search) {

                $q->where(
                    'order_number',
                    'like',
                    '%' . $search . '%'
                )

                    ->orWhereHas('user', function ($userQuery) use ($search) {

                        $userQuery->where(
                            'name',
                            'like',
                            '%' . $search . '%'
                        );
                    })

                    ->orWhereHas('line', function ($lineQuery) use ($search) {

                        $lineQuery->where(
                            'name',
                            'like',
                            '%' . $search . '%'
                        );
                    });
            });
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER DARI TANGGAL
        |--------------------------------------------------------------------------
        */

        if ($startDate) {

            $query->whereDate(
                'created_at',
                '>=',
                $startDate
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER SAMPAI TANGGAL
        |--------------------------------------------------------------------------
        */

        if ($endDate) {

            $query->whereDate(
                'created_at',
                '<=',
                $endDate
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $orders = $query
            ->paginate($perPage)
            ->withQueryString();


        return view(
            'omd.orders.history',
            compact(
                'orders',
                'startDate',
                'endDate'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | HISTORY DETAIL
    |--------------------------------------------------------------------------
    */

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
