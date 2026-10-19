<?php

namespace App\Http\Controllers;

use App\Models\AdditionalBoxRequest;
use App\Models\RepairOrder;
use App\Models\UserScrapLimitCycle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ActivityVersionController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();
        abort_unless(in_array($user->role, ['omd', 'user'], true), 403);

        $orders = RepairOrder::query();
        if ($user->role === 'user') {
            $orders->where('user_id', $user->id);
        }

        $orderState = $orders->selectRaw(
            'COUNT(*) as total, MAX(updated_at) as latest, '
            . 'SUM(CASE WHEN status = ? THEN id ELSE 0 END) as submitted_ids, '
            . 'SUM(CASE WHEN status = ? THEN id ELSE 0 END) as in_repair_ids, '
            . 'SUM(CASE WHEN status = ? THEN id ELSE 0 END) as completed_ids, '
            . 'SUM(CASE WHEN status = ? THEN id ELSE 0 END) as revision_ids, '
            . 'SUM(CASE WHEN status = ? THEN id ELSE 0 END) as confirmed_ids, '
            . 'SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as awaiting_confirmation',
            ['submitted', 'in_repair', 'completed', 'revision_requested', 'confirmed', 'completed']
        )->first();

        $state = ['orders' => $orderState?->toArray()];

        if ($user->role === 'user') {

            $state['additional_box'] = AdditionalBoxRequest::query()
                ->where('user_id', $user->id)
                ->selectRaw('COUNT(*) as total, MAX(updated_at) as latest')
                ->first()?->toArray();
            $state['scrap_cycles'] = UserScrapLimitCycle::query()
                ->where('user_id', $user->id)
                ->selectRaw('COUNT(*) as total, MAX(updated_at) as latest, '
                    . 'SUM(CASE WHEN reset_at IS NULL AND reached_at IS NOT NULL THEN 1 ELSE 0 END) as reached')
                ->first()?->toArray();
        }

        return response()->json([
            'version' => hash('sha256', json_encode($state, JSON_THROW_ON_ERROR)),
        ])->header('Cache-Control', 'private, no-store, max-age=0');
    }
}
