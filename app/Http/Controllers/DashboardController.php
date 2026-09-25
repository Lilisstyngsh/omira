<?php

namespace App\Http\Controllers;

use App\Models\RepairOrder;
use App\Models\RepairResult;
use App\Models\Target;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);
        $areaId = $request->input('area_id');

        $base = RepairOrder::query()->whereYear('order_date', $year)->whereMonth('order_date', $month);
        if ($areaId) $base->where('area_id', $areaId);

        $totalOrders = (clone $base)->count();
        $finishedOrders = (clone $base)->whereIn('status', ['completed', 'confirmed'])->count();
        $scrap = RepairResult::whereHas('order', fn($q) => $q->whereYear('order_date', $year)->whereMonth('order_date', $month)->when($areaId, fn($qq) => $qq->where('area_id', $areaId)))->sum('scrap_qty');
        $target = Target::where('year', $year)->where('month', $month)->when($areaId, fn($q) => $q->where('area_id', $areaId))->sum('target_qty');

        $months = [];
        $orderSeries = [];
        $finishSeries = [];
        $scrapSeries = [];
        for ($m = 1; $m <= 12; $m++) {
            $q = RepairOrder::query()->whereYear('order_date', $year)->whereMonth('order_date', $m)->when($areaId, fn($qq) => $qq->where('area_id', $areaId));
            $months[] = Carbon::create($year, $m, 1)->format('M');
            $orderSeries[] = (clone $q)->count();
            $finishSeries[] = (clone $q)->whereIn('status', ['completed', 'confirmed'])->count();
            $scrapSeries[] = RepairResult::whereHas('order', fn($oq) => $oq->whereYear('order_date', $year)->whereMonth('order_date', $m)->when($areaId, fn($qq) => $qq->where('area_id', $areaId)))->sum('scrap_qty');
        }

        return view('omd.dashboard.index', compact('month', 'year', 'areaId', 'totalOrders', 'finishedOrders', 'scrap', 'target', 'months', 'orderSeries', 'finishSeries', 'scrapSeries'));
    }
}
