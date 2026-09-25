<?php

namespace App\Http\Controllers;

use App\Models\RepairOrder;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function index(Request $request)
    {
        $base = RepairOrder::where('user_id', $request->user()->id);
        $total = (clone $base)->count();
        $submitted = (clone $base)->where('status', 'submitted')->count();
        $inRepair = (clone $base)->where('status', 'in_repair')->count();
        $completed = (clone $base)->whereIn('status', ['completed', 'confirmed'])->count();
        $recentOrders = (clone $base)->with(['area', 'product'])->latest()->limit(6)->get();
        return view('user.dashboard', compact('total', 'submitted', 'inRepair', 'completed', 'recentOrders'));
    }
}
