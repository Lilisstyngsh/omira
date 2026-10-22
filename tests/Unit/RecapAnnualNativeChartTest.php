<?php

namespace Tests\Unit;

use App\Http\Controllers\RecapController;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use ZipArchive;

class RecapAnnualNativeChartTest extends TestCase
{
    private function invoke(RecapController $controller, string $name, mixed ...$args): mixed
    {
        return (new ReflectionMethod($controller, $name))->invoke($controller, ...$args);
    }

    private function months(bool $empty = false): array
    {
        $labels = ['Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des', 'Jan', 'Feb', 'Mar'];
        return array_map(static function (string $label, int $index) use ($empty): array {
            $order = $empty ? null : ($index === 1 ? 1399 : (500 + $index));
            $scrap = $empty ? null : 10 + $index;
            return [
                'label' => $label,
                'target' => 855,
                'order' => $order,
                'finish' => $order === null ? null : $order - $scrap,
                'scrap' => $scrap,
            ];
        }, $labels, array_keys($labels));
    }

    private function buildBook(array $months, array $notes = [], int $fiscalYear = 2026): Spreadsheet
    {
        $book = new Spreadsheet();
        $sheet = $book->getActiveSheet();
        $sheet->setTitle('Rekap Tahunan');
        $controller = new RecapController();
        $this->invoke($controller, 'buildAnnualSummarySheet', $sheet, $months, $fiscalYear, new Collection(['Semua Line']));
        $this->invoke($controller, 'addAnnualNativeChart', $sheet, $fiscalYear);
        $this->invoke($controller, 'buildAnnualAbnormalityTable', $sheet, $months, $fiscalYear, $notes);
        return $book;
    }

    public function test_one_sheet_chart_reads_main_recap_cells_and_abnormality_references(): void
    {
        $book = $this->buildBook($this->months(), [
            '2026-05' => 'Activity sortir dari PPIC Body',
            '2026-07' => 'Catatan yang sudah disimpan di Dashboard',
        ]);
        $sheet = $book->getActiveSheet();
        $this->assertCount(1, $book->getAllSheets());
        $this->assertSame('Rekap Tahunan', $sheet->getTitle());
        $this->assertSame('INDIKATOR', $sheet->getCell('A4')->getValue());
        $this->assertSame('TARGET', $sheet->getCell('A5')->getValue());
        $this->assertSame('ORDER', $sheet->getCell('A6')->getValue());
        $this->assertSame('FINISH', $sheet->getCell('A7')->getValue());
        $this->assertSame('SCRAP', $sheet->getCell('A8')->getValue());
        $this->assertSame(1399, $sheet->getCell('C6')->getValue());
        $this->assertSame('TABEL ABNORMALITY', $sheet->getCell('A31')->getValue());
        $this->assertSame('Mei 2026', $sheet->getCell('A33')->getValue());
        $this->assertSame('=IF(ISNUMBER(C6),C6,"")', $sheet->getCell('B33')->getValue());
        $this->assertSame('=IF(ISNUMBER(C5),C5,"")', $sheet->getCell('C33')->getValue());
        $this->assertSame('=IF(OR(B33="",C33=""),"",B33-C33)', $sheet->getCell('D33')->getValue());
        $this->assertSame('Activity sortir dari PPIC Body', $sheet->getCell('E33')->getValue());
        // Catatan global yang tersimpan tetap ada walau angka historis di bawah target sekarang.
        $this->assertSame('Jul 2026', $sheet->getCell('A34')->getValue());
        $this->assertCount(1, $sheet->getChartCollection());
        $this->assertSame('A1:M34', $sheet->getPageSetup()->getPrintArea());

        $path = tempnam(sys_get_temp_dir(), 'omira_chart_test_');
        self::assertNotFalse($path);
        $zip = new ZipArchive();
        try {
            $writer = IOFactory::createWriter($book, 'Xlsx');
            $writer->setIncludeCharts(true);
            $writer->save($path);
            self::assertSame(true, $zip->open($path));
            $xml = $zip->getFromName('xl/charts/chart1.xml');
            self::assertIsString($xml);
            self::assertStringContainsString('<c:barChart>', $xml);
            self::assertStringContainsString('<c:lineChart>', $xml);
            self::assertStringContainsString('val="clustered"', $xml);
            self::assertStringContainsString('val="col"', $xml);
            self::assertStringContainsString('val="circle"', $xml);
            self::assertSame(4, substr_count($xml, '<c:ser>'));
            self::assertStringContainsString("'Rekap Tahunan'!\$B\$4:\$M\$4", $xml);
            foreach ([6, 7, 8, 5] as $row) {
                self::assertStringContainsString(sprintf("'Rekap Tahunan'!\$B\$%d:\$M\$%d", $row, $row), $xml);
            }
            foreach (['578EF5', '76C457', 'D39A53', 'D75B62'] as $color) {
                self::assertStringContainsString($color, $xml);
            }
            foreach (['NG', 'OK', 'Scrap', 'Target FY'] as $label) {
                self::assertStringContainsString($label, $xml);
            }
            self::assertStringContainsString('Grafik Order Repair Box FY 2026', $xml);
            self::assertStringContainsString('Jumlah Box', $xml);
            // FY ditulis sebagai judul sumbu X, bukan hanya bagian dari judul grafik.
            self::assertStringContainsString('<a:t>FY 2026</a:t>', $xml);
            self::assertFalse($zip->locateName('xl/media/image1.png'));
            $zip->close();

            // Nilai tabel yang diubah tetap tersimpan; chart tetap menunjuk sel yang sama.
            $sheet->setCellValue('C6', 1500);
            $writer->save($path);
            $reader = new XlsxReader();
            $reader->setIncludeCharts(true);
            $reopened = $reader->load($path);
            try {
                self::assertCount(1, $reopened->getAllSheets());
                $newSheet = $reopened->getActiveSheet();
                self::assertSame(1500, $newSheet->getCell('C6')->getValue());
                self::assertCount(1, $newSheet->getChartCollection());
                self::assertSame('=IF(ISNUMBER(C6),C6,"")', $newSheet->getCell('B33')->getValue());
            } finally {
                $reopened->disconnectWorksheets();
            }
        } finally {
            if ($zip->filename !== '') {
                $zip->close();
            }
            @unlink($path);
            $book->disconnectWorksheets();
        }
    }

