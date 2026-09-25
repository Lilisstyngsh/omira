<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\MasterModel;
use App\Models\NgType;
use App\Models\RepairOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = RepairOrder::with(['area', 'product', 'masterModel', 'result'])
            ->where('user_id', $request->user()->id)
            ->latest('order_date')
            ->latest()
            ->paginate(10);

        return view('user.orders.index', compact('orders'));
    }

    public function create(Request $request)
    {
        $userGroup = $request->user()->user_group;

        abort_unless(in_array($userGroup, ['ppic', 'produksi'], true), 422, 'Akun user belum memiliki kelompok PPIC atau Produksi.');

        $models = MasterModel::query()
            ->where('data_scope', $userGroup)
            ->where('is_active', true)
            ->with(['products' => fn ($query) => $query->where('is_active', true)->orderBy('name')])
            ->orderBy('number')
            ->get();

        return view('user.orders.create', [
            'areas' => Area::where('is_active', true)->orderBy('category')->orderBy('name')->get(),
            'models' => $models,
            'ngTypes' => NgType::where('is_active', true)->orderBy('code')->get(),
            'userGroupLabel' => strtoupper($userGroup),
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $scope = $user->user_group;

        abort_unless(in_array($scope, ['ppic', 'produksi'], true), 422, 'Akun user belum memiliki kelompok PPIC atau Produksi.');

        $data = $request->validate([
            'area_id' => ['required', 'exists:areas,id'],
            'master_model_id' => [
                'required',
                'integer',
                Rule::exists('master_models', 'id')->where(fn ($query) => $query
                    ->where('data_scope', $scope)
                    ->where('is_active', true)),
            ],
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'ng_type_id' => ['required', 'exists:ng_types,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $model = MasterModel::query()
            ->whereKey($data['master_model_id'])
            ->where('data_scope', $scope)
            ->where('is_active', true)
            ->firstOrFail();

        $product = $model->products()
            ->whereKey($data['product_id'])
            ->where('is_active', true)
            ->first();

        if (! $product) {
            return back()
                ->withErrors(['product_id' => 'Produk tidak sesuai dengan model yang dipilih.'])
                ->withInput();
        }

        $data['user_id'] = $user->id;
        $data['master_model_id'] = $model->id;
        $data['product_id'] = $product->id;
        $data['model'] = $model->model; // kompatibilitas dengan struktur transaksi lama.
        $data['order_number'] = 'RO-' . now()->format('ymd') . '-' . Str::upper(Str::random(5));
        $data['order_date'] = now()->toDateString();
        $data['status'] = 'submitted';

        $order = RepairOrder::create($data);

        return redirect()
            ->route('user.orders.show', $order)
            ->with('success', 'Order repair berhasil dikirim ke OMD.');
    }

    public function show(Request $request, RepairOrder $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        $order->load(['area', 'product', 'masterModel', 'ngType', 'result', 'confirmation', 'omdVerifier']);

        return view('user.orders.show', compact('order'));
    }

    public function confirm(Request $request, RepairOrder $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        abort_unless($order->status === 'completed', 422, 'Order belum siap dikonfirmasi.');

        $order->update(['status' => 'confirmed']);
        $order->confirmation()->updateOrCreate([], [
            'confirmed_by_user_id' => $request->user()->id,
            'confirmed_at' => now(),
        ]);

        return back()->with('success', 'Order berhasil dikonfirmasi oleh user.');
    }
}
