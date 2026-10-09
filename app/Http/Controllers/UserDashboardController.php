<?php

namespace App\Http\Controllers;

use App\Models\RepairOrder;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $userId = $user->id;

        $base = RepairOrder::query()
            ->where('user_id', $userId);

        $orderActive = (clone $base)
            ->where('status', '!=', 'confirmed')
            ->count();

        $processedOmd = (clone $base)
            ->whereIn('status', ['submitted', 'in_repair', 'revision_requested'])
            ->count();

        $needConfirmation = (clone $base)
            ->where('status', 'completed')
            ->count();

        $completedThisMonth = (clone $base)
            ->where('status', 'confirmed')
            ->whereHas('confirmation', function ($query) {
                $query
                    ->whereYear('confirmed_at', now()->year)
                    ->whereMonth('confirmed_at', now()->month);
            })
            ->count();

        $orderRelations = [
            'line',
            'items.product',
            'items.masterModel',
            'product',
            'masterModel',
        ];

        $actionOrders = (clone $base)
            ->where('status', 'completed')
            ->with($orderRelations)
            ->withCount('feedbacks')
            ->withSum('items as before_qty_sum', 'before_qty')
            ->withSum('items as after_qty_sum', 'after_qty')
            ->latest('repair_completed_at')
            ->latest('id')
            ->limit(5)
            ->get();

        $recentOrders = (clone $base)
            ->with($orderRelations)
            ->withSum('items as before_qty_sum', 'before_qty')
            ->withSum('items as after_qty_sum', 'after_qty')
            ->latest('created_at')
            ->limit(6)
            ->get();

        return view('user.dashboard', compact(
            'orderActive',
            'processedOmd',
            'needConfirmation',
            'completedThisMonth',
            'actionOrders',
            'recentOrders'
        ));
    }
}