    public function test_empty_fiscal_year_creates_valid_chart_and_blank_abnormality_rows(): void
    {
        $book = $this->buildBook($this->months(true));
        try {
            $sheet = $book->getActiveSheet();
            $this->assertNull($sheet->getCell('B6')->getValue());
            $this->assertSame(855, $sheet->getCell('B5')->getValue());
            $this->assertCount(1, $sheet->getChartCollection());
            $this->assertSame('A1:M35', $sheet->getPageSetup()->getPrintArea());
            $this->assertNull($sheet->getCell('A33')->getValue());
            $this->assertNull($sheet->getCell('A35')->getValue());
            $path = tempnam(sys_get_temp_dir(), 'omira_empty_fy_');
            self::assertNotFalse($path);
            try {
                $writer = IOFactory::createWriter($book, 'Xlsx');
                $writer->setIncludeCharts(true);
                $writer->save($path);
                $reader = new XlsxReader();
                $reader->setIncludeCharts(true);
                $reopened = $reader->load($path);
                try {
                    $this->assertCount(1, $reopened->getAllSheets());
                    $this->assertCount(1, $reopened->getActiveSheet()->getChartCollection());
                    $this->assertNull($reopened->getActiveSheet()->getCell('B6')->getValue());
                } finally {
                    $reopened->disconnectWorksheets();
                }
            } finally {
                @unlink($path);
            }
        } finally {
            $book->disconnectWorksheets();
        }
    }

    public function test_fiscal_year_axis_title_is_dynamic(): void
    {
        $book = $this->buildBook($this->months(), [], 2027);
        $path = tempnam(sys_get_temp_dir(), 'omira_fy_axis_');
        self::assertNotFalse($path);
        try {
            $writer = IOFactory::createWriter($book, 'Xlsx');
            $writer->setIncludeCharts(true);
            $writer->save($path);
            $archive = new ZipArchive();
            try {
                self::assertTrue($archive->open($path) === true);
                $xml = $archive->getFromName('xl/charts/chart1.xml');
                self::assertIsString($xml);
                self::assertStringContainsString('<a:t>FY 2027</a:t>', $xml);
                self::assertStringContainsString('Grafik Order Repair Box FY 2027', $xml);
            } finally {
                $archive->close();
            }
        } finally {
            @unlink($path);
            $book->disconnectWorksheets();
        }
    }

    public function test_line_specific_export_does_not_reuse_global_dashboard_notes(): void
    {
        $controller = new RecapController();
        $this->assertSame([], $this->invoke($controller, 'annualAbnormalityReasons', 2026, false));
    }
}
