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
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RecapController extends Controller
{
    public function index(Request $request)
    {
        [$startDate, $endDate] = $this->resolvePeriod($request);
        $selectedLineIds = $this->resolveLineIds($request);
        $lines = $this->availableLines();

        $rows = $this->detailQuery($startDate, $endDate, $selectedLineIds)
            ->orderByDesc('tanggal')
            ->orderBy('plant')
            ->orderBy('line')
            ->orderBy('model')
            ->orderBy('produk')
            ->orderBy('jenis_ng')
            ->paginate(25)
            ->withQueryString();

        return view('omd.recap.index', compact(
            'startDate',
            'endDate',
            'rows',
            'lines',
            'selectedLineIds'
        ));
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
            'startDate', 'endDate', 'monthlyReports', 'selectedLineNames'
        ))
            ->setPaper('a3', 'landscape')
            ->download($filename);
    }

    public function exportExcel(Request $request)
    {
        [$startDate, $endDate] = $this->resolvePeriod($request);
        $selectedLineIds = $this->resolveLineIds($request);
        $selectedLineNames = $this->selectedLineNames($selectedLineIds);
        $chartRows = $this->monthlyChartData($startDate, $endDate, $selectedLineIds);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

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
        }

        $this->buildChartSheet($spreadsheet, $chartRows, $selectedLineNames);
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
        $writer->setIncludeCharts(true);
        $writer->save($xlsxPath);
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
        ]);

        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : now()->startOfMonth()->startOfDay();
        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : now()->endOfDay();

        return [$startDate, $endDate];
    }

    private function resolveLineIds(Request $request): array
    {
        $request->validate([
            'line_ids' => ['nullable', 'array'],
            'line_ids.*' => ['integer', 'exists:lines,id'],
        ]);

        return collect($request->input('line_ids', []))
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
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
            ->where('ro.status', 'confirmed')
            ->whereNotNull('rc.confirmed_at')
            ->whereBetween('rc.confirmed_at', [$startDate, $endDate])
            ->where('roi.after_qty', '>', 0)
            ->selectRaw('COALESCE(apl.name, pl.name) as plant')
            ->selectRaw('COALESCE(al.name, l.name) as line')
            ->selectRaw('COALESCE(amm.model, mm.model, pmm.model, romm.model, ropmm.model) as model')
            ->selectRaw('COALESCE(ap.name, p.name, rop.name) as produk')
            ->addSelect('ng.code as jenis_ng')
            ->selectRaw('SUM(roi.after_qty) as total')
            ->selectRaw('DATE(rc.confirmed_at) as tanggal')
            ->groupByRaw('DATE(rc.confirmed_at), COALESCE(apl.name, pl.name), COALESCE(al.name, l.name), COALESCE(amm.model, mm.model, pmm.model, romm.model, ropmm.model), COALESCE(ap.name, p.name, rop.name), ng.code');

        return $this->applyLineFilter($query, $lineIds);
    }

    private function summary(Carbon $startDate, Carbon $endDate, array $lineIds = []): array
    {
        $query = DB::table('repair_order_items as roi')
            ->join('repair_orders as ro', 'ro.id', '=', 'roi.repair_order_id')
            ->join('repair_confirmations as rc', 'rc.repair_order_id', '=', 'ro.id')
            ->join('ng_types as ng', 'ng.id', '=', 'roi.ng_type_id')
            ->where('ro.status', 'confirmed')
            ->whereNotNull('rc.confirmed_at')
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
            ->whereNotNull('rc.confirmed_at')
            ->whereBetween('rc.confirmed_at', [$startDate, $endDate]);

        $raw = $this->applyLineFilter($query, $lineIds)
            ->selectRaw('YEAR(rc.confirmed_at) as y, MONTH(rc.confirmed_at) as m')
            ->selectRaw('SUM(roi.after_qty) as order_qty')
            ->selectRaw("SUM(CASE WHEN ng.code = 'S' THEN roi.after_qty ELSE 0 END) as scrap_qty")
            ->groupByRaw('YEAR(rc.confirmed_at), MONTH(rc.confirmed_at)')
            ->get()
            ->keyBy(fn ($row) => sprintf('%04d-%02d', $row->y, $row->m));

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
            ->groupBy(fn ($row) => implode('|', [
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
                    if (strtoupper((string) $item->jenis_ng) === 'S') {
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
            ->sortBy(fn ($row) => implode('|', [$row['plant'], $row['line'], $row['model'], $row['produk']]))
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
        foreach ($plantSpans as $span) { $this->mergeGroupedCell($sheet, 'A', $span); }
        foreach ($lineSpans as $span) { $this->mergeGroupedCell($sheet, 'B', $span); }
        foreach ($modelSpans as $span) { $this->mergeGroupedCell($sheet, 'C', $span); }

        $lastDataRow = max(7, $row - 1);
        if ($matrixRows->isEmpty()) {
            $sheet->mergeCells("A7:{$lastColumn}8");
            $sheet->setCellValue('A7', 'Tidak ada data confirmed pada periode dan Line yang dipilih.');
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

    private function buildChartSheet(Spreadsheet $spreadsheet, Collection $chartRows, Collection $selectedLineNames): void
    {
        $chartSheet = $spreadsheet->createSheet();
        $chartSheet->setTitle('Monitoring');
        $chartSheet->fromArray([
            ['Bulan', 'Order', 'OK', 'Scrap', 'Target FY'],
        ], null, 'A1');

        $chartRow = 2;
        foreach ($chartRows as $data) {
            $chartSheet->fromArray([
                $data['label'],
                $data['order'] ?: null,
                $data['ok'] ?: null,
                $data['scrap'] ?: null,
                $data['target'] ?: null,
            ], null, 'A' . $chartRow);
            $chartRow++;
        }

        $lastChartRow = max(2, $chartRow - 1);
        $chartSheet->getStyle('A1:E1')->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
        $chartSheet->getStyle('A1:E1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF5E9378');
        $chartSheet->getStyle("A1:E{$lastChartRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FFE5E7EB');
        foreach (['A' => 16, 'B' => 12, 'C' => 12, 'D' => 12, 'E' => 14] as $column => $width) {
            $chartSheet->getColumnDimension($column)->setWidth($width);
        }

        if ($chartRows->isEmpty()) {
            $chartSheet->setCellValue('A3', 'Tidak ada data confirmed pada periode yang dipilih.');
            return;
        }

        $categories = [
            new DataSeriesValues('String', "'Monitoring'!\$A\$2:\$A\$$lastChartRow", null, $chartRows->count()),
        ];

        $barLabels = [
            new DataSeriesValues('String', "'Monitoring'!\$B\$1", null, 1),
            new DataSeriesValues('String', "'Monitoring'!\$C\$1", null, 1),
            new DataSeriesValues('String', "'Monitoring'!\$D\$1", null, 1),
        ];
        $barValues = [
            new DataSeriesValues('Number', "'Monitoring'!\$B\$2:\$B\$$lastChartRow", null, $chartRows->count()),
            new DataSeriesValues('Number', "'Monitoring'!\$C\$2:\$C\$$lastChartRow", null, $chartRows->count()),
            new DataSeriesValues('Number', "'Monitoring'!\$D\$2:\$D\$$lastChartRow", null, $chartRows->count()),
        ];

        foreach (['578EF5', '76C457', 'F0A44B'] as $index => $hex) {
            $barValues[$index]->setFillColor($hex);
        }

        $barSeries = new DataSeries(
            DataSeries::TYPE_BARCHART,
            DataSeries::GROUPING_CLUSTERED,
            range(0, count($barValues) - 1),
            $barLabels,
            $categories,
            $barValues
        );
        $barSeries->setPlotDirection(DataSeries::DIRECTION_COL);

        $targetValues = new DataSeriesValues('Number', "'Monitoring'!\$E\$2:\$E\$$lastChartRow", null, $chartRows->count());
        $targetValues->setLineColorProperties('D75B62');
        $targetValues->setLineWidth(28575);

        $lineSeries = new DataSeries(
            DataSeries::TYPE_LINECHART,
            null,
            [0],
            [new DataSeriesValues('String', "'Monitoring'!\$E\$1", null, 1)],
            $categories,
            [$targetValues]
        );

        $plotArea = new PlotArea(null, [$barSeries, $lineSeries]);
        $chart = new Chart(
            'repair_box_chart',
            new Title('Grafik Order Repair Box  Fiscal Year  — ' . ($selectedLineNames->isEmpty() ? 'Semua Line' : $selectedLineNames->implode(', '))),
            new Legend(Legend::POSITION_BOTTOM, null, false),
            $plotArea,
            true,
            DataSeries::EMPTY_AS_GAP,
            new Title('Periode'),
            new Title('Qty')
        );
        $chart->setTopLeftPosition('G2');
        $chart->setBottomRightPosition('T22');
        $chartSheet->addChart($chart);
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
