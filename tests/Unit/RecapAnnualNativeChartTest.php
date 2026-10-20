<?php

namespace Tests\Unit;

use App\Http\Controllers\RecapController;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use ZipArchive;

class RecapAnnualNativeChartTest extends TestCase
{
    public function test_native_combo_chart_uses_monthly_cells_and_is_serialized_into_xlsx(): void
    {
        $book = new Spreadsheet();
        $sheet = $book->getActiveSheet();
        $sheet->setTitle('Monitoring');
        $sheet->fromArray(['Bulan', 'NG', 'OK', 'Scrap', 'Target FY'], null, 'A6');
        foreach (['Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des', 'Jan', 'Feb', 'Mar'] as $index => $month) {
            $row = $index + 7;
            $sheet->fromArray([$month, $index * 10, $index * 8, $index * 2, 855], null, 'A' . $row);
        }
        // Future month with no actuals should not invalidate the chart.
        $sheet->setCellValue('B18', null);
        $sheet->setCellValue('C18', null);
        $sheet->setCellValue('D18', null);

        $method = new ReflectionMethod(RecapController::class, 'addAnnualNativeChart');
        $method->invoke(new RecapController(), $sheet, 2026, 7, 18, 22);
        $this->assertCount(1, $sheet->getChartCollection());

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
            self::assertStringContainsString('val="1"', $xml);
            self::assertSame(4, substr_count($xml, '<c:ser>'));
            foreach (['B', 'C', 'D', 'E'] as $column) {
                self::assertStringContainsString(sprintf("'Monitoring'!\$%s\$7:\$%s\$18", $column, $column), $xml);
            }
            foreach (['F59E0B', '94A3B8', 'FACC15', '2563EB'] as $color) {
                self::assertStringContainsString($color, $xml);
            }
            self::assertStringContainsString('Grafik Order Repair Box FY 2026', $xml);
            self::assertStringContainsString('Jumlah Box', $xml);
            self::assertStringNotContainsString('data:image/png', $xml);
            self::assertFalse($zip->locateName('xl/media/image1.png'));
            $zip->close();

            // Pastikan referensi chart tetap sama setelah sel sumber diedit dan disimpan ulang.
            $sheet->setCellValue('B7', 999);
            $writer->save($path);
            self::assertSame(true, $zip->open($path));
            $updatedChartXml = $zip->getFromName('xl/charts/chart1.xml');
            self::assertIsString($updatedChartXml);
            self::assertStringContainsString("'Monitoring'!\$B\$7:\$B\$18", $updatedChartXml);
            $zip->close();

            $reader = new XlsxReader();
            $reader->setIncludeCharts(true);
            $reopened = $reader->load($path);
            try {
                self::assertCount(1, $reopened->getSheetByName('Monitoring')->getChartCollection());
                self::assertSame(999, $reopened->getSheetByName('Monitoring')->getCell('B7')->getValue());
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
}
