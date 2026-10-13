<?php

namespace Database\Seeders;

use App\Models\FiscalYearTarget;
use App\Models\MonitoringAbnormality;
use App\Models\RepairMonthlySnapshot;
use Illuminate\Database\Seeder;

class RepairMonthlySnapshotSeeder extends Seeder
{
    private const ABNORMALITY_REASON = 'Terdapat activity sortir dari tim PPIC Body dan Unit after claim customer.';

    public function run(): void
    {
        $rows = [
            ['month' => 1, 'order' => 696,  'ok' => 651,  'scrap' => 48],
            ['month' => 2, 'order' => 942,  'ok' => 854,  'scrap' => 83],
            ['month' => 3, 'order' => 433,  'ok' => 411,  'scrap' => 18],
            ['month' => 4, 'order' => 541,  'ok' => 531,  'scrap' => 10],
            ['month' => 5, 'order' => 1399, 'ok' => 1373, 'scrap' => 26],
            ['month' => 6, 'order' => 1865, 'ok' => 1810, 'scrap' => 55],
            ['month' => 7, 'order' => 1459, 'ok' => 1395, 'scrap' => 64],
            ['month' => 8, 'order' => 720,  'ok' => 704,  'scrap' => 16],
            ['month' => 9, 'order' => 992,  'ok' => 953,  'scrap' => 39],
        ];

        foreach ($rows as $row) {
            RepairMonthlySnapshot::query()->updateOrCreate(
                [
                    'year' => 2026,
                    'month' => $row['month'],
                ],
                [
                    'order_qty' => $row['order'],
                    'ok_qty' => $row['ok'],
                    'scrap_qty' => $row['scrap'],
                    'target_qty' => 855,
                    'source' => 'legacy_chart_apr_aug_2026',
                ]
            );
        }

        $scopeKey = FiscalYearTarget::scopeKey(null);
        $target = FiscalYearTarget::query()
            ->where('year', 2026)
            ->where('scope_key', $scopeKey)
            ->first();

        foreach ([5 => 1399, 6 => 1865, 7 => 1559] as $month => $actualQty) {
            MonitoringAbnormality::query()->firstOrCreate(
                [
                    'metric' => MonitoringAbnormality::METRIC_TARGET_FY,
                    'year' => 2026,
                    'month' => $month,
                    'scope_key' => $scopeKey,
                ],
                [
                    'line_id' => null,
                    'fiscal_year_target_id' => $target?->id,
                    'actual_qty' => $actualQty,
                    'threshold_qty' => 855,
                    'reason' => self::ABNORMALITY_REASON,
                    'created_by' => null,
                    'updated_by' => null,
                ]
            );
        }
    }
}
