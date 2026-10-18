<?php

namespace App\Http\Controllers;

use App\Models\FiscalYearTarget;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class RecapController extends Controller
{
    public function index(Request $request)
    {
        [$startDate, $endDate] = $this->resolvePeriod($request);
        $selectedLineIds = $this->resolveLineIds($request);
        $lines = $this->availableLines();
        $perPage = (int) $request->input('per_page', $request->input('perPage', 25));
        if (! in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 25;
        }

        $periodType = $request->input('period_type', 'monthly');
        $annualSummary = $periodType === 'yearly'
            ? $this->annualSummary((int) $request->input('report_year', $startDate->year), $selectedLineIds)
            : [];

        $rows = $this->tableQuery($startDate, $endDate, $selectedLineIds)
            ->orderByDesc('tanggal')
            ->orderBy('line')
            ->orderBy('order_number')
            ->orderBy('model')
            ->orderBy('produk')
            ->paginate($perPage)
            ->withQueryString();

        return view('omd.recap.index', compact(
            'startDate',
            'endDate',
            'rows',
            'lines',
            'selectedLineIds',
            'perPage',
            'annualSummary'
        ));
    }

    /** The dashboard controller is the single source of monitoring metrics. */
    private function dashboardChartData(Carbon $endDate, array $lineIds): array
    {
        $dashboard = app(DashboardController::class);
        $params = ['year' => $endDate->year, 'month' => $endDate->month];
        $selectedLines = array_values(array_unique(array_map('intval', $lineIds)));
        $base = $dashboard->index(Request::create('/dashboard', 'GET', $params))->getData();

        // Dashboard supports one Line. For multiple Line selections, combine
        // the respective dashboard series. Do not use global snapshots here.
        if (count($selectedLines) > 1) {
            $order = array_fill(0, $endDate->month, 0);
            $ok = $order;
            $scrap = $order;
            foreach ($selectedLines as $id) {
                $data = $dashboard->index(Request::create('/dashboard', 'GET', $params + ['line_id' => $id]))->getData();
                if ((int) ($data['lineId'] ?? 0) !== $id) {
                    throw \Illuminate\Validation\ValidationException::withMessages(['line_ids' => 'Line tidak aktif atau tidak valid.']);
                }
                foreach ($order as $i => $_) {
                    $order[$i] += (int) ($data['orderSeries'][$i] ?? 0);
                    $ok[$i] += (int) ($data['finishSeries'][$i] ?? 0);
                    $scrap[$i] += (int) ($data['scrapSeries'][$i] ?? 0);
                }
            }
            $base['orderSeries'] = collect($order);
            $base['finishSeries'] = collect($ok);
            $base['scrapSeries'] = collect($scrap);
            $max = max(1, max($order), max($ok), max($scrap), (int) $base['target']);
            $base['chartMax'] = $this->chartScaleMaximum((int) ceil($max * 1.15));
        } elseif (count($selectedLines) === 1) {
            $base = $dashboard->index(Request::create('/dashboard', 'GET', $params + ['line_id' => $selectedLines[0]]))->getData();
            if ((int) ($base['lineId'] ?? 0) !== $selectedLines[0]) {
                throw \Illuminate\Validation\ValidationException::withMessages(['line_ids' => 'Line tidak aktif atau tidak valid.']);
            }
        }

        return $base;
    }

    private function chartScaleMaximum(int $value): int
    {
        if ($value <= 10) return 10;
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

    private function chartRowsFromDashboard(array $data): Collection
    {
        $rows = collect();
        foreach ($data['months'] as $index => $month) {
            $rows->push([
                'label' => (string) $month,
                'order' => (int) ($data['orderSeries'][$index] ?? 0),
                'ok' => (int) ($data['finishSeries'][$index] ?? 0),
                'scrap' => (int) ($data['scrapSeries'][$index] ?? 0),
                'target' => (int) $data['target'],
            ]);
        }
        return $rows;
    }

    public function chartPreview(Request $request)
    {
        [, $end] = $this->resolvePeriod($request);
        $ids = $this->resolveLineIds($request);
        $data = $this->dashboardChartData($end, $ids);
        // Render the EXACT Dashboard Blade and capture its existing chart-card.
        return view('omd.dashboard.index', $data);
    }

    public function exportPdf(Request $request)
    {
        [$startDate, $endDate] = $this->resolvePeriod($request);
        $selectedLineIds = $this->resolveLineIds($request);
        $selectedLineNames = $this->selectedLineNames($selectedLineIds);

        // A separate matrix for each month prevents duplicate day numbers
        // when the selected reporting period spans multiple months.
        $monthlyReports = collect();
        $cursor = $startDate->copy()->startOfMonth();
        $lastMonth = $endDate->copy()->startOfMonth();

        while ($cursor->lte($lastMonth)) {
            $monthStart = $cursor->copy()->startOfMonth()->max($startDate->copy());
            $monthEnd = $cursor->copy()->endOfMonth()->min($endDate->copy());
            $matrixRows = $this->matrixData($monthStart, $monthEnd, $selectedLineIds);

            $plants = $matrixRows->groupBy('plant')->map(function (Collection $plantRows, $plantName) {
                $lines = $plantRows->groupBy('line')->map(function (Collection $lineRows, $lineName) {
                    $models = $lineRows->groupBy('model')->map(function (Collection $modelRows, $modelName) {
                        $products = $modelRows->map(function ($product) {
                            $total = ['order' => 0, 'ok' => 0, 'scrap' => 0];
                            foreach ($product['daily'] as $values) {
                                foreach ($total as $key => $_) {
                                    $total[$key] += (int) ($values[$key] ?? 0);
                                }
                            }
                            $product['totals'] = $total;
                            return $product;
                        })->values();
                        $totals = ['order' => 0, 'ok' => 0, 'scrap' => 0];
                        $daily = [];
                        foreach ($products as $product) {
                            foreach ($totals as $key => $_) {
                                $totals[$key] += $product['totals'][$key];
                            }
                            foreach ($product['daily'] as $day => $values) {
                                foreach ($totals as $key => $_) {
                                    $daily[$day][$key] = ($daily[$day][$key] ?? 0) + (int) ($values[$key] ?? 0);
                                }
                            }
                        }
                        return [
                            'name' => $modelName,
                            'products' => $products,
                            // Exactly three status rows per product; no model subtotal rows.
                            'rowspan' => $products->count() * 3,
                            'totals' => $totals,
                            'daily' => $daily,
                        ];
                    })->values();
                    return [
                        'name' => $lineName,
                        'models' => $models,
                        'rowspan' => $models->sum('rowspan'),
                    ];
                })->values();
                return [
                    'name' => $plantName,
                    'lines' => $lines,
                    'rowspan' => $lines->sum('rowspan'),
                ];
            })->values();

            $grandDaily = [];
            $grandTotals = ['order' => 0, 'ok' => 0, 'scrap' => 0];
            foreach ($matrixRows as $product) {
                foreach ($product['daily'] as $day => $values) {
                    foreach ($grandTotals as $key => $_) {
                        $amount = (int) ($values[$key] ?? 0);
                        $grandDaily[$day][$key] = ($grandDaily[$day][$key] ?? 0) + $amount;
                        $grandTotals[$key] += $amount;
                    }
                }
            }

            $monthlyReports->push([
                'label' => $cursor->copy()->locale('id')->translatedFormat('F Y'),
                'days' => range($monthStart->day, $monthEnd->day),
                'plants' => $plants,
                'grandDaily' => $grandDaily,
                'grandTotals' => $grandTotals,
            ]);
            $cursor->addMonth();
        }

        $filename = sprintf(
            'Rekap_Repair_Box_%s_sd_%s.pdf',
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d')
        );

        return Pdf::loadView('omd.recap.pdf', compact(
            'startDate',
            'endDate',
            'monthlyReports',
            'selectedLineNames'
        ))
            ->setPaper('a3', 'landscape')
            ->download($filename);
    }

    public function exportExcel(Request $request)
    {
        [$startDate, $endDate] = $this->resolvePeriod($request);
        $selectedLineIds = $this->resolveLineIds($request);
        $selectedLineNames = $this->selectedLineNames($selectedLineIds);
        $chartData = $this->dashboardChartData($endDate, $selectedLineIds);
        $chartRows = $this->chartRowsFromDashboard($chartData);
        $chartImage = $this->validateChartImage($request);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);
        $temporaryChartPath = null;

        $cursor = $startDate->copy()->startOfMonth();
        $lastMonth = $endDate->copy()->startOfMonth();
        $createdSheets = 0;

        while ($cursor->lte($lastMonth)) {
            $monthStart = $cursor->copy()->startOfMonth()->max($startDate->copy());
            $monthEnd = $cursor->copy()->endOfMonth()->min($endDate->copy());
            $matrixRows = $this->matrixData($monthStart, $monthEnd, $selectedLineIds);

            $sheetName = $this->safeSheetName($cursor->locale('id')->translatedFormat('M Y'));
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle($sheetName);
            $this->buildMonthlyMatrixSheet(
                $sheet,
                $matrixRows,
                $monthStart,
                $monthEnd,
                $selectedLineNames
            );
            $createdSheets++;

            if ($createdSheets === 1) {
                $monitoringSheet = $spreadsheet->createSheet();
                $monitoringSheet->setTitle('Monitoring');
                $temporaryChartPath = $this->buildMonitoringSheet(
                    $monitoringSheet,
                    $chartRows,
                    $chartImage,
                    $selectedLineNames,
                    $startDate,
                    $endDate
                );
            }

            $cursor->addMonth();
        }

        if ($createdSheets === 0) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('Rekap');
            $this->buildMonthlyMatrixSheet(
                $sheet,
                collect(),
                $startDate,
                $endDate,
                $selectedLineNames
            );
            $monitoringSheet = $spreadsheet->createSheet();
            $monitoringSheet->setTitle('Monitoring');
            $temporaryChartPath = $this->buildMonitoringSheet(
                $monitoringSheet,
                $chartRows,
                $chartImage,
                $selectedLineNames,
                $startDate,
                $endDate
            );
        }

        $spreadsheet->setActiveSheetIndex(0);

        $filename = sprintf(
            'Rekap_Repair_Box_%s_sd_%s.xlsx',
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d')
        );

        $tempPath = tempnam(sys_get_temp_dir(), 'omira_recap_');
        $xlsxPath = $tempPath . '.xlsx';
        @unlink($tempPath);

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        try {
            $writer->save($xlsxPath);
        } finally {
            if ($temporaryChartPath && is_file($temporaryChartPath)) @unlink($temporaryChartPath);
        }
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        return response()
            ->download($xlsxPath, $filename)
            ->deleteFileAfterSend(true);
    }

    private function resolvePeriod(Request $request): array
    {
        $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'period_type' => ['nullable', 'in:monthly,yearly,custom'],
            'report_year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'report_month' => ['nullable', 'integer', 'between:1,12'],
        ]);

        $periodType = $request->input('period_type');

        // Rentang tanggal menggunakan tanggal pilihan pengguna, bukan periode fiscal year.
        if ($periodType === 'custom') {
            $startDate = $request->filled('start_date')
                ? Carbon::parse($request->input('start_date'))->startOfDay()
                : now()->startOfMonth()->startOfDay();
            $endDate = $request->filled('end_date')
                ? Carbon::parse($request->input('end_date'))->endOfDay()
                : ($request->filled('start_date')
                    ? $startDate->copy()->endOfDay()
                    : now()->endOfDay());

            return [$startDate, $endDate];
        }

        if ($periodType && $request->filled('report_year')) {
            $year = (int) $request->input('report_year');
            if ($periodType === 'yearly') {
                return [Carbon::create($year, 4, 1)->startOfDay(), Carbon::create($year + 1, 3, 31)->endOfDay()];
            }
            if ($request->filled('report_month')) {
                $month = (int) $request->input('report_month');
                return [Carbon::create($year, $month, 1)->startOfMonth()->startOfDay(), Carbon::create($year, $month, 1)->endOfMonth()->endOfDay()];
            }
        }

        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : now()->startOfMonth()->startOfDay();
        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : now()->endOfDay();

        return [$startDate, $endDate];
    }

    /** Annual summary follows the fiscal year: April through March. */
    private function annualSummary(int $fiscalYear, array $lineIds = []): array
    {
        $start = Carbon::create($fiscalYear, 4, 1)->startOfDay();
        $end = Carbon::create($fiscalYear + 1, 3, 31)->endOfDay();

        $actualQuery = DB::table('repair_order_items as roi')
            ->join('repair_orders as ro', 'ro.id', '=', 'roi.repair_order_id')
            ->join('repair_confirmations as rc', 'rc.repair_order_id', '=', 'ro.id')
            ->join('ng_types as ng', 'ng.id', '=', 'roi.ng_type_id')
            ->where('roi.before_qty', '>', 0)
            ->where('ro.status', 'confirmed')
            ->whereNotNull('ro.verified_by')
            ->whereNotNull('ro.verified_at')
            ->whereBetween('rc.confirmed_at', [$start, $end]);

        $actualRows = $this->applyLineFilter($actualQuery, $lineIds)
            ->selectRaw('YEAR(rc.confirmed_at) as calendar_year')
            ->selectRaw('MONTH(rc.confirmed_at) as calendar_month')
            ->selectRaw('SUM(roi.after_qty) as order_qty')
            ->selectRaw("SUM(CASE WHEN UPPER(ng.code) = 'S' THEN roi.after_qty ELSE 0 END) as scrap_qty")
            ->groupByRaw('YEAR(rc.confirmed_at), MONTH(rc.confirmed_at)')
            ->get()
            ->keyBy(fn ($row) => sprintf('%04d-%02d', $row->calendar_year, $row->calendar_month));

        if (empty($lineIds)) {
            $target = (int) FiscalYearTarget::query()
                ->where('year', $fiscalYear)
                ->where('scope_key', FiscalYearTarget::scopeKey(null))
                ->value('target_qty');
        } else {
            $target = (int) FiscalYearTarget::query()
                ->where('year', $fiscalYear)
                ->whereIn('line_id', $lineIds)
                ->sum('target_qty');
        }

        $months = [];
        $cursor = $start->copy();
        while ($cursor->lte($end)) {
            $key = $cursor->format('Y-m');
            $actual = $actualRows->get($key);
            $order = $actual ? (int) $actual->order_qty : null;
            $scrap = $actual ? (int) $actual->scrap_qty : null;

            $months[] = [
                'label' => $cursor->locale('id')->translatedFormat('M'),
                'target' => $target,
                'order' => $order,
                'finish' => $actual ? max($order - $scrap, 0) : null,
                'scrap' => $scrap,
            ];
            $cursor->addMonth();
        }

        return $months;
    }

    private function resolveLineIds(Request $request): array
    {
        $request->validate([
            'line_ids' => ['nullable', 'array'],
            'line_ids.*' => ['integer', 'exists:lines,id'],
        ]);

        return collect($request->input('line_ids', []))
            ->map(fn($id) => (int) $id)
            ->filter(fn($id) => $id > 0)
            ->unique()
            ->values()
            ->all();
    }

    private function availableLines(): Collection
    {
        return DB::table('lines as l')
            ->join('plants as p', 'p.id', '=', 'l.plant_id')
            ->select('l.id', 'l.name', 'p.name as plant')
            ->orderBy('p.name')
            ->orderBy('l.name')
            ->get();
    }

    private function selectedLineNames(array $lineIds): Collection
    {
        if (empty($lineIds)) {
            return collect(['Semua Line']);
        }

        return DB::table('lines')
            ->whereIn('id', $lineIds)
            ->orderBy('name')
            ->pluck('name');
    }

    private function applyLineFilter(Builder $query, array $lineIds): Builder
    {
        if (! empty($lineIds)) {
            $query->whereIn('ro.line_id', $lineIds);
        }

        return $query;
    }

    /**
     * One row per order/product for the on-screen recap table.
     * Keep detailQuery() separate because PDF/Excel use its NG-type rows.
     */
    private function tableQuery(Carbon $startDate, Carbon $endDate, array $lineIds = []): Builder
    {
        $query = DB::table('repair_order_items as roi')
            ->join('repair_orders as ro', 'ro.id', '=', 'roi.repair_order_id')
            ->join('repair_confirmations as rc', 'rc.repair_order_id', '=', 'ro.id')
            ->join('lines as l', 'l.id', '=', 'ro.line_id')
            ->join('plants as pl', 'pl.id', '=', 'l.plant_id')
            ->leftJoin('master_models as mm', 'mm.id', '=', 'roi.master_model_id')
            ->leftJoin('products as p', 'p.id', '=', 'roi.product_id')
            ->leftJoin('master_models as pmm', 'pmm.id', '=', 'p.master_model_id')
            ->leftJoin('products as ap', 'ap.id', '=', 'roi.after_product_id')
            ->leftJoin('master_models as amm', 'amm.id', '=', 'ap.master_model_id')
            ->leftJoin('master_models as romm', 'romm.id', '=', 'ro.master_model_id')
            ->leftJoin('products as rop', 'rop.id', '=', 'ro.product_id')
            ->leftJoin('master_models as ropmm', 'ropmm.id', '=', 'rop.master_model_id')
            ->leftJoin('lines as al', 'al.id', '=', 'amm.line_id')
            ->leftJoin('plants as apl', 'apl.id', '=', 'al.plant_id')
            ->join('ng_types as ng', 'ng.id', '=', 'roi.ng_type_id')
            ->where('roi.before_qty', '>', 0)
            ->where('ro.status', 'confirmed')
            ->whereNotNull('ro.verified_by')
            ->whereNotNull('ro.verified_at')
            ->whereBetween('rc.confirmed_at', [$startDate, $endDate])
            ->selectRaw('DATE(rc.confirmed_at) as tanggal')
            ->addSelect('ro.order_number as order_number')
            ->selectRaw('COALESCE(apl.name, pl.name) as plant')
            ->selectRaw('COALESCE(al.name, l.name) as line')
            ->selectRaw('COALESCE(amm.model, mm.model, pmm.model, romm.model, ropmm.model) as model')
            ->selectRaw('COALESCE(ap.name, p.name, rop.name) as produk')
            ->selectRaw("COALESCE(SUM(CASE WHEN UPPER(ng.code) = 'P' THEN roi.after_qty ELSE 0 END), 0) as qty_p")
            ->selectRaw("COALESCE(SUM(CASE WHEN UPPER(ng.code) = 'H' THEN roi.after_qty ELSE 0 END), 0) as qty_h")
            ->selectRaw("COALESCE(SUM(CASE WHEN UPPER(ng.code) = 'C' THEN roi.after_qty ELSE 0 END), 0) as qty_c")
            ->selectRaw("COALESCE(SUM(CASE WHEN UPPER(ng.code) = 'S' THEN roi.after_qty ELSE 0 END), 0) as qty_s")
            ->selectRaw('COALESCE(SUM(roi.after_qty), 0) as total')
            ->groupByRaw('DATE(rc.confirmed_at), ro.order_number, COALESCE(apl.name, pl.name), COALESCE(al.name, l.name), COALESCE(amm.model, mm.model, pmm.model, romm.model, ropmm.model), COALESCE(ap.name, p.name, rop.name)');

        return $this->applyLineFilter($query, $lineIds);
    }

    private function detailQuery(Carbon $startDate, Carbon $endDate, array $lineIds = []): Builder
    {
        $query = DB::table('repair_order_items as roi')
            ->join('repair_orders as ro', 'ro.id', '=', 'roi.repair_order_id')
            ->join('repair_confirmations as rc', 'rc.repair_order_id', '=', 'ro.id')
            ->join('lines as l', 'l.id', '=', 'ro.line_id')
            ->join('plants as pl', 'pl.id', '=', 'l.plant_id')
            ->leftJoin('master_models as mm', 'mm.id', '=', 'roi.master_model_id')
            ->leftJoin('products as p', 'p.id', '=', 'roi.product_id')
            ->leftJoin('master_models as pmm', 'pmm.id', '=', 'p.master_model_id')
            ->leftJoin('products as ap', 'ap.id', '=', 'roi.after_product_id')
            ->leftJoin('master_models as amm', 'amm.id', '=', 'ap.master_model_id')
            ->leftJoin('master_models as romm', 'romm.id', '=', 'ro.master_model_id')
            ->leftJoin('products as rop', 'rop.id', '=', 'ro.product_id')
            ->leftJoin('master_models as ropmm', 'ropmm.id', '=', 'rop.master_model_id')
            ->leftJoin('lines as al', 'al.id', '=', 'amm.line_id')
            ->leftJoin('plants as apl', 'apl.id', '=', 'al.plant_id')
            ->join('ng_types as ng', 'ng.id', '=', 'roi.ng_type_id')
            // Hanya tampilkan jenis NG yang benar-benar dipesan user dan telah diverifikasi OMD.
            ->where('roi.before_qty', '>', 0)
            ->where('ro.status', 'confirmed')
            ->whereNotNull('ro.verified_by')
            ->whereNotNull('ro.verified_at')
            ->whereBetween('rc.confirmed_at', [$startDate, $endDate])
            ->selectRaw('COALESCE(apl.name, pl.name) as plant')
            ->selectRaw('COALESCE(al.name, l.name) as line')
            ->selectRaw('COALESCE(amm.model, mm.model, pmm.model, romm.model, ropmm.model) as model')
            ->selectRaw('COALESCE(ap.name, p.name, rop.name) as produk')
            ->addSelect('ng.code as jenis_ng')
            ->selectRaw('SUM(roi.after_qty) as total')
            ->selectRaw('DATE(rc.confirmed_at) as tanggal')
            ->addSelect('ro.order_number as order_number')
            ->groupByRaw('DATE(rc.confirmed_at), ro.order_number, COALESCE(apl.name, pl.name), COALESCE(al.name, l.name), COALESCE(amm.model, mm.model, pmm.model, romm.model, ropmm.model), COALESCE(ap.name, p.name, rop.name), ng.code');

        return $this->applyLineFilter($query, $lineIds);
    }

    private function summary(Carbon $startDate, Carbon $endDate, array $lineIds = []): array
    {
        $query = DB::table('repair_order_items as roi')
            ->join('repair_orders as ro', 'ro.id', '=', 'roi.repair_order_id')
            ->join('repair_confirmations as rc', 'rc.repair_order_id', '=', 'ro.id')
            ->join('ng_types as ng', 'ng.id', '=', 'roi.ng_type_id')
            ->where('ro.status', 'confirmed')
            ->whereNotNull('ro.verified_by')
            ->whereNotNull('ro.verified_at')
            ->whereBetween('rc.confirmed_at', [$startDate, $endDate]);

        $row = $this->applyLineFilter($query, $lineIds)
            ->selectRaw('COALESCE(SUM(roi.after_qty), 0) as order_qty')
            ->selectRaw("COALESCE(SUM(CASE WHEN ng.code = 'S' THEN roi.after_qty ELSE 0 END), 0) as scrap_qty")
            ->first();

        $order = (int) ($row->order_qty ?? 0);
        $scrap = (int) ($row->scrap_qty ?? 0);

        return [
            'order' => $order,
            'ok' => max($order - $scrap, 0),
            'scrap' => $scrap,
        ];
    }

    private function monthlyChartData(Carbon $startDate, Carbon $endDate, array $lineIds = []): Collection
    {
        $query = DB::table('repair_order_items as roi')
            ->join('repair_orders as ro', 'ro.id', '=', 'roi.repair_order_id')
            ->join('repair_confirmations as rc', 'rc.repair_order_id', '=', 'ro.id')
            ->join('ng_types as ng', 'ng.id', '=', 'roi.ng_type_id')
            ->where('ro.status', 'confirmed')
            ->whereNotNull('ro.verified_by')
            ->whereNotNull('ro.verified_at')
            ->whereBetween('rc.confirmed_at', [$startDate, $endDate]);

        $raw = $this->applyLineFilter($query, $lineIds)
            ->selectRaw('YEAR(rc.confirmed_at) as y, MONTH(rc.confirmed_at) as m')
            ->selectRaw('SUM(roi.after_qty) as order_qty')
            ->selectRaw("SUM(CASE WHEN ng.code = 'S' THEN roi.after_qty ELSE 0 END) as scrap_qty")
            ->groupByRaw('YEAR(rc.confirmed_at), MONTH(rc.confirmed_at)')
            ->get()
            ->keyBy(fn($row) => sprintf('%04d-%02d', $row->y, $row->m));

        $years = range($startDate->year, $endDate->year);
        $scopeKey = FiscalYearTarget::scopeKey(null);
        $targets = FiscalYearTarget::query()
            ->whereIn('year', $years)
            ->where('scope_key', $scopeKey)
            ->pluck('target_qty', 'year');

        $result = collect();
        $cursor = $startDate->copy()->startOfMonth();
        $lastMonth = $endDate->copy()->startOfMonth();

        while ($cursor->lte($lastMonth)) {
            $key = $cursor->format('Y-m');
            $record = $raw->get($key);
            $order = (int) ($record->order_qty ?? 0);
            $scrap = (int) ($record->scrap_qty ?? 0);

            $result->push([
                'label' => $cursor->locale('id')->translatedFormat('M Y'),
                'order' => $order,
                'ok' => max($order - $scrap, 0),
                'scrap' => $scrap,
                'target' => (int) ($targets[$cursor->year] ?? 0),
            ]);

            $cursor->addMonth();
        }

        return $result;
    }

    private function matrixData(Carbon $startDate, Carbon $endDate, array $lineIds = []): Collection
    {
        $rows = $this->detailQuery($startDate, $endDate, $lineIds)
            ->orderBy('plant')
            ->orderBy('line')
            ->orderBy('model')
            ->orderBy('produk')
            ->orderBy('tanggal')
            ->get();

        return $rows
            ->groupBy(fn($row) => implode('|', [
                $row->plant,
                $row->line,
                $row->model ?? '-',
                $row->produk ?? '-',
            ]))
            ->map(function (Collection $items) {
                $first = $items->first();
                $daily = [];

                foreach ($items as $item) {
                    $day = Carbon::parse($item->tanggal)->day;
                    $daily[$day] ??= ['order' => 0, 'scrap' => 0];
                    $daily[$day]['order'] += (int) $item->total;
                    if (strtoupper((string) ($item->jenis_ng ?? '')) === 'S') {
                        $daily[$day]['scrap'] += (int) $item->total;
                    }
                }

                foreach ($daily as $day => $values) {
                    $daily[$day]['ok'] = max($values['order'] - $values['scrap'], 0);
                }

                return [
                    'plant' => $first->plant,
                    'line' => $first->line,
                    'model' => $first->model ?? '-',
                    'produk' => $first->produk ?? '-',
                    'daily' => $daily,
                ];
            })
            ->sortBy(fn($row) => implode('|', [$row['plant'], $row['line'], $row['model'], $row['produk']]))
            ->values();
    }

    private function buildMonthlyMatrixSheet($sheet, Collection $matrixRows, Carbon $monthStart, Carbon $monthEnd, Collection $selectedLineNames): void
    {
        $firstDay = $monthStart->day;
        $lastDay = $monthEnd->day;
        $daysInPeriod = $lastDay - $firstDay + 1;
        // A-E: Plant, Line, Model, Produk, Jenis; F onward: dates.
        $firstDayColumn = 6;
        $lastColumnIndex = $firstDayColumn + $daysInPeriod;
        $totalColumn = Coordinate::stringFromColumnIndex($lastColumnIndex);
        $lastDayColumn = Coordinate::stringFromColumnIndex($lastColumnIndex - 1);
        $lastColumn = $totalColumn;

        $sheet->mergeCells("A1:{$lastColumn}1");
        $sheet->setCellValue('A1', 'DATA REKAP REPAIR BOX');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(28);

        $details = [
            2 => 'Periode: ' . $monthStart->format('d-m-Y') . ' s.d. ' . $monthEnd->format('d-m-Y'),
            3 => 'Line: ' . ($selectedLineNames->isEmpty() ? 'Semua Line' : $selectedLineNames->implode(', ')),
            4 => 'Bulan: ' . $monthStart->locale('id')->translatedFormat('F Y'),
        ];
        foreach ($details as $row => $value) {
            $sheet->mergeCells("A{$row}:{$lastColumn}{$row}");
            $sheet->setCellValue("A{$row}", $value);
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("A{$row}")->getFont()->setSize(10)->getColor()->setARGB('FF475569');
        }

        foreach (['A' => 'PLANT', 'B' => 'LINE', 'C' => 'MODEL', 'D' => 'PRODUK', 'E' => 'JENIS'] as $col => $label) {
            $sheet->mergeCells("{$col}5:{$col}6");
            $sheet->setCellValue("{$col}5", $label);
        }
        $firstDayLetter = Coordinate::stringFromColumnIndex($firstDayColumn);
        $sheet->mergeCells("{$firstDayLetter}5:{$lastDayColumn}5");
        $sheet->setCellValue("{$firstDayLetter}5", 'TANGGAL');
        $sheet->mergeCells("{$totalColumn}5:{$totalColumn}6");
        $sheet->setCellValue("{$totalColumn}5", 'GRAND TOTAL');
        for ($day = $firstDay; $day <= $lastDay; $day++) {
            $col = Coordinate::stringFromColumnIndex($firstDayColumn + $day - $firstDay);
            $sheet->setCellValue("{$col}6", $day);
        }

        $headerRange = "A5:{$lastColumn}6";
        $sheet->getStyle($headerRange)->getFont()->setBold(true)->setSize(10)->getColor()->setARGB('FF24332B');
        $sheet->getStyle($headerRange)->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE5EEE8');
        $sheet->getStyle($headerRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FFB7C8BA');

        $row = 7;
        $plantSpans = [];
        $lineSpans = [];
        $modelSpans = [];
        foreach ($matrixRows as $item) {
            $startRow = $row;
            $endRow = $row + 2;
            $sheet->mergeCells("D{$startRow}:D{$endRow}");
            $sheet->setCellValue("D{$startRow}", $item['produk']);

            $plantKey = $item['plant'];
            $lineKey = $plantKey . '|' . $item['line'];
            $modelKey = $lineKey . '|' . $item['model'];
            $plantSpans[$plantKey] = $this->extendSpan($plantSpans[$plantKey] ?? null, $startRow, $endRow, $item['plant']);
            $lineSpans[$lineKey] = $this->extendSpan($lineSpans[$lineKey] ?? null, $startRow, $endRow, $item['line']);
            $modelSpans[$modelKey] = $this->extendSpan($modelSpans[$modelKey] ?? null, $startRow, $endRow, $item['model']);

            foreach ([['order', 'NG'], ['ok', 'OK'], ['scrap', 'Scrap']] as $offset => [$key, $label]) {
                $statusRow = $startRow + $offset;
                $sheet->setCellValue("E{$statusRow}", $label);
                $productTotal = 0;
                for ($day = $firstDay; $day <= $lastDay; $day++) {
                    $col = Coordinate::stringFromColumnIndex($firstDayColumn + $day - $firstDay);
                    $value = (int) ($item['daily'][$day][$key] ?? 0);
                    $productTotal += $value;
                    if ($value !== 0) {
                        $sheet->setCellValue("{$col}{$statusRow}", $value);
                    }
                }
                if ($productTotal !== 0) {
                    $sheet->setCellValue("{$totalColumn}{$statusRow}", $productTotal);
                }
            }
            $row += 3;
        }
        foreach ($plantSpans as $span) {
            $this->mergeGroupedCell($sheet, 'A', $span);
        }
        foreach ($lineSpans as $span) {
            $this->mergeGroupedCell($sheet, 'B', $span);
        }
        foreach ($modelSpans as $span) {
            $this->mergeGroupedCell($sheet, 'C', $span);
        }

        $lastDataRow = max(7, $row - 1);
        if ($matrixRows->isEmpty()) {
            $sheet->mergeCells("A7:{$lastColumn}8");
            $sheet->setCellValue('A7', 'Tidak ada order yang divalidasi OMD pada periode dan Line yang dipilih.');
            $sheet->getStyle('A7')->getFont()->setItalic(true)->getColor()->setARGB('FF64748B');
            $sheet->getStyle('A7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $lastDataRow = 8;
        }

        $summaryStart = $lastDataRow + 1;
        $summaryEnd = $summaryStart + 2;
        $sheet->mergeCells("A{$summaryStart}:D{$summaryEnd}");
        $sheet->setCellValue("A{$summaryStart}", 'TOTAL');
        foreach (['order' => 'NG', 'ok' => 'OK', 'scrap' => 'SCRAP'] as $key => $label) {
            $summaryRow = $summaryStart + array_search($key, ['order', 'ok', 'scrap'], true);
            $sheet->setCellValue("E{$summaryRow}", $label);
            $grand = 0;
            for ($day = $firstDay; $day <= $lastDay; $day++) {
                $dailyTotal = 0;
                foreach ($matrixRows as $item) {
                    $dailyTotal += (int) ($item['daily'][$day][$key] ?? 0);
                }
                $col = Coordinate::stringFromColumnIndex($firstDayColumn + $day - $firstDay);
                if ($dailyTotal !== 0) {
                    $sheet->setCellValue("{$col}{$summaryRow}", $dailyTotal);
                }
                $grand += $dailyTotal;
            }
            if ($grand !== 0) {
                $sheet->setCellValue("{$totalColumn}{$summaryRow}", $grand);
            }
        }
        $sheet->getStyle("A{$summaryStart}:{$lastColumn}{$summaryEnd}")->getFill()
            ->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE8F2EC');
        $sheet->getStyle("A{$summaryStart}:{$lastColumn}{$summaryEnd}")->getFont()->setBold(true);
        $sheet->getStyle("A{$summaryStart}:{$lastColumn}{$summaryEnd}")->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF94A99B');
        $sheet->getStyle("A{$summaryStart}:{$lastColumn}{$summaryEnd}")->getAlignment()
            ->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $dataRange = "A7:{$lastColumn}{$lastDataRow}";
        $sheet->getStyle($dataRange)->getFont()->setSize(9);
        $sheet->getStyle($dataRange)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FFD1D5DB');
        $sheet->getStyle("A7:C{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("D7:D{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle("E7:{$lastColumn}{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        foreach (['A' => 14, 'B' => 18, 'C' => 22, 'D' => 28, 'E' => 10] as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }
        for ($columnIndex = $firstDayColumn; $columnIndex < $lastColumnIndex; $columnIndex++) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($columnIndex))->setWidth(5);
        }
        $sheet->getColumnDimension($totalColumn)->setWidth(16);
        $sheet->freezePane('F7');
        $sheet->setShowGridlines(false);
        $sheet->getPageSetup()->setOrientation('landscape');
        $sheet->getPageSetup()->setFitToWidth(1)->setFitToHeight(0);
        $sheet->getPageSetup()->setPaperSize('8');
        $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(5, 6);
        $sheet->getPageMargins()->setTop(0.3)->setBottom(0.3)->setLeft(0.25)->setRight(0.25);
    }

    private function validateChartImage(Request $request): string
    {
        $raw = $request->validate(['chart_image' => ['required', 'string', 'max:12000000']])['chart_image'];
        if (!str_starts_with($raw, 'data:image/png;base64,')) {
            throw \Illuminate\Validation\ValidationException::withMessages(['chart_image' => 'Format gambar grafik tidak valid.']);
        }
        $bytes = base64_decode(substr($raw, strlen('data:image/png;base64,')), true);
        if ($bytes === false || strlen($bytes) > 7500000 || substr($bytes, 0, 8) !== "\x89PNG\r\n\x1a\n") {
            throw \Illuminate\Validation\ValidationException::withMessages(['chart_image' => 'Gambar grafik PNG tidak valid atau terlalu besar.']);
        }
        return $bytes;
    }

    private function buildMonitoringSheet($sheet, Collection $rows, string $image, Collection $selectedLineNames, Carbon $start, Carbon $end): string
    {
        $sheet->mergeCells('A1:Q1');
        $sheet->setCellValue('A1', 'DATA MONITORING & GRAFIK REPAIR BOX ' . $end->year);
        $sheet->mergeCells('A2:Q2');
        $sheet->setCellValue('A2', 'Line: ' . $selectedLineNames->implode(', '));
        $sheet->mergeCells('A3:Q3');
        $sheet->setCellValue('A3', 'Visual Dashboard: Januari - ' . $end->locale('id')->translatedFormat('F Y'));
        $sheet->mergeCells('A4:Q4');
        $sheet->setCellValue('A4', 'Periode rekap detail: ' . $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y'));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(15);
        $sheet->getStyle('A2:A4')->getFont()->setSize(10);

        $sheet->fromArray(['Bulan', 'Order', 'OK', 'Scrap', 'Target FY'], null, 'A6');
        $sheet->getStyle('A6:E6')->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A6:E6')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF5F9278');

        $rowIndex = 7;
        foreach ($rows as $row) {
            $sheet->fromArray([
                (string) $row['label'],
                (int) $row['order'],
                (int) $row['ok'],
                (int) $row['scrap'],
                (int) $row['target'],
            ], null, 'A' . $rowIndex);
            $rowIndex++;
        }

        $lastDataRow = max(6, $rowIndex - 1);
        $sheet->getStyle('A6:E' . $lastDataRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FFD8DEE8');
        $sheet->getStyle('A6:E' . $lastDataRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A7:E' . $lastDataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A7:A' . $lastDataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        foreach (['A' => 18, 'B' => 14, 'C' => 14, 'D' => 14, 'E' => 16] as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }
        foreach (range('F', 'Q') as $column) {
            $sheet->getColumnDimension($column)->setWidth(11);
        }

        $chartTopRow = $lastDataRow + 3;
        $sheet->mergeCells("A{$chartTopRow}:Q{$chartTopRow}");
        $sheet->setCellValue("A{$chartTopRow}", 'Grafik Monitoring Dashboard');
        $sheet->getStyle("A{$chartTopRow}")->getFont()->setBold(true)->setSize(12);

        $path = tempnam(sys_get_temp_dir(), 'omira_graph_');
        if ($path === false) throw new \RuntimeException('Tidak dapat membuat file grafik sementara.');
        $pngPath = $path . '.png';
        @unlink($path);
        file_put_contents($pngPath, $image);

        $draw = new Drawing();
        $draw->setName('Grafik Dashboard OMIRA');
        $draw->setPath($pngPath);
        $draw->setCoordinates('A' . ($chartTopRow + 1));
        $draw->setOffsetX(8);
        $draw->setOffsetY(6);
        $draw->setWidth(1380);
        $draw->setWorksheet($sheet);

        $sheet->freezePane('A7');
        $sheet->setShowGridlines(false);
        $sheet->getPageSetup()->setOrientation('landscape');
        $sheet->getPageSetup()->setFitToWidth(1)->setFitToHeight(0);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A3);
        $sheet->getPageMargins()->setTop(0.3)->setBottom(0.3)->setLeft(0.25)->setRight(0.25);

        return $pngPath;
    }

    private function extendSpan(?array $span, int $start, int $end, string $value): array
    {
        if ($span === null) {
            return ['start' => $start, 'end' => $end, 'value' => $value];
        }

        $span['end'] = $end;

        return $span;
    }

    private function mergeGroupedCell($sheet, string $column, array $span): void
    {
        if ($span['start'] !== $span['end']) {
            $sheet->mergeCells("{$column}{$span['start']}:{$column}{$span['end']}");
        }
        $sheet->setCellValue("{$column}{$span['start']}", $span['value']);
    }

    private function safeSheetName(string $name): string
    {
        $name = str_replace(['\\', '/', '?', '*', '[', ']', ':'], '-', $name) ?: 'Rekap';

        return mb_substr($name, 0, 31);
    }
}
