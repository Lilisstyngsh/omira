<?php

namespace App\Http\Controllers;

use App\Models\FiscalYearTarget;
use App\Models\Line;
use App\Models\ModelScrapLimit;
use App\Models\MonitoringAbnormality;
use App\Models\RepairOrder;
use App\Models\RepairOrderItem;
use App\Models\RepairResult;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    private const MONITORED_STATUSES = [
        'completed',
        'revision_requested',
        'confirmed',
    ];

    public function index(Request $request)
    {
        $month = $request->integer('month') ?: now()->month;
        $year = $request->integer('year') ?: now()->year;
        $lineId = $request->filled('line_id')
            ? (int) $request->input('line_id')
            : null;

        if ($month < 1 || $month > 12) {
            $month = now()->month;
        }

        if ($year < 2020 || $year > 2100) {
            $year = now()->year;
        }

        $lines = Line::query()
            ->where('is_active', true)
            ->with('plant')
            ->orderBy('plant_id')
            ->orderBy('name')
            ->get();

        if ($lineId && ! $lines->contains('id', $lineId)) {
            $lineId = null;
        }

        $selectedLine = $lineId
            ? $lines->firstWhere('id', $lineId)
            : null;

        $monthlyMetrics = collect();

        for ($calendarMonth = 1; $calendarMonth <= $month; $calendarMonth++) {
            $metrics = $this->metricsForMonth(
                $year,
                $calendarMonth,
                $lineId
            );

            $monthlyMetrics->push([
                'month' => $calendarMonth,
                'label' => Carbon::create($year, $calendarMonth, 1)
                    ->locale('id')
                    ->translatedFormat('M'),
                ...$metrics,
            ]);
        }

        $selectedMetrics = $monthlyMetrics->last() ?? [
            'order' => 0,
            'ok' => 0,
            'scrap' => 0,
        ];

        $totalOrders = (int) $selectedMetrics['order'];
        $finishedOrders = (int) $selectedMetrics['ok'];
        $scrap = (int) $selectedMetrics['scrap'];

        $targetRecord = $this->resolveTarget($year, $lineId);
        $target = (int) ($targetRecord?->target_qty ?? 0);
        $targetScopeLabel = $targetRecord
            ? ($targetRecord->line_id ? 'Target Line' : 'Target Global')
            : 'Target belum diatur';

        $targetExceeded = $target > 0 && $totalOrders > $target;
        $targetDifference = $targetExceeded
            ? $totalOrders - $target
            : 0;

        $scopeKey = FiscalYearTarget::scopeKey($lineId);

        $abnormality = MonitoringAbnormality::query()
            ->with(['creator', 'updater'])
            ->where('metric', MonitoringAbnormality::METRIC_TARGET_FY)
            ->where('year', $year)
            ->where('month', $month)
            ->where('scope_key', $scopeKey)
            ->first();

        $periodEnd = Carbon::create($year, $month, 1)->endOfMonth();

        if ($year === now()->year && $month === now()->month) {
            $periodEnd = now();
        }

        $modelScrapAlerts = $this->modelScrapAlerts(
            $year,
            $month,
            $lineId,
            $periodEnd->toDateString()
        );

        $orderSeries = $monthlyMetrics->pluck('order')->map(fn ($value) => (int) $value)->values();
        $finishSeries = $monthlyMetrics->pluck('ok')->map(fn ($value) => (int) $value)->values();
        $scrapSeries = $monthlyMetrics->pluck('scrap')->map(fn ($value) => (int) $value)->values();
        $months = $monthlyMetrics->pluck('label')->values();
        $targetSeries = $monthlyMetrics->map(fn () => $target)->values();

        $maxMetric = max(
            1,
            (int) $orderSeries->max(),
            (int) $finishSeries->max(),
            (int) $scrapSeries->max(),
            $target
        );

        $chartMax = $this->roundChartMax($maxMetric);

        return view('omd.dashboard.index', compact(
            'month',
            'year',
            'lineId',
            'lines',
            'selectedLine',
            'totalOrders',
            'finishedOrders',
            'scrap',
            'target',
            'targetRecord',
            'targetScopeLabel',
            'targetExceeded',
            'targetDifference',
            'abnormality',
            'modelScrapAlerts',
            'months',
            'orderSeries',
            'finishSeries',
            'scrapSeries',
            'targetSeries',
            'chartMax'
        ));
    }

    public function storeAbnormality(Request $request)
    {
        $data = $request->validate([
            'year' => ['required', 'integer', 'min:2020', 'max:2100'],
            'month' => ['required', 'integer', 'between:1,12'],
            'line_id' => [
                'nullable',
                'integer',
                Rule::exists('lines', 'id')->where(
                    fn ($query) => $query->where('is_active', true)
                ),
            ],
            'reason' => ['required', 'string', 'min:5', 'max:2000'],
        ]);

        $year = (int) $data['year'];
        $month = (int) $data['month'];
        $lineId = isset($data['line_id'])
            ? (int) $data['line_id']
            : null;

        $metrics = $this->metricsForMonth($year, $month, $lineId);
        $targetRecord = $this->resolveTarget($year, $lineId);
        $target = (int) ($targetRecord?->target_qty ?? 0);

        if ($target <= 0 || $metrics['order'] <= $target) {
            return back()->withErrors([
                'reason' => 'Alasan abnormality hanya dapat disimpan ketika Order melewati Target FY.',
            ]);
        }

        $scopeKey = FiscalYearTarget::scopeKey($lineId);
        $userId = $request->user()->id;

        MonitoringAbnormality::query()->updateOrCreate(
            [
                'metric' => MonitoringAbnormality::METRIC_TARGET_FY,
                'year' => $year,
                'month' => $month,
                'scope_key' => $scopeKey,
            ],
            [
                'line_id' => $lineId,
                'fiscal_year_target_id' => $targetRecord?->id,
                'actual_qty' => (int) $metrics['order'],
                'threshold_qty' => $target,
                'reason' => trim($data['reason']),
                'created_by' => MonitoringAbnormality::query()
                    ->where('metric', MonitoringAbnormality::METRIC_TARGET_FY)
                    ->where('year', $year)
                    ->where('month', $month)
                    ->where('scope_key', $scopeKey)
                    ->value('created_by') ?: $userId,
                'updated_by' => $userId,
            ]
        );

        return redirect()
            ->route('dashboard', array_filter([
                'month' => $month,
                'year' => $year,
                'line_id' => $lineId,
            ], fn ($value) => $value !== null && $value !== ''))
            ->with('success', 'Alasan abnormality berhasil disimpan.');
    }

    private function metricsForMonth(int $year, int $month, ?int $lineId): array
    {
        $itemQuery = RepairOrderItem::query()
            ->whereHas('repairOrder', function ($query) use ($year, $month, $lineId) {
                $this->applyCompletedOrderFilter($query, $year, $month, $lineId);
            });

        $order = (int) (clone $itemQuery)->sum('before_qty');
        $ok = (int) (clone $itemQuery)->sum('after_qty');
        $scrap = max($order - $ok, 0);

        /*
         * Compatibility untuk format order lama yang belum mempunyai
         * repair_order_items.
         */
        $legacyOrders = RepairOrder::query()
            ->whereDoesntHave('items')
            ->whereIn('status', self::MONITORED_STATUSES)
            ->whereYear('repair_completed_at', $year)
            ->whereMonth('repair_completed_at', $month)
            ->when($lineId, fn ($query) => $query->where('line_id', $lineId));

        $legacyOrderQty = (int) (clone $legacyOrders)->sum('quantity');

        $legacyResults = RepairResult::query()
            ->whereHas('order', function ($query) use ($year, $month, $lineId) {
                $query
                    ->whereDoesntHave('items')
                    ->whereIn('status', self::MONITORED_STATUSES)
                    ->whereYear('repair_completed_at', $year)
                    ->whereMonth('repair_completed_at', $month)
                    ->when($lineId, fn ($q) => $q->where('line_id', $lineId));
            });

        return [
            'order' => $order + $legacyOrderQty,
            'ok' => $ok + (int) (clone $legacyResults)->sum('ok_qty'),
            'scrap' => $scrap + (int) (clone $legacyResults)->sum('scrap_qty'),
        ];
    }

    private function applyCompletedOrderFilter(
        $query,
        int $year,
        int $month,
        ?int $lineId
    ): void {
        $query
            ->whereIn('status', self::MONITORED_STATUSES)
            ->whereNotNull('repair_completed_at')
            ->whereYear('repair_completed_at', $year)
            ->whereMonth('repair_completed_at', $month)
            ->when($lineId, fn ($q) => $q->where('line_id', $lineId));
    }

    private function resolveTarget(int $year, ?int $lineId): ?FiscalYearTarget
    {
        if ($lineId) {
            $lineTarget = FiscalYearTarget::query()
                ->where('year', $year)
                ->where('scope_key', FiscalYearTarget::scopeKey($lineId))
                ->first();

            if ($lineTarget) {
                return $lineTarget;
            }
        }

        return FiscalYearTarget::query()
            ->where('year', $year)
            ->where('scope_key', FiscalYearTarget::scopeKey(null))
            ->first();
    }

    private function modelScrapAlerts(
        int $year,
        int $month,
        ?int $lineId,
        string $effectiveDate
    ): Collection {
        $items = RepairOrderItem::query()
            ->with(['masterModel.line'])
            ->whereNotNull('master_model_id')
            ->whereHas('repairOrder', function ($query) use ($year, $month, $lineId) {
                $this->applyCompletedOrderFilter($query, $year, $month, $lineId);
            })
            ->get();

        if ($items->isEmpty()) {
            return collect();
        }

        $modelIds = $items
            ->pluck('master_model_id')
            ->filter()
            ->unique()
            ->values();

        $limits = ModelScrapLimit::query()
            ->whereIn('master_model_id', $modelIds)
            ->activeOn($effectiveDate)
            ->orderByDesc('effective_from')
            ->get()
            ->groupBy('master_model_id')
            ->map(fn ($rows) => $rows->first());

        return $items
            ->groupBy('master_model_id')
            ->map(function (Collection $modelItems, $masterModelId) use ($limits) {
                $limit = $limits->get((int) $masterModelId);

                if (! $limit) {
                    return null;
                }

                $orderQty = (int) $modelItems->sum('before_qty');
                $okQty = (int) $modelItems->sum('after_qty');
                $scrapQty = max($orderQty - $okQty, 0);
                $limitQty = (int) $limit->limit_qty;

                if ($scrapQty < $limitQty) {
                    return null;
                }

                $model = $modelItems->first()?->masterModel;

                return [
                    'master_model_id' => (int) $masterModelId,
                    'model' => $model?->model ?? '-',
                    'line' => $model?->line?->name ?? '-',
                    'scrap_qty' => $scrapQty,
                    'limit_qty' => $limitQty,
                    'difference' => max($scrapQty - $limitQty, 0),
                    'status' => $scrapQty > $limitQty ? 'exceeded' : 'warning',
                ];
            })
            ->filter()
            ->sortByDesc('scrap_qty')
            ->values();
    }

    private function roundChartMax(int $value): int
    {
        if ($value <= 10) {
            return 10;
        }

        $magnitude = 10 ** floor(log10($value));
        $normalized = $value / $magnitude;

        $rounded = match (true) {
            $normalized <= 1 => 1,
            $normalized <= 2 => 2,
            $normalized <= 5 => 5,
            default => 10,
        };

        return (int) ($rounded * $magnitude);
    }
}
