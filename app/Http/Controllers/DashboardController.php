<?php

namespace App\Http\Controllers;

use App\Models\FiscalYearTarget;
use App\Models\Line;
use App\Models\MonitoringAbnormality;
use App\Models\ProductScrapLimit;
use App\Models\RepairOrder;
use App\Models\RepairOrderItem;
use App\Models\RepairMonthlySnapshot;
use App\Models\UserScrapLimitCycle;
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
        // Fiscal Year default mengikuti periode aktif April–Maret, tetapi tetap bisa dipilih melalui filter Tahun.
        $today = now();
        $activeFiscalYear = (int) ($today->month >= 4 ? $today->year : $today->year - 1);
        $year = $request->filled('year')
            ? max(2020, min(2100, (int) $request->input('year')))
            : $activeFiscalYear;
        $month = (int) $today->month;
        $lineId = $request->filled('line_id') ? (int) $request->input('line_id') : null;

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
        $fiscalMonths = [];
        for ($offset = 0; $offset < 12; $offset++) {
            $date = Carbon::create($year, 4, 1)->addMonths($offset);
            $calendarYear = (int) $date->year;
            $calendarMonth = (int) $date->month;
            $isFuture = $date->startOfMonth()->gt(now()->startOfMonth());
            $metrics = $isFuture
                ? ['order' => 0, 'ok' => 0, 'scrap' => 0]
                : $this->metricsForMonth($calendarYear, $calendarMonth, $lineId);
            $monthlyMetrics->push([
                'month' => $calendarMonth,
                'year' => $calendarYear,
                'key' => $calendarYear . '-' . str_pad((string) $calendarMonth, 2, '0', STR_PAD_LEFT),
                'label' => $date->locale('id')->translatedFormat('M'),
                ...$metrics,
            ]);
            $fiscalMonths[] = ['year' => $calendarYear, 'month' => $calendarMonth];
        }

        $selectedMetrics = $monthlyMetrics->first(fn ($row) => (int) $row['month'] === $month && (int) $row['year'] === (int) $today->year) ?? [
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

        $globalMetrics = $this->metricsForMonth((int) $today->year, $month, null);
        $globalOrder = (int) $globalMetrics['order'];
        $targetExceeded = $target > 0 && $globalOrder > $target;
        $targetDifference = $targetExceeded
            ? $globalOrder - $target
            : 0;

        $globalScopeKey = FiscalYearTarget::scopeKey(null);

        $abnormalityQuery = MonitoringAbnormality::query()
            ->with(['creator', 'updater'])
            ->where('metric', MonitoringAbnormality::METRIC_TARGET_FY)
            ->where('scope_key', $globalScopeKey)
            ->where(function ($query) use ($year) {
                $query->where(function ($q) use ($year) {
                    $q->where('year', $year)->whereBetween('month', [4, 12]);
                })->orWhere(function ($q) use ($year) {
                    $q->where('year', $year + 1)->whereBetween('month', [1, 3]);
                });
            });
        $abnormalityRows = $abnormalityQuery->get()->sortBy(fn ($row) => (((int) $row->month) >= 4 ? (int) $row->month - 4 : (int) $row->month + 8));
        $abnormalitiesByMonth = $abnormalityRows->keyBy(fn ($row) => $row->year . '-' . str_pad((string) $row->month, 2, '0', STR_PAD_LEFT));
        $abnormality = $abnormalitiesByMonth->get($today->year . '-' . str_pad((string) $month, 2, '0', STR_PAD_LEFT));

        $periodEnd = Carbon::create((int) $today->year, $month, 1)->endOfMonth();

        if ($month === now()->month) {
            $periodEnd = now();
        }

        $productScrapAlerts = $this->productScrapAlerts(
            (int) $today->year,
            $month,
            $lineId,
            $periodEnd->toDateTimeString()
        );

        $orderSeries = $monthlyMetrics->pluck('order')->map(fn ($value) => (int) $value)->values();
        // Target FY bersifat global meskipun grafik sedang difilter menurut Line.
        // Snapshot historis dari seeder ikut dibaca oleh metricsForMonth(..., null).
        $globalOrderSeries = $lineId === null
            ? $orderSeries
            : $monthlyMetrics->map(function ($row) {
                $date = Carbon::create((int) $row['year'], (int) $row['month'], 1);
                return $date->gt(now()->startOfMonth())
                    ? 0
                    : (int) $this->metricsForMonth((int) $row['year'], (int) $row['month'], null)['order'];
            })->values();
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

        // Target FY adalah reference line, bukan batas maksimum chart.
        // Beri headroom agar batang yang melebihi target tetap terlihat menembus
        // garis Target FY dan label angka di atas batang tidak terpotong.
        $chartMax = $this->roundChartMax((int) ceil($maxMetric * 1.15));

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
            'abnormalitiesByMonth',
            'productScrapAlerts',
            'months',
            'fiscalMonths',
            'orderSeries',
            'globalOrderSeries',
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

        $year = (int) $data['year']; // Tahun kalender untuk record abnormality
        $month = (int) $data['month'];
        $fiscalYear = $month >= 4 ? $year : $year - 1;
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

        $redirectParams = array_filter([
            'year' => $fiscalYear,
            'line_id' => $returnLineId,
        ], fn ($value) => $value !== null && $value !== '');

        // Data bulan yang sudah tercatat boleh diedit kapan pun, tanpa
        // bergantung pada perubahan Target FY atau total order sesudahnya.
        if ($existing) {
            $existing->update([
                'reason' => trim($data['reason']),
                'updated_by' => $request->user()->id,
            ]);

            return redirect()
                ->route('dashboard', $redirectParams)
                ->with('success', 'Alasan abnormality berhasil diperbarui.');
        }

        $metrics = $this->metricsForMonth($year, $month, null);
        $targetRecord = $this->resolveTarget($fiscalYear);
        $target = (int) ($targetRecord?->target_qty ?? 0);

        if ($target <= 0 || $metrics['order'] <= $target) {
            return redirect()
                ->route('dashboard', $redirectParams)
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
            ->route('dashboard', $redirectParams)
            ->with('success', 'Alasan abnormality bulan ini berhasil disimpan.');
    }

    private function metricsForMonth(int $year, int $month, ?int $lineId): array
    {
        /*
         * Historical snapshot April–Agustus 2026 hanya tersedia dalam agregat
         * global. Gunakan persis nilai sumber ketika Dashboard tidak difilter
         * per Line. Snapshot tidak dibuat menjadi Repair Order fiktif.
         */
        if ($lineId === null) {
            $snapshot = RepairMonthlySnapshot::query()
                ->where('year', $year)
                ->where('month', $month)
                ->first();

            if ($snapshot) {
                return [
                    'order' => (int) $snapshot->order_qty,
                    'ok' => (int) $snapshot->ok_qty,
                    'scrap' => (int) $snapshot->scrap_qty,
                ];
            }
        }

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

        $resetProducts = UserScrapLimitCycle::query()
            ->whereNotNull('reset_at')
            ->pluck('product_id')
            ->filter()
            ->unique();

        return $items
            ->groupBy('product_id')
            ->map(function (Collection $productItems, $productId) use ($limits, $resetProducts) {
                if ($resetProducts->contains((int) $productId)) {
                    return null;
                }

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

        /*
         * Hindari pembulatan 1/2/5 yang dapat meloncat terlalu jauh
         * (contoh 1.056 menjadi 2.000). Step yang lebih rapat menjaga
         * perbedaan Actual vs Target tetap terbaca jelas pada grafik.
         */
        $step = match (true) {
            $value <= 100 => 10,
            $value <= 500 => 25,
            $value <= 1000 => 50,
            $value <= 5000 => 100,
            $value <= 10000 => 250,
            default => 500,
        };

        return (int) (ceil($value / $step) * $step);
    }
}
