<?php

namespace App\Http\Controllers;

use App\Models\RepairOrder;
use App\Models\UserScrapLimitCycle;
use App\Models\RepairOrderItem;
use App\Services\UserScrapLimitService;
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
            ->limit(5)
            ->get();

        // Include legacy confirmed Scrap rows, not only orders confirmed after rollout.
        $scrapProductIds = RepairOrderItem::query()
            ->whereHas('repairOrder', fn ($q) => $q->where('user_id', $userId)
                ->where('status', 'confirmed'))
            ->whereHas('ngType', fn ($q) => $q->where('code', 'S'))
            ->where('after_qty', '>', 0)
            ->get(['product_id', 'after_product_id'])
            ->map(fn ($item) => (int) ($item->after_product_id ?: $item->product_id))
            ->filter()->unique();
        $limitService = app(UserScrapLimitService::class);
        foreach ($scrapProductIds as $productId) {
            $limitService->sync($user, $productId);
        }

        $scrapAlerts = UserScrapLimitCycle::query()->where('user_id', $userId)
            ->whereNull('reset_at')
            ->with('product.masterModel.line')->latest('id')->get()
            ->map(function ($cycle) use ($user, $limitService) {
                $cycle->used_qty = $limitService->quantity($user, $cycle->product_id, $cycle);
                $cycle->remaining_qty = max(0, $cycle->limit_qty - $cycle->used_qty);
                return $cycle;
            })->filter(fn ($cycle) => $cycle->used_qty >= $cycle->limit_qty);

        $scrapLimitSummary = $scrapAlerts->map(function ($cycle) {
            return [
                'id' => $cycle->id,
                'product_id' => $cycle->product_id,
                'product' => $cycle->product?->name,
                'model' => $cycle->product?->masterModel?->model,
                'used_qty' => $cycle->used_qty,
                'limit_qty' => $cycle->limit_qty,
            ];
        })->values();

        return view('user.dashboard', compact(
            'orderActive',
            'processedOmd',
            'needConfirmation',
            'completedThisMonth',
            'actionOrders',
            'recentOrders',
            'scrapAlerts',
            'scrapLimitSummary'
        ));
    }
}
