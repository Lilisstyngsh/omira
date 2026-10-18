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
            ->with(['decisions', 'items'])->latest('id')->paginate(15);
        return view('user.additional-box.index', compact('requests'));
    }

    public function store(Request $request, UserScrapLimitService $service)
    {
        $data = $request->validate([
            'submission_token' => ['required', 'uuid'],
            'items' => ['prohibited'], // No more browser-supplied quantities (including the old form).
            'decisions' => ['required', 'array', 'min:1', 'max:100'],
            'decisions.*.cycle_id' => ['required', 'integer', 'distinct'],
            'decisions.*.decision' => ['required', 'in:yes,no'],
            'decisions.*.reason' => ['nullable', 'string', 'max:1000'],
            'decisions.*.quantity' => ['prohibited'],
        ]);

        $document = DB::transaction(function () use ($request, $data, $service) {
            // Serialise submissions and confirmations by the same account.
            $user = User::query()->lockForUpdate()->findOrFail($request->user()->id);
            $existing = AdditionalBoxRequest::query()->where('submission_token', $data['submission_token'])->first();
            if ($existing) {
                abort_unless((int) $existing->user_id === (int) $user->id, 403);
                return $existing; // Idempotent retries of requests containing Order selections.
            }

            $decisions = collect($data['decisions'])->values();
            $ids = $decisions->pluck('cycle_id');
            $cycles = UserScrapLimitCycle::query()->where('user_id', $user->id)
                ->whereIn('id', $ids)->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            if ($cycles->count() !== $ids->count()) {
                throw ValidationException::withMessages(['decisions' => 'Produk tidak valid atau bukan milik akun Anda.']);
            }

            // Validate the complete batch before writing ANY decisions or resetting cycles.
            $quantities = [];
            foreach ($decisions as $item) {
                $cycle = $cycles->get($item['cycle_id']);
                if ($cycle->reset_at || !$cycle->reached_at) {
                    throw ValidationException::withMessages(['decisions' => 'Peringatan sudah diproses. Muat ulang dashboard.']);
                }
                if ($item['decision'] === 'no' && trim((string) ($item['reason'] ?? '')) === '') {
                    throw ValidationException::withMessages(['decisions' => 'Alasan wajib diisi untuk pilihan Tidak.']);
                }
                $cycle->loadMissing('product.masterModel');
                if (!$cycle->product || !$cycle->product->masterModel) {
                    throw ValidationException::withMessages(['decisions' => 'Model atau produk tidak tersedia. Hubungi OMD.']);
                }
                // Confirmed OMD Scrap (NG S), after user confirmation, in THIS cycle only.
                $qty = $service->quantity($user, $cycle->product_id, $cycle);
                if ($qty < (int) $cycle->limit_qty || $qty < 1) {
                    throw ValidationException::withMessages(['decisions' => 'Scrap terkonfirmasi berubah. Muat ulang dashboard.']);
                }
                $quantities[$cycle->id] = $qty;
            }

            // One history document for the entire batch, including all-No decisions.
            $document = AdditionalBoxRequest::create([
                'user_id' => $user->id,
                'submission_token' => $data['submission_token'],
                'purpose' => AdditionalBoxRequest::PURPOSE,
            ]);

            foreach ($decisions as $item) {
                $cycle = $cycles->get($item['cycle_id']);
                // History snapshots include Order and Tidak; export items include Order only.
                $document->decisions()->create([
                    'user_scrap_limit_cycle_id' => $cycle->id,
                    'product_id' => $cycle->product_id,
                    'model_name' => $cycle->product->masterModel->model,
                    'product_name' => $cycle->product->name,
                    'scrap_qty' => $quantities[$cycle->id],
                    'limit_qty' => $cycle->limit_qty,
                    'decision' => $item['decision'],
                    'reason' => $item['decision'] === 'no' ? trim($item['reason']) : null,
                    'quantity' => $item['decision'] === 'yes' ? $quantities[$cycle->id] : null,
                ]);
                if ($item['decision'] === 'yes') {
                    $document->items()->create([
                        'user_scrap_limit_cycle_id' => $cycle->id,
                        'product_id' => $cycle->product_id,
                        'model_name' => $cycle->product->masterModel->model,
                        'product_name' => $cycle->product->name,
                        'quantity' => $quantities[$cycle->id],
                    ]);
                }
                $cycle->update([
                    'decision' => $item['decision'],
                    'decision_reason' => $item['decision'] === 'no' ? trim($item['reason']) : null,
                    'decided_at' => now(),
                ]);
                $service->reset($cycle, $user, $item['decision'] === 'yes'
                    ? 'Reset otomatis setelah request additional box ' . $document->document_number
                    : 'Reset otomatis setelah keputusan Tidak Order.');
            }

            return $document;
        }, 3);

        return redirect()->route('user.additional-box.show', $document)
            ->with('success', 'Keputusan berhasil disimpan ke riwayat Additional Box.');
    }

    public function show(Request $request, AdditionalBoxRequest $document)
    {
        abort_unless((int) $document->user_id === (int) $request->user()->id, 403);
        $document->load(['items', 'decisions']);
        return view('user.additional-box.show', compact('document'));
    }

    public function preview(Request $request, AdditionalBoxRequest $document, AdditionalBoxExportService $service)
    {
        abort_unless((int) $document->user_id === (int) $request->user()->id, 403);
        $document->load('items');
        abort_if($document->items->isEmpty(), 404, 'Tidak ada produk Order untuk dipreview.');

        return $service->previewPdf($document);
    }

    public function export(Request $request, AdditionalBoxRequest $document, string $format, AdditionalBoxExportService $service)
    {
        abort_unless((int) $document->user_id === (int) $request->user()->id, 403);
        abort_unless(in_array($format, ['pdf', 'excel'], true), 404);
        $document->load('items');
        // A no-only request has history but never produces an empty PDF/Excel.
        abort_if($document->items->isEmpty(), 404, 'Tidak ada produk Order untuk diekspor.');
        return $format === 'pdf' ? $service->pdf($document) : $service->excel($document);
    }
}
