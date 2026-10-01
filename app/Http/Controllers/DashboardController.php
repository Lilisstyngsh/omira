<?php

namespace App\Http\Controllers;

use App\Models\RepairOrderItem;
use App\Models\RepairResult;
use App\Models\Target;
use App\Models\Line;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | FILTER
        |--------------------------------------------------------------------------
        | $year dianggap sebagai Fiscal Year.
        |
        | Contoh:
        | FY 2026 = Apr 2026 s.d. Mar 2027
        |--------------------------------------------------------------------------
        */

        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);
        $lineId = $request->input('line_id');
        $lines = Line::query()
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | VALIDASI FILTER
        |--------------------------------------------------------------------------
        */

        if ($month < 1 || $month > 12) {
            $month = now()->month;
        }

        /*
        |--------------------------------------------------------------------------
        | TENTUKAN TAHUN DATA BERDASARKAN FY
        |--------------------------------------------------------------------------
        |
        | FY 2026:
        | Apr 2026
        | Mei 2026
        | ...
        | Des 2026
        | Jan 2027
        | Feb 2027
        | Mar 2027
        |
        |--------------------------------------------------------------------------
        */

        $selectedDataYear = in_array($month, [1, 2, 3], true)
            ? $year + 1
            : $year;

        /*
        |--------------------------------------------------------------------------
        | ORDER BULAN TERPILIH
        |--------------------------------------------------------------------------
        | ORDER = GRAND TOTAL before_qty
        | BUKAN jumlah order/tiket.
        |--------------------------------------------------------------------------
        */

        $orderBase = RepairOrderItem::query()
            ->whereHas('repairOrder', function ($query) use (
                $selectedDataYear,
                $month,
                $lineId
            ) {
                $query
                    ->whereYear('order_date', $selectedDataYear)
                    ->whereMonth('order_date', $month)
                    ->whereIn('status', [
                        'submitted',
                        'in_repair',
                        'completed',
                        'confirmed',
                    ])
                    ->when(
                        $lineId,
                        fn($q) => $q->where('line_id', $lineId)
                    );
            });

        $totalOrders = (int) $orderBase->sum('before_qty');

        /*
        |--------------------------------------------------------------------------
        | FINISH BULAN TERPILIH
        |--------------------------------------------------------------------------
        | FINISH = total OK dari hasil repair OMD.
        |--------------------------------------------------------------------------
        */

        $resultBase = RepairResult::query()
            ->whereHas('order', function ($query) use (
                $selectedDataYear,
                $month,
                $lineId
            ) {
                $query
                    ->whereYear('order_date', $selectedDataYear)
                    ->whereMonth('order_date', $month)
                    ->whereIn('status', [
                        'completed',
                        'confirmed',
                    ])
                    ->when(
                        $lineId,
                        fn($q) => $q->where('line_id', $lineId)
                    );
            });

        $finishedOrders = (int) (clone $resultBase)
            ->sum('ok_qty');

        /*
        |--------------------------------------------------------------------------
        | SCRAP BULAN TERPILIH
        |--------------------------------------------------------------------------
        */

        $scrap = (int) (clone $resultBase)
            ->sum('scrap_qty');

        /*
        |--------------------------------------------------------------------------
        | TARGET BULAN TERPILIH
        |--------------------------------------------------------------------------
        |
        | Target diambil dari tabel targets.
        | $year dianggap sebagai Fiscal Year.
        |--------------------------------------------------------------------------
        */

        $targetData = Target::query()
            ->where('year', $year)
            ->where('month', $month)
            ->when(
                $lineId,
                fn($q) => $q->where('line_id', $lineId)
            )
            ->first();

        $target = (int) ($targetData?->target_qty ?? 0);

        $scrapLimit = (int) ($targetData?->scrap_limit ?? 20);

        /*
        |--------------------------------------------------------------------------
        | WARNING SCRAP
        |--------------------------------------------------------------------------
        */

        $scrapWarning = $scrap >= $scrapLimit;

        /*
        |--------------------------------------------------------------------------
        | SERIES GRAFIK
        |--------------------------------------------------------------------------
        |
        | Urutan FY:
        |
        | Apr
        | Mei
        | Jun
        | Jul
        | Agu
        | Sep
        | Okt
        | Nov
        | Des
        | Jan
        | Feb
        | Mar
        |
        |--------------------------------------------------------------------------
        */

        $fiscalMonths = [
            4,
            5,
            6,
            7,
            8,
            9,
            10,
            11,
            12,
            1,
            2,
            3,
        ];

        $months = [];
        $orderSeries = [];
        $finishSeries = [];
        $scrapSeries = [];
        $targetSeries = [];
        $scrapLimitSeries = [];

        foreach ($fiscalMonths as $calendarMonth) {

            /*
            |--------------------------------------------------------------------------
            | TAHUN DATA
            |--------------------------------------------------------------------------
            */

            $dataYear = in_array(
                $calendarMonth,
                [1, 2, 3],
                true
            )
                ? $year + 1
                : $year;

            /*
            |--------------------------------------------------------------------------
            | LABEL BULAN
            |--------------------------------------------------------------------------
            */

            $months[] = Carbon::create(
                $dataYear,
                $calendarMonth,
                1
            )->translatedFormat('M');

            /*
            |--------------------------------------------------------------------------
            | ORDER SERIES
            |--------------------------------------------------------------------------
            */

            $monthlyOrder = RepairOrderItem::query()
                ->whereHas('repairOrder', function ($query) use (
                    $dataYear,
                    $calendarMonth,
                    $lineId
                ) {
                    $query
                        ->whereYear('order_date', $dataYear)
                        ->whereMonth('order_date', $calendarMonth)
                        ->whereIn('status', [
                            'submitted',
                            'in_repair',
                            'completed',
                            'confirmed',
                        ])
                        ->when(
                            $lineId,
                            fn($q) => $q->where('line_id', $lineId)
                        );
                })
                ->sum('before_qty');

            $orderSeries[] = (int) $monthlyOrder;

            /*
            |--------------------------------------------------------------------------
            | FINISH SERIES
            |--------------------------------------------------------------------------
            */

            $monthlyFinish = RepairResult::query()
                ->whereHas('order', function ($query) use (
                    $dataYear,
                    $calendarMonth,
                    $lineId
                ) {
                    $query
                        ->whereYear('order_date', $dataYear)
                        ->whereMonth('order_date', $calendarMonth)
                        ->whereIn('status', [
                            'completed',
                            'confirmed',
                        ])
                        ->when(
                            $lineId,
                            fn($q) => $q->where('line_id', $lineId)
                        );
                })
                ->sum('ok_qty');

            $finishSeries[] = (int) $monthlyFinish;

            /*
            |--------------------------------------------------------------------------
            | SCRAP SERIES
            |--------------------------------------------------------------------------
            */

            $monthlyScrap = RepairResult::query()
                ->whereHas('order', function ($query) use (
                    $dataYear,
                    $calendarMonth,
                    $lineId
                ) {
                    $query
                        ->whereYear('order_date', $dataYear)
                        ->whereMonth('order_date', $calendarMonth)
                        ->whereIn('status', [
                            'completed',
                            'confirmed',
                        ])
                        ->when(
                            $lineId,
                            fn($q) => $q->where('line_id', $lineId)
                        );
                })
                ->sum('scrap_qty');

            $scrapSeries[] = (int) $monthlyScrap;

            /*
            |--------------------------------------------------------------------------
            | TARGET SERIES
            |--------------------------------------------------------------------------
            |
            | Target berdasarkan FY + bulan + Line.
            |--------------------------------------------------------------------------
            */

            $monthlyTargetData = Target::query()
                ->where('year', $year)
                ->where('month', $calendarMonth)
                ->when(
                    $lineId,
                    fn($q) => $q->where('line_id', $lineId)
                )
                ->first();

            $targetSeries[] = (int) (
                $monthlyTargetData?->target_qty ?? 0
            );

            $scrapLimitSeries[] = (int) (
                $monthlyTargetData?->scrap_limit ?? 20
            );
        }

        /*
        |--------------------------------------------------------------------------
        | KIRIM DATA KE VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'omd.dashboard.index',
            compact(
                'month',
                'year',
                'lineId',
                'lines',
                'totalOrders',
                'finishedOrders',
                'scrap',
                'target',
                'scrapLimit',
                'scrapWarning',
                'months',
                'orderSeries',
                'finishSeries',
                'scrapSeries',
                'targetSeries',
                'scrapLimitSeries'
            )
        );
    }
}
