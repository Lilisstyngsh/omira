<?php

namespace App\Http\Controllers;

use App\Models\Line;
use App\Models\MasterModel;
use App\Models\NgType;
use App\Models\RepairOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class UserOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = RepairOrder::with([
            'line',
            'result',
            'confirmation',
            'handedOverBy',
        ])
            ->where('user_id', $request->user()->id)
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

        $line = Line::with('plant')->findOrFail($user->line_id);

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

        $ngTypes = NgType::where('is_active', true)
            ->orderBy('code')
            ->get();

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
                'max:1000'
            ],

            'items' => [
                'required',
                'array',
                'min:1'
            ],

            'items.*.master_model_id' => [
                'required',
                'integer',
                'exists:master_models,id'
            ],

            'items.*.product_id' => [
                'required',
                'integer',
                'exists:products,id'
            ],

            'items.*.qty' => [
                'nullable',
                'array'
            ],

            'items.*.qty.*' => [
                'nullable',
                'integer',
                'min:0'
            ],
        ]);

        $line = Line::findOrFail($user->line_id);

        /*
    |--------------------------------------------------------------------------
    | Validasi item dan hitung total
    |--------------------------------------------------------------------------
    */

        $preparedItems = [];
        $totalQuantity = 0;

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

            foreach (($item['qty'] ?? []) as $ngTypeId => $quantity) {

                $quantity = (int) ($quantity ?? 0);

                if ($quantity <= 0) {
                    continue;
                }

                $ngType = NgType::query()
                    ->whereKey($ngTypeId)
                    ->where('is_active', true)
                    ->first();

                if (!$ngType) {
                    abort(
                        422,
                        'Jenis NG tidak valid.'
                    );
                }

                $preparedItems[] = [
                    'master_model_id' => $model->id,
                    'product_id' => $product->id,
                    'ng_type_id' => $ngType->id,
                    'before_qty' => $quantity,
                ];

                $totalQuantity += $quantity;
            }
        }

        if (empty($preparedItems)) {
            return back()
                ->withErrors([
                    'items' => 'Masukkan minimal satu Quantity NG.'
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
            ->route('user.orders.index', $order)
            ->with(
                'success',
                'Order Repair Box berhasil dikirim ke OMD.'
            );
    }

    public function show(Request $request, RepairOrder $order)
    {
        abort_unless(
            $order->user_id === $request->user()->id,
            403
        );

        $order->load([
            'user',
            'line.plant',
            'items.masterModel',
            'items.product',
            'items.ngType',
            'result',
            'confirmation',
            'omdVerifier',
            'handedOverBy',
        ]);

        return view(
            'user.orders.show',
            compact('order')
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

        return back()->with(
            'success',
            'Order berhasil dikonfirmasi. Proses Repair Box selesai.'
        );
    }

    private function lineCode(string $lineName): string
    {
        return match (strtolower(trim($lineName))) {
            'inj' => 'IJ',
            'painting', 'pt' => 'PT',
            'as unit' => 'AU',
            'machining', 'ma' => 'MA',
            'die casting', 'dc' => 'DC',
            'ppic unit' => 'PU',
            'as electric' => 'AE',
            'ppic electric', 'ppic electris' => 'PE',
            'as body' => 'AB',
            'ppic body' => 'PB',
            default => strtoupper(
                Str::substr(
                    preg_replace('/[^A-Za-z]/', '', $lineName),
                    0,
                    2
                )
            ),
        };
    }
}
