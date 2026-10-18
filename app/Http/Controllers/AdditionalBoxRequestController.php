<?php
namespace App\Http\Controllers;

use App\Models\AdditionalBoxRequest;
use App\Models\User;
use App\Models\UserScrapLimitCycle;
use App\Services\AdditionalBoxExportService;
use App\Services\UserScrapLimitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdditionalBoxRequestController extends Controller
{
    public function index(Request $request)
    {
        $requests = AdditionalBoxRequest::query()->where('user_id', $request->user()->id)
            ->withCount('items')->latest('id')->paginate(15);
        return view('user.additional-box.index', compact('requests'));
    }

    public function store(Request $request, UserScrapLimitService $service)
    {
        $data = $request->validate([
            'submission_token' => ['required', 'uuid'],
            'items' => ['required', 'array', 'min:1', 'max:100'],
            'items.*.cycle_id' => ['required', 'integer', 'distinct'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:1000000'],
        ]);
        $document = DB::transaction(function () use ($request, $data, $service) {
            $user = User::query()->lockForUpdate()->findOrFail($request->user()->id);
            $existing = AdditionalBoxRequest::where('submission_token', $data['submission_token'])->first();
            if ($existing) {
                abort_unless((int) $existing->user_id === (int) $user->id, 403);
                return $existing;
            }
            $ids = collect($data['items'])->pluck('cycle_id');
            $cycles = UserScrapLimitCycle::query()->where('user_id', $user->id)
                ->whereIn('id', $ids)->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            if ($cycles->count() !== $ids->count()) {
                throw ValidationException::withMessages(['items' => 'Produk peringatan tidak valid atau bukan milik akun Anda.']);
            }
            foreach ($cycles as $cycle) {
                if ($cycle->reset_at || !$cycle->reached_at) {
                    throw ValidationException::withMessages(['items' => 'Peringatan sudah diproses atau direset. Muat ulang dashboard.']);
                }
                $cycle->load('product.masterModel');
                if (!$cycle->product || !$cycle->product->masterModel) {
                    throw ValidationException::withMessages(['items' => 'Data model/produk tidak tersedia. Hubungi OMD.']);
                }
            }
            $document = AdditionalBoxRequest::create([
                'user_id' => $user->id, 'submission_token' => $data['submission_token'],
                'purpose' => AdditionalBoxRequest::PURPOSE,
            ]);
            foreach ($data['items'] as $item) {
                $cycle = $cycles->get($item['cycle_id']);
                $document->items()->create([
                    'user_scrap_limit_cycle_id' => $cycle->id,
                    'product_id' => $cycle->product_id,
                    'model_name' => $cycle->product->masterModel->model,
                    'product_name' => $cycle->product->name,
                    'quantity' => $item['quantity'],
                ]);
                $cycle->update(['decision' => 'yes', 'decision_reason' => null, 'decided_at' => now()]);
                $service->reset($cycle, $user, 'Reset otomatis setelah permintaan additional box ' . $document->document_number);
            }
            return $document;
        }, 3);
        return redirect()->route('user.additional-box.show', $document)
            ->with('success', 'Permintaan tersimpan. Siklus scrap produk terpilih telah direset. PDF dan Excel siap diunduh.');
    }

    public function show(Request $request, AdditionalBoxRequest $document)
    {
        abort_unless((int) $document->user_id === (int) $request->user()->id, 403);
        $document->load('items');
        return view('user.additional-box.show', compact('document'));
    }

    public function export(Request $request, AdditionalBoxRequest $document, string $format, AdditionalBoxExportService $service)
    {
        abort_unless((int) $document->user_id === (int) $request->user()->id, 403);
        abort_unless(in_array($format, ['pdf', 'excel'], true), 404);
        $document->load('items');
        return $format === 'pdf' ? $service->pdf($document) : $service->excel($document);
    }
}
