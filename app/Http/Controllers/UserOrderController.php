<?php

namespace App\Http\Controllers;

use App\Models\Line;
use App\Models\MasterModel;
use App\Models\NgType;
use App\Models\RepairOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = RepairOrder::with([
            'line',
            'result',
            'confirmation',
        ])
            ->withSum('items as before_qty_sum', 'before_qty')
            ->withSum('items as after_qty_sum', 'after_qty')
            ->where('user_id', $request->user()->id)
            ->whereIn('status', [
                'submitted',
                'in_repair',
                'completed',
                'revision_requested',
            ])
            ->latest('created_at')
            ->paginate(10);

        return view('user.orders.index', compact('orders'));
    }


    public function create(Request $request)
    {
        $user = $request->user();

        abort_unless(
            $user->role === 'user',
            403
        );

        abort_unless(
            $user->line_id,
            422,
            'Akun user belum memiliki Line.'
        );

        $line = Line::query()
            ->whereKey($user->line_id)
            ->where('is_active', true)
            ->with('plant')
            ->firstOrFail();

        $models = MasterModel::query()
            ->where('line_id', $user->line_id)
            ->where('is_active', true)
            ->with([
                'products' => function ($query) {
                    $query
                        ->where('is_active', true)
                        ->orderBy('name');
                }
            ])
            ->orderBy('number')
            ->orderBy('model')
            ->get();

        $ngCodes = [
            'P',
            'H',
            'C',
            'S',
        ];

        $ngTypes = NgType::query()
            ->where('is_active', true)
            ->whereIn('code', $ngCodes)
            ->get()
            ->sortBy(function ($ngType) use ($ngCodes) {
                return array_search(
                    strtoupper($ngType->code),
                    $ngCodes,
                    true
                );
            })
            ->values();

        return view('user.orders.create', [
            'line' => $line,
            'models' => $models,
            'ngTypes' => $ngTypes,
        ]);
    }


    public function store(Request $request)
    {
        $user = $request->user();

        abort_unless(
            $user->role === 'user',
            403
        );

        abort_unless(
            $user->line_id,
            422,
            'Akun user belum memiliki Line.'
        );

        $data = $request->validate([
            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.master_model_id' => [
                'required',
                'integer',
                'exists:master_models,id',
            ],

            'items.*.product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],

            'items.*.qty' => [
                'nullable',
                'array',
            ],

            'items.*.qty.*' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ]);


        $line = Line::query()
            ->whereKey($user->line_id)
            ->where('is_active', true)
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Jenis NG yang digunakan sistem
        |--------------------------------------------------------------------------
        */

        $ngCodes = [
            'P',
            'H',
            'C',
            'S',
        ];


        $ngTypes = NgType::query()
            ->where('is_active', true)
            ->whereIn('code', $ngCodes)
            ->get()
            ->keyBy(function ($ngType) {
                return strtoupper($ngType->code);
            });


        if ($ngTypes->count() !== count($ngCodes)) {
            abort(
                422,
                'Master Jenis NG P, H, C, dan S belum lengkap.'
            );
        }


        $ngTypesById = $ngTypes->keyBy('id');


        /*
        |--------------------------------------------------------------------------
        | Validasi dan kumpulkan data Produk
        |--------------------------------------------------------------------------
        */

        $selectedProducts = [];


        foreach ($data['items'] as $item) {

            $model = MasterModel::query()
                ->whereKey($item['master_model_id'])
                ->where('line_id', $line->id)
                ->where('is_active', true)
                ->first();

            if (!$model) {
                abort(
                    422,
                    'Model tidak sesuai dengan Line User.'
                );
            }


            $product = $model->products()
                ->whereKey($item['product_id'])
                ->where('is_active', true)
                ->first();

            if (!$product) {
                abort(
                    422,
                    'Produk tidak sesuai dengan Model yang dipilih.'
                );
            }


            $productKey = $model->id . ':' . $product->id;


            if (!isset($selectedProducts[$productKey])) {

                $selectedProducts[$productKey] = [
                    'master_model_id' => $model->id,
                    'product_id' => $product->id,
                    'qty' => [
                        'P' => 0,
                        'H' => 0,
                        'C' => 0,
                        'S' => 0,
                    ],
                ];
            }


            foreach (($item['qty'] ?? []) as $ngTypeId => $quantity) {

                $quantity = (int) ($quantity ?? 0);


                $ngType = $ngTypesById->get($ngTypeId);


                if (!$ngType) {
                    abort(
                        422,
                        'Jenis NG tidak valid.'
                    );
                }


                $ngCode = strtoupper($ngType->code);


                if (!in_array($ngCode, $ngCodes, true)) {
                    abort(
                        422,
                        'Jenis NG tidak diperbolehkan.'
                    );
                }


                $selectedProducts[$productKey]['qty'][$ngCode] += $quantity;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Buat 4 Repair Order Item untuk setiap Produk yang dipilih
        |--------------------------------------------------------------------------
        |
        | Contoh:
        |
        | Produk A
        | P = 2
        | H = 0
        | C = 3
        | S = 0
        |
        | Database:
        | Produk A | P | 2
        | Produk A | H | 0
        | Produk A | C | 3
        | Produk A | S | 0
        |
        */

        $preparedItems = [];
        $totalQuantity = 0;


        foreach ($selectedProducts as $selectedProduct) {

            $productTotal =
                $selectedProduct['qty']['P'] +
                $selectedProduct['qty']['H'] +
                $selectedProduct['qty']['C'] +
                $selectedProduct['qty']['S'];


            /*
             * Produk yang seluruh Qty NG-nya kosong
             * tidak ikut dibuat ke dalam order.
             */
            if ($productTotal <= 0) {
                continue;
            }


            foreach ($ngCodes as $ngCode) {

                $ngType = $ngTypes->get($ngCode);


                $beforeQty =
                    (int) $selectedProduct['qty'][$ngCode];


                $preparedItems[] = [
                    'master_model_id' => $selectedProduct['master_model_id'],
                    'product_id' => $selectedProduct['product_id'],
                    'ng_type_id' => $ngType->id,
                    'before_qty' => $beforeQty,
                ];


                $totalQuantity += $beforeQty;
            }
        }


        if (empty($preparedItems) || $totalQuantity <= 0) {

            return back()
                ->withErrors([
                    'items' => 'Masukkan minimal satu Quantity NG.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Buat Token Order
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $data,
            $user,
            $line,
            $preparedItems,
            $totalQuantity,
            &$order
        ) {

            $year = now()->year;


            $lastSequence = RepairOrder::query()
                ->where('line_id', $line->id)
                ->where('order_year', $year)
                ->lockForUpdate()
                ->max('sequence');


            $sequence = ($lastSequence ?? 0) + 1;


            $lineCode = $this->lineCode($line->name);


            $orderNumber = sprintf(
                'RB-%s-%d-%03d',
                $lineCode,
                $year,
                $sequence
            );


            $firstItem = $preparedItems[0];


            $order = RepairOrder::create([
                'order_number' => $orderNumber,

                'user_id' => $user->id,

                'line_id' => $line->id,

                'order_year' => $year,

                'sequence' => $sequence,

                /*
                 * Field lama tetap diisi untuk kompatibilitas
                 * dengan struktur RepairOrder existing.
                 */
                'master_model_id' => $firstItem['master_model_id'],

                'product_id' => $firstItem['product_id'],

                'ng_type_id' => $firstItem['ng_type_id'],

                'order_date' => now()->toDateString(),

                'quantity' => $totalQuantity,

                'description' => $data['description'] ?? null,

                'status' => 'submitted',
            ]);


            foreach ($preparedItems as $item) {

                $order->items()->create([
                    'master_model_id' => $item['master_model_id'],

                    'product_id' => $item['product_id'],

                    'ng_type_id' => $item['ng_type_id'],

                    'before_qty' => $item['before_qty'],
                ]);
            }
        });


        return redirect()
            ->route('user.orders.index')
            ->with(
                'success',
                'Order Repair Box berhasil dikirim ke OMD.'
            );
    }


    public function show(
        Request $request,
        RepairOrder $order
    ) {
        abort_unless(
            $order->user_id === $request->user()->id,
            403
        );

        $order->load([
            'user',
            'line.plant',
            'items.masterModel',
            'items.product',
            'items.afterProduct',
            'items.ngType',
            'result',
            'result.processedBy',
            'confirmation',
            'omdVerifier',
            'handedOverBy',
        ]);

        return view(
            'user.orders.show',
            compact('order')
        );
    }
    public function requestRevision(
        Request $request,
        RepairOrder $order
    ) {
        abort_unless(
            $order->user_id === $request->user()->id,
            403
        );

        abort_unless(
            $order->status === 'completed',
            422,
            'Permintaan koreksi hanya dapat dilakukan setelah OMD menyerahkan hasil repair.'
        );

        $order->update([
            'status' => 'revision_requested',
        ]);

        return redirect()
            ->route('user.orders.show', $order)
            ->with(
                'success',
                'Ketidaksesuaian sudah dikirim ke OMD. OMD dapat melakukan koreksi hasil repair.'
            );
    }


    public function confirm(
        Request $request,
        RepairOrder $order
    ) {
        abort_unless(
            $order->user_id === $request->user()->id,
            403
        );


        abort_unless(
            $order->status === 'completed',
            422,
            'Order belum selesai diproses.'
        );


        $order->update([
            'status' => 'confirmed',
        ]);


        $order->confirmation()->updateOrCreate(
            [],
            [
                'confirmed_by_user_id' => $request->user()->id,

                'confirmed_at' => now(),
            ]
        );


        return redirect()
            ->route('user.orders.index')
            ->with(
                'success',
                'Order berhasil dikonfirmasi. Proses Repair Box selesai.'
            );
    }


    public function history(Request $request)
    {
        $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'per_page' => ['nullable', 'integer', 'in:10,25,50,100'],
            'search' => ['nullable', 'string', 'max:100'],
            'line_id' => ['nullable', 'integer', 'exists:lines,id'],
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = trim((string) $request->input('search', ''));
        $lineId = $request->filled('line_id') ? (int) $request->input('line_id') : null;
        $perPage = (int) $request->input('per_page', 10);

        $query = RepairOrder::with([
            'line',
            'result',
            'confirmation',
        ])
            ->withSum('items as before_qty_sum', 'before_qty')
            ->withSum('items as after_qty_sum', 'after_qty')
            ->where('user_id', $request->user()->id)
            ->where('status', 'confirmed');

        if ($search !== '') {
            $query->where(function ($subQuery) use ($search) {
                $subQuery
                    ->where('order_number', 'like', '%' . $search . '%')
                    ->orWhereHas('line', fn ($lineQuery) =>
                        $lineQuery->where('name', 'like', '%' . $search . '%')
                    );
            });
        }

        if ($lineId) {
            $query->where('line_id', $lineId);
        }

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $orders = $query
            ->latest('created_at')
            ->paginate($perPage)
            ->withQueryString();

        $historyLineIds = RepairOrder::query()
            ->where('user_id', $request->user()->id)
            ->where('status', 'confirmed')
            ->whereNotNull('line_id')
            ->distinct()
            ->pluck('line_id');

        $lines = Line::query()
            ->whereIn('id', $historyLineIds)
            ->orderBy('name')
            ->get();

        return view('user.orders.history', compact(
            'orders',
            'startDate',
            'endDate',
            'lines',
            'lineId'
        ));
    }

    public function historyShow(
        Request $request,
        RepairOrder $order
    ) {
        abort_unless(
            $order->user_id === $request->user()->id,
            403
        );

        $order->load([
            'user',
            'line.plant',
            'items.masterModel',
            'items.product',
            'items.afterProduct',
            'items.ngType',
            'result',
            'result.processedBy',
            'confirmation',
            'omdVerifier',
            'handedOverBy',
        ]);

        return view(
            'user.orders.history-show',
            compact('order')
        );
    }


    public function pendingConfirmationCount(Request $request)
    {
        $count = RepairOrder::query()
            ->where('user_id', $request->user()->id)
            ->where('status', 'completed')
            ->count();


        return response()->json([
            'count' => $count,
        ]);
    }


    private function lineCode(string $lineName): string
    {
        return match (strtolower(trim($lineName))) {

            'inj' => 'IJ',

            'painting',
            'pt' => 'PT',

            'as unit' => 'AU',

            'machining',
            'ma' => 'MA',

            'die casting',
            'dc' => 'DC',

            'ppic unit' => 'PU',

            'as electric' => 'AE',

            'ppic electric',
            'ppic electris' => 'PE',

            'as body' => 'AB',

            'ppic body' => 'PB',

            default => strtoupper(
                Str::substr(
                    preg_replace(
                        '/[^A-Za-z]/',
                        '',
                        $lineName
                    ),
                    0,
                    2
                )
            ),
        };
    }
}
