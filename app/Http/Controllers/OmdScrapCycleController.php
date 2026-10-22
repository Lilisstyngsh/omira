<?php

namespace App\Http\Controllers;

use App\Models\AdditionalBoxRequestDecision;
use App\Models\Line;
use App\Models\UserScrapLimitCycle;
use App\Services\ScrapHistoryExportService;
use App\Services\UserScrapLimitService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OmdScrapCycleController extends Controller
{
    public function index(Request $request)
    {
        $filters = $this->validatedFilters($request);
        $cycles = $this->cyclesQuery($filters)
            ->paginate((int) ($filters['per_page'] ?? 10))
            ->withQueryString();

        $lines = Line::query()->with('plant')->orderBy('name')->get();

        return view('omd.scrap-limits.cycles', compact('cycles', 'lines'));
    }

    public function export(Request $request, string $format, ScrapHistoryExportService $exportService)
    {
        abort_unless(in_array($format, ['pdf', 'excel'], true), 404);

        $filters = $this->validatedFilters($request);
        $cycles = $this->cyclesQuery($filters)->get();
        $cycleIds = $cycles->pluck('id')->all();
        $decisionSnapshots = empty($cycleIds)
            ? collect()
            : AdditionalBoxRequestDecision::query()
                ->with('request')
                ->whereIn('user_scrap_limit_cycle_id', $cycleIds)
                ->get()
                ->keyBy('user_scrap_limit_cycle_id');

        $rows = $cycles->map(function (UserScrapLimitCycle $cycle) use ($decisionSnapshots): array {
            $manualReset = $cycle->reset_at
                && $cycle->reset_by
                && (int) $cycle->reset_by !== (int) $cycle->user_id
                && $cycle->resetter?->role === 'omd';
            $snapshot = $decisionSnapshots->get($cycle->id);

            $decision = match ($cycle->decision) {
                'yes' => 'Order',
                'no' => 'Tidak',
                default => $manualReset ? 'Reset oleh OMD' : 'Belum dipilih',
            };
            $reason = $cycle->decision === 'no'
                ? (string) ($cycle->decision_reason ?? '')
                : (($manualReset && !$cycle->decision) ? (string) ($cycle->reset_reason ?? '') : '');
            $model = trim((string) ($cycle->product?->masterModel?->model ?? ''));
            $product = trim((string) ($cycle->product?->name ?? ''));

            return [
                'order_number' => (string) ($snapshot?->request?->document_number ?? '—'),
                'date' => $cycle->reached_at?->format('d-m-Y H:i') ?? '—',
                'model_product' => trim($model . ' – ' . $product, ' –'),
                'scrap_limit' => (string) ($cycle->reached_qty ?? 0) . ' / ' . (string) $cycle->limit_qty,
                'decision' => $decision,
                'reason' => $reason,
            ];
        })->values()->all();

        $title = 'History Scrap OMD';
        $filename = 'history-scrap-omd-' . now()->format('Ymd-His');

        return $format === 'pdf'
            ? $exportService->pdf($title, $filename, $rows)
            : $exportService->excel($title, $filename, $rows);
    }

    private function validatedFilters(Request $request): array
    {
        return $request->validate([
            'start_date' => ['nullable', 'date_format:Y-m-d'],
            'end_date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            'line_id' => ['nullable', 'integer', 'exists:lines,id'],
            'search' => ['nullable', 'string', 'max:150'],
            'per_page' => ['nullable', Rule::in([10, 25, 50, 100])],
        ]);
    }

    private function cyclesQuery(array $filters)
    {
        $search = trim((string) ($filters['search'] ?? ''));

        return UserScrapLimitCycle::query()
            ->whereNotNull('reached_at')
            ->with(['user', 'product.masterModel.line.plant', 'line', 'resetter'])
            ->when(!empty($filters['start_date']), fn ($query) => $query->whereDate('reached_at', '>=', $filters['start_date']))
            ->when(!empty($filters['end_date']), fn ($query) => $query->whereDate('reached_at', '<=', $filters['end_date']))
            ->when(!empty($filters['line_id']), fn ($query) => $query->where('line_id', $filters['line_id']))
            ->when($search !== '', function ($query) use ($search) {
                $keyword = '%' . $search . '%';
                $query->where(function ($matching) use ($keyword) {
                    $matching->whereHas('user', fn ($user) => $user->where('name', 'like', $keyword))
                        ->orWhereHas('line', fn ($line) => $line->where('name', 'like', $keyword))
                        ->orWhereHas('product', fn ($product) => $product->where('name', 'like', $keyword))
                        ->orWhereHas('product.masterModel', fn ($model) => $model->where('model', 'like', $keyword))
                        ->orWhereHas('product.masterModel.line.plant', fn ($plant) => $plant->where('name', 'like', $keyword));
                });
            })
            ->orderByDesc('reached_at')
            ->orderByDesc('id');
    }

    public function reset(Request $request, UserScrapLimitCycle $cycle, UserScrapLimitService $service)
    {
        $data = $request->validate(['reason' => ['required', 'string', 'min:5', 'max:1000']]);
        $service->reset($cycle, $request->user(), trim($data['reason']));

        return back()->with('success', 'Scrap Limit direset ke default master. Riwayat sebelumnya tetap disimpan.');
    }
}
