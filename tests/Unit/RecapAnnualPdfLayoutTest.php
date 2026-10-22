<?php

namespace Tests\Unit;

use App\Http\Controllers\RecapController;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class RecapAnnualPdfLayoutTest extends TestCase
{
    private function invoke(RecapController $controller, string $name, mixed ...$args): mixed
    {
        return (new ReflectionMethod($controller, $name))->invoke($controller, ...$args);
    }

    private function months(): array
    {
        $labels = ['Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des', 'Jan', 'Feb', 'Mar'];

        return array_map(static fn (string $label, int $index): array => [
            'label' => $label,
            'target' => 855,
            'order' => $index === 2 ? null : 500 + ($index * 10),
            'finish' => $index === 2 ? null : 490 + ($index * 10),
            'scrap' => $index === 2 ? null : 10,
        ], $labels, array_keys($labels));
    }

    public function test_dompdf_chart_is_valid_svg_with_combo_series_and_dynamic_fy_axis(): void
    {
        $controller = new RecapController();
        $svg = $this->invoke($controller, 'buildAnnualChartSvg', $this->months(), 2027);

        $this->assertStringContainsString('<svg xmlns="http://www.w3.org/2000/svg"', $svg);
        $this->assertStringContainsString('Grafik Order Repair Box FY 2027', $svg);
        $this->assertStringContainsString('>FY 2027</text>', $svg);
        $this->assertStringContainsString('Jumlah Box', $svg);
        $this->assertStringContainsString('APR', $svg);
        $this->assertStringContainsString('#578EF5', $svg);
        $this->assertStringContainsString('#76C457', $svg);
        $this->assertStringContainsString('#D39A53', $svg);
        $this->assertStringContainsString('#D75B62', $svg);
        $this->assertStringContainsString('<polyline', $svg);
        $this->assertStringContainsString('<circle', $svg);

        $document = new \DOMDocument();
        $this->assertTrue($document->loadXML($svg));
    }

    public function test_pdf_abnormality_rows_match_excel_threshold_and_reason_rules(): void
    {
        $controller = new RecapController();
        $months = $this->months();
        $months[1]['order'] = 1399;
        $months[1]['finish'] = 1373;
        $months[2]['order'] = null;
        $months[2]['finish'] = null;
        $reasons = [
            '2026-06' => 'Catatan abnormality dari Dashboard',
            '2026-07' => 'Catatan meskipun order kosong',
        ];

        $rows = $this->invoke($controller, 'annualAbnormalityRows', $months, 2026, $reasons);

        $this->assertCount(3, $rows);
        $this->assertSame('Mei 2026', $rows[0]['label']);
        $this->assertSame(1399, $rows[0]['order']);
        $this->assertSame(855, $rows[0]['target']);
        $this->assertSame(544, $rows[0]['difference']);
        $this->assertSame('Catatan abnormality dari Dashboard', $rows[1]['reason']);
        $this->assertNull($rows[1]['order']);
        $this->assertNull($rows[1]['difference']);
        $this->assertSame('Jul 2026', $rows[2]['label']);
        $this->assertSame('Catatan meskipun order kosong', $rows[2]['reason']);
    }
}
