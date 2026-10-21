<?php

namespace App\Http\Controllers;

use App\Models\FiscalYearTarget;
use App\Models\MonitoringAbnormality;
use App\Models\RepairMonthlySnapshot;
use App\Services\AnnualRecapPdfConverter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\GridLines;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

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


    private function dashboardChartData(Carbon $endDate, array $lineIds): array
    {
        $dashboard = app(DashboardController::class);

        $fiscalYear = $endDate->month >= 4 ? $endDate->year : $endDate->year - 1;
        $params = ['year' => $fiscalYear];
        $selectedLines = array_values(array_unique(array_map('intval', $lineIds)));
        $base = $dashboard->index(Request::create('/dashboard', 'GET', $params))->getData();


        if (count($selectedLines) > 1) {
            $order = array_fill(0, count($base['months']), 0);
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

        return view('omd.dashboard.index', $data);
    }

    public function exportPdf(Request $request)
    {
        [$startDate, $endDate] = $this->resolvePeriod($request);
        $selectedLineIds = $this->resolveLineIds($request);
        $selectedLineNames = $this->selectedLineNames($selectedLineIds);
        $isYearly = $request->input('period_type', 'monthly') === 'yearly';
        // PDF tahunan berasal dari workbook yang sama dengan Export Excel (termasuk Native Chart).
        if ($isYearly) {
            $workbook = $this->createAnnualRecapWorkbook($startDate->year, $selectedLineIds, $selectedLineNames);
            try {
                $pdfPath = app(AnnualRecapPdfConverter::class)->convert($workbook);
            } catch (\RuntimeException $exception) {
                abort(503, $exception->getMessage());
            } finally {
                $workbook->disconnectWorksheets();
            }

            $filename = sprintf('Rekap_Repair_Box_%s_sd_%s.pdf', $startDate->format('Y-m-d'), $endDate->format('Y-m-d'));
            return response()->download($pdfPath, $filename)->deleteFileAfterSend(true);
        }

        $annualSummary = [];
        $monthlyReports = collect();

        if (! $isYearly) {
            $dates = $this->reportDates($startDate, $endDate);
            $matrixRows = $this->matrixData($startDate, $endDate, $selectedLineIds);
            $monthlyReports->push([
                'label' => $startDate->locale('id')->translatedFormat('F Y'),
                'days' => $dates,
                'range_label' => $startDate->isSameMonth($endDate)
                    ? $startDate->locale('id')->translatedFormat('F Y')
                    : $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y'),

                'pages' => $matrixRows->isEmpty()
                    ? collect([collect()])
                    : $matrixRows->chunk(18)->map(fn(Collection $chunk) => $this->groupMatrixForPdf($chunk))->values(),
                'grandDaily' => $this->sumDailyMatrix($matrixRows, $dates),
                'productCount' => $matrixRows->count(),
            ]);
        }

        $filename = sprintf('Rekap_Repair_Box_%s_sd_%s.pdf', $startDate->format('Y-m-d'), $endDate->format('Y-m-d'));
        return Pdf::loadView('omd.recap.pdf', compact(
            'startDate',
            'endDate',
            'monthlyReports',
            'selectedLineNames',
            'isYearly',
            'annualSummary'
        ))->setPaper('a4', 'landscape')->download($filename);
    }

    /** Susunan grup dan rowspan tetap sama dengan report harian sebelumnya. */
    private function groupMatrixForPdf(Collection $matrixRows): Collection
    {
        return $matrixRows->groupBy('plant')->map(function (Collection $plantRows, $plantName) {
            $lines = $plantRows->groupBy('line')->map(function (Collection $lineRows, $lineName) {
                $models = $lineRows->groupBy('model')->map(function (Collection $modelRows, $modelName) {
                    $products = $modelRows->values();
                    return [
                        'name' => $modelName,
                        'products' => $products,
                        'rowspan' => $products->count() * 3,
                    ];
                })->values();
                return ['name' => $lineName, 'models' => $models, 'rowspan' => $models->sum('rowspan')];
            })->values();
            return ['name' => $plantName, 'lines' => $lines, 'rowspan' => $lines->sum('rowspan')];
        })->values();
    }

    public function exportExcel(Request $request)
    {
        [$startDate, $endDate] = $this->resolvePeriod($request);
        $selectedLineIds = $this->resolveLineIds($request);
        $selectedLineNames = $this->selectedLineNames($selectedLineIds);
        $isYearly = $request->input('period_type', 'monthly') === 'yearly';
        if ($isYearly) {
            $spreadsheet = $this->createAnnualRecapWorkbook($startDate->year, $selectedLineIds, $selectedLineNames);
        } else {
            // Load file yang diminta user secara langsung agar merge, posisi TOTAL,
            // GRAND TOTAL, lebar kolom, dan style tidak diubah menjadi format baru.
            $spreadsheet = IOFactory::load(resource_path('templates/DATA_REKAP_BULANAN_OMD.xlsx'));
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Rekap');
            $dates = $this->reportDates($startDate, $endDate);
            $this->fillDailyTemplateSheet(
                $sheet,
                $this->matrixData($startDate, $endDate, $selectedLineIds),
                $dates,
                $startDate,
                $endDate
            );
        }
        $spreadsheet->setActiveSheetIndex(0);

        $filename = sprintf('Rekap_Repair_Box_%s_sd_%s.xlsx', $startDate->format('Y-m-d'), $endDate->format('Y-m-d'));
        $tempPath = tempnam(sys_get_temp_dir(), 'omira_recap_');
        $xlsxPath = $tempPath . '.xlsx';
        @unlink($tempPath);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        // Chart harus diikutsertakan: default writer XLSX tidak mengekspor chart.
        if ($isYearly) {
            $writer->setIncludeCharts(true);
        }
        $writer->save($xlsxPath);
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
        return response()->download($xlsxPath, $filename)->deleteFileAfterSend(true);
    }

    /** Sumber tunggal Excel dan PDF FY: angka, format, chart, dan abnormality identik. */
    private function createAnnualRecapWorkbook(int $fiscalYear, array $lineIds, Collection $lineNames): Spreadsheet
    {
        $months = $this->annualSummary($fiscalYear, $lineIds);
        $workbook = new Spreadsheet();
        $sheet = $workbook->getActiveSheet();
        $sheet->setTitle('Rekap Tahunan');
        $this->buildAnnualSummarySheet($sheet, $months, $fiscalYear, $lineNames);
        $this->addAnnualNativeChart($sheet, $fiscalYear);
        $this->buildAnnualAbnormalityTable(
            $sheet,
            $months,
            $fiscalYear,
            $this->annualAbnormalityReasons($fiscalYear, empty($lineIds))
        );
        $workbook->setActiveSheetIndex(0);

        return $workbook;
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

        $periodType = $request->input('period_type', 'monthly');

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

        if ($periodType === 'yearly') {
            $year = (int) $request->input('report_year', now()->year);
            return [Carbon::create($year, 4, 1)->startOfDay(), Carbon::create($year + 1, 3, 31)->endOfDay()];
        }

        // Bulanan selalu tanggal 1 sampai akhir bulan, meskipun bulan belum selesai.
        $year = (int) $request->input('report_year', now()->year);
        $month = (int) $request->input('report_month', now()->month);
        return [Carbon::create($year, $month, 1)->startOfDay(), Carbon::create($year, $month, 1)->endOfMonth()->endOfDay()];
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
            ->keyBy(fn($row) => sprintf('%04d-%02d', $row->calendar_year, $row->calendar_month));

        // Snapshot historis digunakan hanya pada rekap global (Semua Line).
        // Jangan dijumlahkan dengan transaksi agar angka FY tidak terhitung ganda.
        // Snapshot tidak memiliki rincian per Line, sehingga filter Line tetap
        // memakai data transaksi aktual dari $actualRows.
        $snapshots = collect();
        if (empty($lineIds)) {
            $snapshots = RepairMonthlySnapshot::query()
                ->where(function ($query) use ($fiscalYear) {
                    $query->where(function ($q) use ($fiscalYear) {
                        $q->where('year', $fiscalYear)->whereBetween('month', [4, 12]);
                    })->orWhere(function ($q) use ($fiscalYear) {
                        $q->where('year', $fiscalYear + 1)->whereBetween('month', [1, 3]);
                    });
                })
                ->get()
                ->keyBy(fn($row) => sprintf('%04d-%02d', $row->year, $row->month));
        }

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
            $snapshot = $snapshots->get($key);
            $order = $snapshot ? (int) $snapshot->order_qty : ($actual ? (int) $actual->order_qty : null);
            $scrap = $snapshot ? (int) $snapshot->scrap_qty : ($actual ? (int) $actual->scrap_qty : null);

            $months[] = [
                'label' => $cursor->locale('id')->translatedFormat('M'),
                'target' => $snapshot ? (int) $snapshot->target_qty : $target,
                'order' => $order,
                'finish' => $snapshot ? (int) $snapshot->ok_qty : ($actual ? max($order - $scrap, 0) : null),
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
        // Sumber baris adalah transaksi Order Repair OMD pada periode terpilih,
        // bukan seluruh katalog Data Master. Plant/Line/Model/Produk tanpa
        // transaksi tidak ditampilkan pada export PDF maupun Excel.
        $grouped = [];
        $add = function ($plant, $line, $model, $produk) use (&$grouped): string {
            $plant = (string) ($plant ?? '-');
            $line = (string) ($line ?? '-');
            $model = (string) ($model ?? '-');
            $produk = (string) ($produk ?? '-');
            $key = json_encode([$plant, $line, $model, $produk], JSON_UNESCAPED_UNICODE);
            if (! isset($grouped[$key])) {
                $grouped[$key] = compact('plant', 'line', 'model', 'produk') + ['daily' => []];
            }
            return $key;
        };
        foreach ($this->detailQuery($startDate, $endDate, $lineIds)->get() as $item) {
            $key = $add($item->plant, $item->line, $item->model, $item->produk);
            // Full date, not the day number: handles custom ranges crossing months.
            $date = Carbon::parse($item->tanggal)->toDateString();
            $grouped[$key]['daily'][$date] ??= ['order' => 0, 'scrap' => 0];
            $grouped[$key]['daily'][$date]['order'] += (int) $item->total;
            if (strtoupper((string) ($item->jenis_ng ?? '')) === 'S') {
                $grouped[$key]['daily'][$date]['scrap'] += (int) $item->total;
            }
        }
        foreach ($grouped as &$item) {
            foreach ($item['daily'] as &$values) {
                $values['ok'] = max($values['order'] - $values['scrap'], 0);
            }
            unset($values);
        }
        unset($item);
        return collect(array_values($grouped))
            ->sortBy(fn($item) => json_encode([$item['plant'], $item['line'], $item['model'], $item['produk']]))
            ->values();
    }

    private function reportDates(Carbon $startDate, Carbon $endDate): array
    {
        $dates = [];
        for ($day = $startDate->copy()->startOfDay(); $day->lte($endDate); $day->addDay()) {
            $dates[] = [
                'key' => $day->toDateString(),
                'day' => $day->day,
                'header' => $startDate->isSameMonth($endDate) ? (string) $day->day : $day->format('d/m'),
            ];
        }
        return $dates;
    }

    private function sumDailyMatrix(Collection $matrixRows, array $dates): array
    {
        $totals = [];
        foreach ($dates as $date) {
            foreach (['order', 'ok', 'scrap'] as $key) {
                $totals[$date['key']][$key] = $matrixRows->sum(
                    fn($product) => (int) ($product['daily'][$date['key']][$key] ?? 0)
                );
            }
        }
        return $totals;
    }

    /**
     * Isi cell langsung pada template Excel permintaan user.
     * Lokasi asli: B:E grup, F jenis, G:AK tanggal, AL:AM GRAND TOTAL,
     * B264:E266 TOTAL dengan NG/OK/Scrap di F264:F266.
     */
    private function fillDailyTemplateSheet(Worksheet $sheet, Collection $matrixRows, array $dates, Carbon $start, Carbon $end): void
    {
        $productCount = max(1, $matrixRows->count());
        $originalProducts = 86; // Baris 6 s.d. 263 pada template asli, 3 baris/produk.
        $lastDataRow = 5 + ($productCount * 3);
        $footerRow = $lastDataRow + 1;
        $dayCount = count($dates);
        $extraDays = max(0, $dayCount - 31);

        // Lepaskan hanya merge dinamis; header template tetap seperti aslinya.
        foreach (array_keys($sheet->getMergeCells()) as $range) {
            if (preg_match('/^[A-Z]+(\d+):[A-Z]+\d+$/', $range, $match) && (int) $match[1] >= 6) {
                $sheet->unmergeCells($range);
            }
        }

        if ($productCount > $originalProducts) {
            $sheet->insertNewRowBefore(264, ($productCount - $originalProducts) * 3);
            // Baris tambahan mewarisi format per indikator dari template.
            for ($row = 264; $row <= $lastDataRow; $row++) {
                $sourceRow = 6 + (($row - 6) % 3);
                foreach (range(2, 37) as $column) {
                    $letter = Coordinate::stringFromColumnIndex($column);
                    $sheet->duplicateStyle($sheet->getStyle("{$letter}{$sourceRow}"), "{$letter}{$row}");
                }
            }
        } elseif ($productCount < $originalProducts) {
            $sheet->removeRow($footerRow, ($originalProducts - $productCount) * 3);
        }

        if ($extraDays) {
            $sheet->unmergeCells('G4:AK4');
            $sheet->insertNewColumnBefore('AL', $extraDays);
            $lastDayColumn = Coordinate::stringFromColumnIndex(37 + $extraDays);
            $sheet->mergeCells("G4:{$lastDayColumn}4");
            for ($n = 0; $n < $extraDays; $n++) {
                $col = Coordinate::stringFromColumnIndex(38 + $n);
                $sheet->duplicateStyle($sheet->getStyle('AK5'), "{$col}5");
                for ($row = 6; $row <= $footerRow + 2; $row++) {
                    $sheet->duplicateStyle($sheet->getStyle("AK{$row}"), "{$col}{$row}");
                }
                $sheet->getColumnDimension($col)->setWidth(7);
            }
        }
        $grandNumberCol = Coordinate::stringFromColumnIndex(38 + $extraDays); // AL
        $grandLabelCol = Coordinate::stringFromColumnIndex(39 + $extraDays);  // AM
        $lastDayColumn = Coordinate::stringFromColumnIndex(6 + $dayCount);

        // Header dan tanggal tidak mengubah susunan template.
        $sheet->setCellValue('G4', 'Bulan : ' . ($start->isSameMonth($end)
            ? $start->locale('id')->translatedFormat('F Y')
            : $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y')));
        for ($i = 0; $i < $dayCount; $i++) {
            $col = Coordinate::stringFromColumnIndex(7 + $i);
            $sheet->setCellValue("{$col}5", $start->isSameMonth($end) ? $dates[$i]['day'] : $dates[$i]['header']);
            if (! $start->isSameMonth($end)) $sheet->getColumnDimension($col)->setWidth(7.5);
        }
        // Sembunyikan tanggal template yang tidak termasuk periode, tanpa memindahkan TOTAL.
        for ($i = $dayCount; $i < 31; $i++) {
            $col = Coordinate::stringFromColumnIndex(7 + $i);
            $sheet->setCellValue("{$col}5", null);
            $sheet->getColumnDimension($col)->setVisible(false);
        }

        // Ganti hanya nilai lama; merge, ukuran dan style aslinya digunakan lagi.
        foreach (range(6, $lastDataRow) as $row) {
            foreach (['B', 'C', 'D', 'E', 'F', $grandNumberCol, $grandLabelCol] as $col) {
                $sheet->setCellValue("{$col}{$row}", null);
            }
        }
        $plants = $lines = $models = [];
        $row = 6;
        foreach ($matrixRows as $item) {
            $firstRow = $row;
            $sheet->mergeCells("E{$row}:E" . ($row + 2));
            $sheet->setCellValue("E{$row}", $item['produk']);
            $plantKey = $item['plant'];
            $lineKey = json_encode([$item['plant'], $item['line']]);
            $modelKey = json_encode([$item['plant'], $item['line'], $item['model']]);
            $plants[$plantKey] = $this->extendSpan($plants[$plantKey] ?? null, $row, $row + 2, $item['plant']);
            $lines[$lineKey] = $this->extendSpan($lines[$lineKey] ?? null, $row, $row + 2, $item['line']);
            $models[$modelKey] = $this->extendSpan($models[$modelKey] ?? null, $row, $row + 2, $item['model']);
            foreach (['order' => 'NG', 'ok' => 'OK', 'scrap' => 'Scrap'] as $offsetKey => $label) {
                $offset = array_search($offsetKey, ['order', 'ok', 'scrap'], true);
                $thisRow = $firstRow + $offset;
                $sheet->setCellValue("F{$thisRow}", $label);
                foreach ($dates as $index => $date) {
                    $val = (int) ($item['daily'][$date['key']][$offsetKey] ?? 0);
                    if ($val > 0) {
                        $col = Coordinate::stringFromColumnIndex(7 + $index);
                        $sheet->setCellValue("{$col}{$thisRow}", $val);
                    }
                }
            }
            $row += 3;
        }
        if ($matrixRows->isEmpty()) {
            $sheet->setCellValue('F6', 'NG');
            $sheet->setCellValue('F7', 'OK');
            $sheet->setCellValue('F8', 'Scrap');
        }
        foreach ($plants as $span) $this->mergeGroupedCell($sheet, 'B', $span);
        foreach ($lines as $span) $this->mergeGroupedCell($sheet, 'C', $span);
        foreach ($models as $span) $this->mergeGroupedCell($sheet, 'D', $span);

        // Pada template asli, GRAND TOTAL mulai di AL263, yaitu sisi kanan
        // baris produk terakhir, bukan judul kolom Total per produk.
        $sheet->mergeCells("{$grandNumberCol}{$lastDataRow}:{$grandLabelCol}{$lastDataRow}");
        $sheet->setCellValue("{$grandNumberCol}{$lastDataRow}", 'GRAND TOTAL');
        $sheet->getStyle("{$grandNumberCol}{$lastDataRow}:{$grandLabelCol}{$lastDataRow}")
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells("B{$footerRow}:E" . ($footerRow + 2));
        $sheet->setCellValue("B{$footerRow}", 'TOTAL');
        foreach (['NG', 'OK', 'Scrap'] as $offset => $label) {
            $thisRow = $footerRow + $offset;
            $sheet->setCellValue("F{$thisRow}", $label);
            $sheet->setCellValue("{$grandLabelCol}{$thisRow}", $label);
            foreach ($dates as $index => $date) {
                $col = Coordinate::stringFromColumnIndex(7 + $index);
                // Jenis pada kolom F tetap menjadi kriteria penjumlahan seperti sebelumnya.
                // SUMIF menghitung ulang TOTAL ketika OMD mengedit rincian NG/OK/Scrap.
                $sheet->setCellValue(
                    "{$col}{$thisRow}",
                    sprintf('=SUMIF($F$6:$F$%d,$F$%d,%s$6:%s$%d)', $lastDataRow, $thisRow, $col, $col, $lastDataRow)
                );
            }
            // GRAND TOTAL tetap SUM dari baris TOTAL setiap hari.
            $sheet->setCellValue("{$grandNumberCol}{$thisRow}", "=SUM(G{$thisRow}:{$lastDayColumn}{$thisRow})");
        }
        $sheet->getStyle("B{$footerRow}:{$grandLabelCol}" . ($footerRow + 2))
            ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("B{$footerRow}:{$grandLabelCol}" . ($footerRow + 2))->getFont()->setBold(true);

        // Area kanan di atas GRAND TOTAL sengaja tanpa border seperti template.
        // Hanya judul GRAND TOTAL dan tiga baris nilai NG/OK/Scrap yang dibingkai.
        $sheet->getStyle("{$grandNumberCol}4:{$grandLabelCol}" . ($lastDataRow - 1))
            ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_NONE);
        $sheet->getStyle("{$grandNumberCol}{$lastDataRow}:{$grandLabelCol}{$lastDataRow}")
            ->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("{$grandNumberCol}{$footerRow}:{$grandLabelCol}" . ($footerRow + 2))
            ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("G5:{$grandLabelCol}" . ($footerRow + 2))
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Baris 1 kosong pada template: gunakan untuk judul tanpa menggeser tabel.
        $sheet->mergeCells("B1:{$grandLabelCol}1");
        $sheet->setCellValue('B1', 'Repair Box Workshop OMD');
        $sheet->getStyle("B1:{$grandLabelCol}1")->getFont()->setBold(true)->setSize(18);
        $sheet->getStyle("B1:{$grandLabelCol}1")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(32);
        $sheet->setShowGridlines(false);
        $setup = $sheet->getPageSetup();
        $setup->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
            ->setPaperSize(PageSetup::PAPERSIZE_A4)
            ->setFitToPage(true)->setFitToWidth(1)->setFitToHeight(0);
        $setup->setRowsToRepeatAtTopByStartAndEnd(1, 5);
        $setup->setPrintArea("B1:{$grandLabelCol}" . ($footerRow + 2));
        $sheet->getPageMargins()->setTop(0.25)->setBottom(0.25)->setLeft(0.2)->setRight(0.2);
        $sheet->freezePane('G6');
    }

    /** Layout FY April–Maret: rekap A4:M8, chart A10:K29, abnormality mulai A31. */
    private function buildAnnualSummarySheet(Worksheet $sheet, array $months, int $fiscalYear, Collection $selectedLineNames): void
    {
        $sheet->mergeCells('A1:M1');
        $sheet->setCellValue('A1', 'DATA REKAP REPAIR BOX FY ' . $fiscalYear);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(24);

        $sheet->mergeCells('A2:M2');
        $sheet->setCellValue('A2', 'Periode: April ' . $fiscalYear . ' - Maret ' . ($fiscalYear + 1));
        $sheet->mergeCells('A3:M3');
        $sheet->setCellValue('A3', 'Line: ' . ($selectedLineNames->isEmpty() ? 'Semua Line' : $selectedLineNames->implode(', ')));
        $sheet->getStyle('A2:M3')->getFont()->setSize(9);
        $sheet->getStyle('A2:M3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(2)->setRowHeight(17);
        $sheet->getRowDimension(3)->setRowHeight(17);

        $sheet->setCellValue('A4', 'INDIKATOR');
        foreach ($months as $i => $month) {
            $column = Coordinate::stringFromColumnIndex($i + 2);
            $sheet->setCellValue($column . '4', strtoupper($month['label']));
        }
        foreach ([5 => ['target', 'TARGET'], 6 => ['order', 'ORDER'], 7 => ['finish', 'FINISH'], 8 => ['scrap', 'SCRAP']] as $row => [$key, $label]) {
            $sheet->setCellValue('A' . $row, $label);
            foreach ($months as $monthIndex => $month) {
                if ($month[$key] !== null) {
                    $column = Coordinate::stringFromColumnIndex($monthIndex + 2);
                    $sheet->setCellValue($column . $row, (int) $month[$key]);
                }
            }
        }

        $sheet->getStyle('A4:M8')->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FFB8C3CC');
        $sheet->getStyle('A4:M4')->getFont()->setBold(true);
        $sheet->getStyle('A5:A8')->getFont()->setBold(true);
        $sheet->getStyle('A4:M8')->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A4:M8')->getFont()->setSize(10);
        $sheet->getStyle('B5:M8')->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getColumnDimension('A')->setWidth(17);
        foreach (range('B', 'M') as $column) {
            $sheet->getColumnDimension($column)->setWidth(11);
        }
        foreach (range(4, 8) as $row) {
            $sheet->getRowDimension($row)->setRowHeight(21);
        }
        $sheet->getRowDimension(9)->setRowHeight(10);
        foreach (range(10, 29) as $row) {
            $sheet->getRowDimension($row)->setRowHeight(17);
        }
        $sheet->getRowDimension(30)->setRowHeight(10);
        $sheet->freezePane('B5');
        $sheet->setShowGridlines(false);
        $setup = $sheet->getPageSetup();
        $setup->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
            ->setPaperSize(PageSetup::PAPERSIZE_A4)
            ->setFitToPage(true)->setFitToWidth(1)->setFitToHeight(1);
        $sheet->getPageMargins()->setTop(0.25)->setBottom(0.25)->setLeft(0.2)->setRight(0.2);
        $setup->setHorizontalCentered(true);
    }

    /** Catatan Dashboard hanya milik cakupan global; jangan kaitkan ke filter Line. */
    private function annualAbnormalityReasons(int $fiscalYear, bool $isGlobal): array
    {
        if (! $isGlobal) {
            return [];
        }

        return MonitoringAbnormality::query()
            ->where('metric', MonitoringAbnormality::METRIC_TARGET_FY)
            ->where('scope_key', FiscalYearTarget::scopeKey(null))
            ->where(function ($query) use ($fiscalYear) {
                $query->where(function ($q) use ($fiscalYear) {
                    $q->where('year', $fiscalYear)->whereBetween('month', [4, 12]);
                })->orWhere(function ($q) use ($fiscalYear) {
                    $q->where('year', $fiscalYear + 1)->whereBetween('month', [1, 3]);
                });
            })
            ->get()
            ->mapWithKeys(fn($item) => [sprintf('%04d-%02d', $item->year, $item->month) => trim((string) $item->reason)])
            ->all();
    }

    /** Tabel abnormality menggunakan referensi langsung ke sel rekap agar selisih editable. */
    private function buildAnnualAbnormalityTable(Worksheet $sheet, array $months, int $fiscalYear, array $reasonsByMonth): void
    {
        $sheet->mergeCells('A31:I31');
        $sheet->setCellValue('A31', 'TABEL ABNORMALITY');
        $sheet->getStyle('A31')->getFont()->setBold(true)->setSize(10);
        $sheet->getRowDimension(31)->setRowHeight(20);

        foreach (['A' => 'Bulan', 'B' => 'ORDER/NG', 'C' => 'TARGET FY', 'D' => 'SELISIH', 'E' => 'KETERANGAN'] as $column => $heading) {
            $sheet->setCellValue($column . '32', $heading);
        }
        $sheet->mergeCells('E32:I32');
        $sheet->getStyle('A32:I32')->getFont()->setBold(true)->setSize(9);
        $sheet->getRowDimension(32)->setRowHeight(21);

        $abnormalMonths = [];
        foreach ($months as $index => $month) {
            $date = Carbon::create($fiscalYear, 4, 1)->addMonths($index);
            $key = $date->format('Y-m');
            $order = $month['order'];
            $target = (int) $month['target'];
            $hasReason = isset($reasonsByMonth[$key]) && $reasonsByMonth[$key] !== '';
            if (($order !== null && $target > 0 && (int) $order > $target) || $hasReason) {
                $abnormalMonths[] = [
                    'label' => $date->locale('id')->translatedFormat('M Y'),
                    'source' => Coordinate::stringFromColumnIndex($index + 2),
                    'reason' => $reasonsByMonth[$key] ?? '',
                ];
            }
        }

        // Bila tidak ada abnormality, sisakan 3 baris kosong sesuai format referensi.
        $entries = $abnormalMonths ?: array_fill(0, 3, null);
        foreach ($entries as $index => $entry) {
            $row = 33 + $index;
            $sheet->mergeCells("E{$row}:I{$row}");
            if ($entry !== null) {
                $col = $entry['source'];
                $sheet->setCellValue("A{$row}", $entry['label']);
                $sheet->setCellValue("B{$row}", "=IF(ISNUMBER({$col}6),{$col}6,\"\")");
                $sheet->setCellValue("C{$row}", "=IF(ISNUMBER({$col}5),{$col}5,\"\")");
                $sheet->setCellValue("D{$row}", "=IF(OR(B{$row}=\"\",C{$row}=\"\"),\"\",B{$row}-C{$row})");
                $sheet->setCellValue("E{$row}", $entry['reason']);
                $reasonLength = mb_strlen($entry['reason']);
                $sheet->getRowDimension($row)->setRowHeight(max(21, min(78, 18 * (int) ceil(max(1, $reasonLength) / 64))));
            } else {
                $sheet->getRowDimension($row)->setRowHeight(21);
            }
        }

        $lastRow = 32 + count($entries);
        $sheet->getStyle("A32:I{$lastRow}")->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF64748B');
        $sheet->getStyle("A32:I{$lastRow}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A32:I32')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A33:D{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("E33:I{$lastRow}")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_LEFT)->setWrapText(true);
        $sheet->getStyle("B33:D{$lastRow}")->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle("A33:I{$lastRow}")->getFont()->setSize(9);

        // Banyaknya alasan bersifat dinamis. Jangan paksa satu halaman bila sulit dibaca.
        $sheet->getPageSetup()->setFitToHeight(count($abnormalMonths) > 5 ? 0 : 1);
        $sheet->getPageSetup()->setPrintArea('A1:M' . $lastRow);
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
        $sheet->getStyle("A{$summaryStart}:{$lastColumn}{$summaryEnd}")->getFont()->setBold(true);
        $sheet->getStyle("A{$summaryStart}:{$lastColumn}{$summaryEnd}")->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF94A99B');
        $sheet->getStyle("A{$summaryStart}:{$lastColumn}{$summaryEnd}")->getAlignment()
            ->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $dataRange = "A7:{$lastColumn}{$lastDataRow}";
        $sheet->getStyle($dataRange)->getFont()->setSize(10);
        $sheet->getStyle($dataRange)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FFD1D5DB');
        $sheet->getStyle("A7:C{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("D7:D{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle("E7:{$lastColumn}{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        foreach (['A' => 14, 'B' => 18, 'C' => 22, 'D' => 28, 'E' => 10] as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }
        for ($columnIndex = $firstDayColumn; $columnIndex < $lastColumnIndex; $columnIndex++) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($columnIndex))->setWidth(5.5);
        }
        $sheet->getColumnDimension($totalColumn)->setWidth(16);
        $sheet->freezePane('F7');
        $sheet->setShowGridlines(false);
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
        // Jangan perkecil 31 kolom ke 1 halaman: biarkan beberapa halaman A4.
        $sheet->getPageSetup()->setFitToPage(false)->setScale(85);
        $sheet->getPageSetup()->setColumnsToRepeatAtLeftByStartAndEnd('A', 'E');
        $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(5, 6);
        $sheet->getPageMargins()->setTop(0.3)->setBottom(0.3)->setLeft(0.25)->setRight(0.25);
        $sheet->getPageSetup()->setPrintArea("A1:{$lastColumn}{$summaryEnd}");
    }

    /** Native combo chart membaca baris horizontal dari tabel utama, tanpa worksheet tambahan. */
    private function addAnnualNativeChart(Worksheet $sheet, int $fiscalYear): void
    {
        $source = "'" . str_replace("'", "''", $sheet->getTitle()) . "'!";
        $categories = $source . '$B$4:$M$4';
        $barLabels = [];
        $barCategories = [];
        $barValues = [];
        foreach ([6 => ['NG', 'F59E0B'], 7 => ['OK', '94A3B8'], 8 => ['Scrap', 'FACC15']] as $row => [$label, $color]) {
            $barLabels[] = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, null, null, 1, [$label]);
            $barCategories[] = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $categories, null, 12);
            $barValues[] = new DataSeriesValues(
                DataSeriesValues::DATASERIES_TYPE_NUMBER,
                $source . '$B$' . $row . ':$M$' . $row,
                '#,##0',
                12,
                [],
                null,
                $color
            );
        }
        $bars = new DataSeries(
            DataSeries::TYPE_BARCHART,
            DataSeries::GROUPING_CLUSTERED,
            [0, 1, 2],
            $barLabels,
            $barCategories,
            $barValues
        );
        $bars->setPlotDirection(DataSeries::DIRECTION_COL);

        $targetValues = new DataSeriesValues(
            DataSeriesValues::DATASERIES_TYPE_NUMBER,
            $source . '$B$5:$M$5',
            '#,##0',
            12,
            [],
            'circle',
            '2563EB',
            6
        );
        $targetValues->setLineColorProperties('2563EB');
        $targetValues->setLineWidth(2.5);
        $targetValues->getMarkerFillColor()->setColorProperties('2563EB');
        $targetValues->getMarkerBorderColor()->setColorProperties('2563EB');

        $line = new DataSeries(
            DataSeries::TYPE_LINECHART,
            DataSeries::GROUPING_STANDARD,
            [3],
            [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, null, null, 1, ['Target FY'])],
            [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $categories, null, 12)],
            [$targetValues]
        );

        $layout = new Layout();
        $layout->setShowVal(true);
        $chart = new Chart(
            'omira_rekap_fy_' . $fiscalYear,
            new Title('Grafik Order Repair Box FY ' . $fiscalYear),
            new Legend(Legend::POSITION_BOTTOM, null, false),
            new PlotArea($layout, [$bars, $line]),
            true,
            DataSeries::EMPTY_AS_GAP,
            new Title('FY ' . $fiscalYear),
            new Title('Jumlah Box')
        );
        $gridlines = new GridLines();
        $gridlines->setLineColorProperties('DDE5ED');
        $chart->getChartAxisY()->setMajorGridlines($gridlines);
        $chart->setTopLeftPosition('A10', 8, 4);
        $chart->setBottomRightPosition('K29', 0, 0);
        $sheet->addChart($chart);
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
