<?php

namespace App\Http\Controllers;

use App\Models\FiscalYearTarget;
use App\Models\Line;
use App\Models\MonitoringAbnormality;
use App\Models\ProductScrapLimit;
use App\Models\RepairOrder;
use App\Models\RepairOrderItem;
use App\Models\RepairResult;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    private const MONITORED_STATUS = 'confirmed';

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

        /*
         * Target FY hanya 1 nilai global per tahun untuk seluruh Plant & Line.
         * Filter Line tetap berlaku untuk KPI/grafik, tetapi abnormality selalu
         * membandingkan total Order global bulanan dengan Target FY global.
         */
        $targetRecord = $this->resolveTarget($year);
        $target = (int) ($targetRecord?->target_qty ?? 0);
        $targetScopeLabel = $targetRecord
            ? 'Seluruh Plant & Line'
            : 'Target belum diatur';

        $globalMetrics = $this->metricsForMonth($year, $month, null);
        $globalOrder = (int) $globalMetrics['order'];
        $targetExceeded = $target > 0 && $globalOrder > $target;
        $targetDifference = $targetExceeded
            ? $globalOrder - $target
            : 0;

        $globalScopeKey = FiscalYearTarget::scopeKey(null);

        $abnormality = MonitoringAbnormality::query()
            ->with(['creator', 'updater'])
            ->where('metric', MonitoringAbnormality::METRIC_TARGET_FY)
            ->where('year', $year)
            ->where('month', $month)
            ->where('scope_key', $globalScopeKey)
            ->first();

        $periodEnd = Carbon::create($year, $month, 1)->endOfMonth();

        if ($year === now()->year && $month === now()->month) {
            $periodEnd = now();
        }

        $productScrapAlerts = $this->productScrapAlerts(
            $year,
            $month,
            $lineId,
            $periodEnd->toDateTimeString()
        );

        $orderSeries = $monthlyMetrics->pluck('order')->map(fn ($value) => (int) $value)->values();
        $finishSeries = $monthlyMetrics->pluck('ok')->map(fn ($value) => (int) $value)->values();
        $scrapSeries = $monthlyMetrics->pluck('scrap')->map(fn ($value) => (int) $value)->values();
        $months = $monthlyMetrics->pluck('label')->values();

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
            'globalOrder',
            'targetExceeded',
            'targetDifference',
            'abnormality',
            'productScrapAlerts',
            'months',
            'orderSeries',
            'finishSeries',
            'scrapSeries',
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
        $returnLineId = isset($data['line_id'])
            ? (int) $data['line_id']
            : null;

        $scopeKey = FiscalYearTarget::scopeKey(null);

        $existing = MonitoringAbnormality::query()
            ->where('metric', MonitoringAbnormality::METRIC_TARGET_FY)
            ->where('year', $year)
            ->where('month', $month)
            ->where('scope_key', $scopeKey)
            ->first();

        if ($existing) {
            return redirect()
                ->route('dashboard', array_filter([
                    'month' => $month,
                    'year' => $year,
                    'line_id' => $returnLineId,
                ], fn ($value) => $value !== null && $value !== ''))
                ->withErrors([
                    'reason' => 'Alasan abnormality untuk bulan ini sudah pernah disimpan dan tidak dapat diinput ulang.',
                ]);
        }

        $metrics = $this->metricsForMonth($year, $month, null);
        $targetRecord = $this->resolveTarget($year);
        $target = (int) ($targetRecord?->target_qty ?? 0);

        if ($target <= 0 || $metrics['order'] <= $target) {
            return redirect()
                ->route('dashboard', array_filter([
                    'month' => $month,
                    'year' => $year,
                    'line_id' => $returnLineId,
                ], fn ($value) => $value !== null && $value !== ''))
                ->withErrors([
                    'reason' => 'Alasan abnormality hanya dapat disimpan ketika total Order seluruh Line melewati Target FY.',
                ]);
        }

        MonitoringAbnormality::create([
            'metric' => MonitoringAbnormality::METRIC_TARGET_FY,
            'year' => $year,
            'month' => $month,
            'scope_key' => $scopeKey,
            'line_id' => null,
            'fiscal_year_target_id' => $targetRecord?->id,
            'actual_qty' => (int) $metrics['order'],
            'threshold_qty' => $target,
            'reason' => trim($data['reason']),
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ]);

        return redirect()
            ->route('dashboard', array_filter([
                'month' => $month,
                'year' => $year,
                'line_id' => $returnLineId,
            ], fn ($value) => $value !== null && $value !== ''))
            ->with('success', 'Alasan abnormality bulan ini berhasil disimpan.');
    }

    private function metricsForMonth(int $year, int $month, ?int $lineId): array
    {
        $itemQuery = RepairOrderItem::query()
            ->whereHas('repairOrder', function ($query) use ($year, $month, $lineId) {
                $this->applyConfirmedOrderFilter($query, $year, $month, $lineId);
            });

        /*
         * Dashboard baru membaca hasil final OMD setelah barang dikonfirmasi
         * sesuai oleh User:
         * Order = P + H + C + S (after_qty)
         * Scrap = S (after_qty)
         * OK    = Order - Scrap
         */
        $order = (int) (clone $itemQuery)->sum('after_qty');
        $scrap = (int) (clone $itemQuery)
            ->whereHas('ngType', fn ($query) => $query->where('code', 'S'))
            ->sum('after_qty');
        $ok = max($order - $scrap, 0);

        /*
         * Compatibility untuk order lama yang belum mempunyai repair_order_items.
         * Periode tetap memakai tanggal User melakukan konfirmasi penerimaan.
         */
        $legacyResults = RepairResult::query()
            ->whereHas('order', function ($query) use ($year, $month, $lineId) {
                $query->whereDoesntHave('items');
                $this->applyConfirmedOrderFilter($query, $year, $month, $lineId);
            });

        $legacyOk = (int) (clone $legacyResults)->sum('ok_qty');
        $legacyScrap = (int) (clone $legacyResults)->sum('scrap_qty');
        $legacyNg = (int) (clone $legacyResults)->sum('ng_qty');
        $legacyOrder = $legacyOk + $legacyScrap + $legacyNg;

        return [
            'order' => $order + $legacyOrder,
            'ok' => $ok + max($legacyOrder - $legacyScrap, 0),
            'scrap' => $scrap + $legacyScrap,
        ];
    }

    private function applyConfirmedOrderFilter(
        $query,
        int $year,
        int $month,
        ?int $lineId
    ): void {
        $query
            ->where('status', self::MONITORED_STATUS)
            ->whereHas('confirmation', function ($confirmationQuery) use ($year, $month) {
                $confirmationQuery
                    ->whereNotNull('confirmed_at')
                    ->whereYear('confirmed_at', $year)
                    ->whereMonth('confirmed_at', $month);
            })
            ->when($lineId, fn ($q) => $q->where('line_id', $lineId));
    }

    private function resolveTarget(int $year): ?FiscalYearTarget
    {
        return FiscalYearTarget::query()
            ->where('year', $year)
            ->where('scope_key', FiscalYearTarget::scopeKey(null))
            ->first();
    }

    private function productScrapAlerts(
        int $year,
        int $month,
        ?int $lineId,
        string $effectiveDateTime
    ): Collection {
        $items = RepairOrderItem::query()
            ->with(['product.masterModel.line', 'ngType'])
            ->whereNotNull('product_id')
            ->whereHas('ngType', fn ($query) => $query->where('code', 'S'))
            ->whereHas('repairOrder', function ($query) use ($year, $month, $lineId) {
                $this->applyConfirmedOrderFilter($query, $year, $month, $lineId);
            })
            ->get();

        if ($items->isEmpty()) {
            return collect();
        }

        $productIds = $items
            ->pluck('product_id')
            ->filter()
            ->unique()
            ->values();

        $limits = ProductScrapLimit::query()
            ->whereIn('product_id', $productIds)
            ->activeAt($effectiveDateTime)
            ->orderByDesc('effective_from')
            ->orderByDesc('id')
            ->get()
            ->groupBy('product_id')
            ->map(fn ($rows) => $rows->first());

        return $items
            ->groupBy('product_id')
            ->map(function (Collection $productItems, $productId) use ($limits) {
                $limit = $limits->get((int) $productId);

                if (! $limit) {
                    return null;
                }

                $scrapQty = (int) $productItems->sum('after_qty');
                $limitQty = (int) $limit->limit_qty;

                if ($scrapQty < $limitQty) {
                    return null;
                }

                $product = $productItems->first()?->product;
                $model = $product?->masterModel;

                return [
                    'product_id' => (int) $productId,
                    'model' => $model?->model ?? '-',
                    'product' => $product?->name ?? '-',
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
