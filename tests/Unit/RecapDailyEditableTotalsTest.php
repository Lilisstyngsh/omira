<?php

namespace Tests\Unit;

use App\Http\Controllers\RecapController;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class RecapDailyEditableTotalsTest extends TestCase
{
    private function exportTemplate(Collection $rows, Carbon $start, Carbon $end): Spreadsheet
    {
        // Muat template asli yang sama dengan export bulanan dan rentang tanggal.
        $path = dirname(__DIR__, 2) . '/resources/templates/DATA_REKAP_BULANAN_OMD.xlsx';
        $book = IOFactory::load($path);
        $dates = [];
        for ($day = $start->copy(); $day->lte($end); $day->addDay()) {
            $dates[] = [
                'key' => $day->toDateString(),
                'day' => $day->day,
                'header' => $day->format('d/m'),
            ];
        }
        (new ReflectionMethod(RecapController::class, 'fillDailyTemplateSheet'))
            ->invoke(new RecapController(), $book->getActiveSheet(), $rows, $dates, $start, $end);
        return $book;
    }

    public function test_monthly_totals_and_grand_totals_recalculate_when_detail_cells_change(): void
    {
        $start = Carbon::parse('2026-10-01');
        $end = Carbon::parse('2026-10-02');
        $rows = collect([
            [
                'plant' => 'Body', 'line' => 'PPIC Body', 'model' => '660', 'produk' => 'Handle',
                'daily' => [
                    '2026-10-01' => ['order' => 3, 'ok' => 2, 'scrap' => 1],
                    '2026-10-02' => ['order' => 2, 'ok' => 2, 'scrap' => 0],
                ],
            ],
            [
                'plant' => 'Body', 'line' => 'PPIC Body', 'model' => '660', 'produk' => 'Cap',
                'daily' => ['2026-10-01' => ['order' => 5, 'ok' => 4, 'scrap' => 1]],
            ],
        ]);
        $book = $this->exportTemplate($rows, $start, $end);
        try {
            $sheet = $book->getActiveSheet();
            // Dua produk x 3 baris: baris rinci 6..11, footer 12..14.
            $this->assertSame('=SUMIF($F$6:$F$11,$F$12,G$6:G$11)', $sheet->getCell('G12')->getValue());
            $this->assertSame('=SUMIF($F$6:$F$11,$F$13,G$6:G$11)', $sheet->getCell('G13')->getValue());
            $this->assertSame('=SUMIF($F$6:$F$11,$F$14,G$6:G$11)', $sheet->getCell('G14')->getValue());
            $this->assertSame('=SUMIF($F$6:$F$11,$F$12,H$6:H$11)', $sheet->getCell('H12')->getValue());
            $this->assertSame('=SUM(G12:H12)', $sheet->getCell('AL12')->getValue());
            $this->assertSame('=SUM(G13:H13)', $sheet->getCell('AL13')->getValue());
            $this->assertSame('=SUM(G14:H14)', $sheet->getCell('AL14')->getValue());
            $this->assertEquals(8, $sheet->getCell('G12')->getCalculatedValue());
            $this->assertEquals(6, $sheet->getCell('G13')->getCalculatedValue());
            $this->assertEquals(2, $sheet->getCell('G14')->getCalculatedValue());
            $this->assertEquals(10, $sheet->getCell('AL12')->getCalculatedValue());

            // Mengedit rinci NG tanpa mengubah data database mengubah TOTAL dan GRAND TOTAL.
            $sheet->setCellValue('G6', 13);
            $this->assertEquals(18, $sheet->getCell('G12')->getCalculatedValue());
            $this->assertEquals(20, $sheet->getCell('AL12')->getCalculatedValue());
            $sheet->setCellValue('H11', 7); // Semula kosong, kini OMD mengisi Scrap.
            $this->assertEquals(7, $sheet->getCell('H14')->getCalculatedValue());
            $this->assertEquals(9, $sheet->getCell('AL14')->getCalculatedValue());

            $this->assertSame('Repair Box Workshop OMD', $sheet->getCell('B1')->getValue());
            $this->assertContains('B1:AM1', $sheet->getMergeCells());
            $this->assertTrue($sheet->getStyle('B1')->getFont()->getBold());
            $this->assertSame(18.0, (float) $sheet->getStyle('B1')->getFont()->getSize());
            $this->assertSame(Alignment::HORIZONTAL_CENTER, $sheet->getStyle('B1')->getAlignment()->getHorizontal());
            $this->assertSame('B1:AM14', $sheet->getPageSetup()->getPrintArea());

            $tmp = tempnam(sys_get_temp_dir(), 'omira_recap_editable_');
            self::assertNotFalse($tmp);
            try {
                IOFactory::createWriter($book, 'Xlsx')->save($tmp);
                $reopened = (new XlsxReader())->load($tmp);
                try {
                    $reloadedSheet = $reopened->getActiveSheet();
                    $this->assertSame('=SUMIF($F$6:$F$11,$F$12,G$6:G$11)', $reloadedSheet->getCell('G12')->getValue());
                    $this->assertSame('=SUM(G12:H12)', $reloadedSheet->getCell('AL12')->getValue());
                    $this->assertSame(13, $reloadedSheet->getCell('G6')->getValue());
                    $this->assertSame('Repair Box Workshop OMD', $reloadedSheet->getCell('B1')->getValue());
                } finally {
                    $reopened->disconnectWorksheets();
                }
            } finally {
                @unlink($tmp);
            }
        } finally {
            $book->disconnectWorksheets();
        }
    }

    public function test_custom_range_over_31_days_keeps_dynamic_formulas_and_title_merge(): void
    {
        $start = Carbon::parse('2026-01-15');
        $end = Carbon::parse('2026-02-16'); // 33 tanggal: dua kolom ekstra.
        $rows = collect([[ 
            'plant' => 'Unit', 'line' => 'PPIC Unit', 'model' => 'A', 'produk' => 'Part',
            'daily' => [
                '2026-01-15' => ['order' => 1, 'ok' => 1, 'scrap' => 0],
                '2026-02-16' => ['order' => 2, 'ok' => 1, 'scrap' => 1],
            ],
        ]]);
        $book = $this->exportTemplate($rows, $start, $end);
        try {
            $sheet = $book->getActiveSheet();
            $this->assertSame('=SUMIF($F$6:$F$8,$F$9,G$6:G$8)', $sheet->getCell('G9')->getValue());
            $this->assertSame('=SUMIF($F$6:$F$8,$F$9,AM$6:AM$8)', $sheet->getCell('AM9')->getValue());
            $this->assertSame('=SUM(G9:AM9)', $sheet->getCell('AN9')->getValue());
            $this->assertEquals(3, $sheet->getCell('AN9')->getCalculatedValue());
            $this->assertContains('B1:AO1', $sheet->getMergeCells());
            $this->assertSame('B1:AO11', $sheet->getPageSetup()->getPrintArea());
        } finally {
            $book->disconnectWorksheets();
        }
    }

    public function test_empty_period_still_has_editable_total_formulas(): void
    {
        $start = Carbon::parse('2026-09-01');
        $book = $this->exportTemplate(collect(), $start, $start);
        try {
            $sheet = $book->getActiveSheet();
            $this->assertSame('=SUMIF($F$6:$F$8,$F$9,G$6:G$8)', $sheet->getCell('G9')->getValue());
            $this->assertSame('=SUM(G9:G9)', $sheet->getCell('AL9')->getValue());
            $this->assertEquals(0, $sheet->getCell('G9')->getCalculatedValue());
            $sheet->setCellValue('G6', 4);
            $this->assertEquals(4, $sheet->getCell('G9')->getCalculatedValue());
        } finally {
            $book->disconnectWorksheets();
        }
    }
}
