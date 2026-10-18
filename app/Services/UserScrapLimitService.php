<?php
namespace App\Services;

use App\Models\ProductScrapLimit;
use App\Models\RepairOrderItem;
use App\Models\User;
use App\Models\UserScrapLimitCycle;
use Illuminate\Support\Facades\DB;

class UserScrapLimitService
{
    public function masterLimit(int $productId): ?ProductScrapLimit
    {
        return ProductScrapLimit::query()->where('product_id', $productId)
            ->activeAt(now())->orderByDesc('effective_from')->orderByDesc('id')->first();
    }

    public function cycle(User $user, int $productId): ?UserScrapLimitCycle
    {
        return UserScrapLimitCycle::query()->where('user_id', $user->id)
            ->where('product_id', $productId)->whereNull('reset_at')->latest('id')->first();
    }

    public function quantity(User $user, int $productId, ?UserScrapLimitCycle $cycle = null): int
    {
        $since = $cycle?->started_at ?? now()->startOfDay()->subYears(20);
        return (int) RepairOrderItem::query()
            ->where(function ($q) use ($productId) {
                $q->where('after_product_id', $productId)
                    ->orWhere(fn ($sub) => $sub->whereNull('after_product_id')->where('product_id', $productId));
            })->whereHas('ngType', fn ($q) => $q->where('code', 'S'))
            ->whereHas('repairOrder', function ($q) use ($user, $since, $cycle) {
                $q->where('user_id', $user->id)->where('status', 'confirmed')
                    ->whereHas('confirmation', function ($c) use ($since, $cycle) {
                        if ($cycle && $cycle->baseline_confirmation_id !== null) {
                            $c->where('id', '>', $cycle->baseline_confirmation_id);
                        } else {
                            $c->where('confirmed_at', '>=', $since);
                        }
                    });
            })->sum('after_qty');
    }

    // Create or refresh one cycle per user/product under the owner row lock.
    public function sync(User $user, int $productId): ?UserScrapLimitCycle
    {
        return DB::transaction(function () use ($user, $productId) {
            $owner = User::query()->lockForUpdate()->findOrFail($user->id);
            return $this->syncLocked($owner, $productId);
        }, 3);
    }

    private function syncLocked(User $user, int $productId): ?UserScrapLimitCycle
    {
        $cycle = $this->cycle($user, $productId);
        $master = $this->masterLimit($productId);
        if (!$master) return $cycle;
        $qty = $this->quantity($user, $productId, $cycle);
        // Record the incident only when the confirmed Scrap meets or exceeds its master limit.
        // Existing cycles keep their original limit until an automatic or manual reset.
        if (!$cycle && $qty < $master->limit_qty) return null;
        if (!$cycle) {
            $cycle = UserScrapLimitCycle::create([
                'user_id'=>$user->id, 'product_id'=>$productId, 'line_id'=>$user->line_id,
                'limit_qty'=>$master->limit_qty,
                'started_at'=>now()->startOfDay()->subYears(20),
            ]);
        }
        if (!$cycle->reached_at && $qty >= $cycle->limit_qty) {
            $cycle->update(['reached_at'=>now(), 'reached_qty'=>$qty]);
        }
        return $cycle->fresh();
    }

    /** Quantity held by unconfirmed orders; counted once per order item. */
    public function reserved(User $user, int $productId, ?UserScrapLimitCycle $cycle = null, ?int $excludeOrderId = null): int
    {
        $since = $cycle?->started_at ?? now()->startOfDay()->subYears(20);
        $query = RepairOrderItem::query()
            ->where('product_id', $productId)
            ->whereHas('ngType', fn ($q) => $q->where('code', 'S'))
            ->with('repairOrder')->whereHas('repairOrder', function ($q) use ($user, $since, $excludeOrderId) {
                $q->where('user_id', $user->id)
                    ->whereIn('status', ['submitted', 'in_repair', 'completed', 'revision_requested'])
                    ->where('created_at', '>=', $since);
                if ($excludeOrderId !== null) $q->where('id', '!=', $excludeOrderId);
            });
        // If OMD has completed a repair, its result replaces the submitted reservation.
        return (int) $query->get()->sum(function ($item) {
            return in_array($item->repairOrder->status, ['completed', 'revision_requested'], true)
                ? (int) $item->after_qty
                : (int) $item->before_qty;
        });
    }

    /** Remaining quota, including reservations made by other active orders. */
    public function remaining(User $user, int $productId, ?int $excludeOrderId = null): ?int
    {
        $cycle = $this->cycle($user, $productId);
        $master = $this->masterLimit($productId);
        $limit = $cycle ? (int) $cycle->limit_qty : ($master ? (int) $master->limit_qty : null);
        if ($limit === null) return null;
        return max(0, $limit - $this->quantity($user, $productId, $cycle)
            - $this->reserved($user, $productId, $cycle, $excludeOrderId));
    }

    /** Scrap limits are monitoring only; repair orders are never blocked by quota. */
    public function isBlocked(User $user, int $productId): bool
    {
        return false;
    }

    public function reset(UserScrapLimitCycle $cycle, User $actor, string $reason): void
    {
        DB::transaction(function () use ($cycle, $actor, $reason) {
            User::query()->lockForUpdate()->findOrFail($cycle->user_id);
            $cycle = UserScrapLimitCycle::query()->lockForUpdate()->findOrFail($cycle->id);
            abort_unless($actor->role === 'omd' || (int) $actor->id === (int) $cycle->user_id, 403);
            if ($cycle->reset_at) return;
            $master = $this->masterLimit($cycle->product_id);
            $cutoff = (int) \App\Models\RepairConfirmation::query()
                ->whereIn('repair_order_id', \App\Models\RepairOrder::where('user_id', $cycle->user_id)->select('id'))->max('id');
            $resetAt = now();
            $cycle->update(['reset_at' => $resetAt, 'reset_by' => $actor->id, 'reset_reason' => $reason]);
            UserScrapLimitCycle::create([
                'user_id' => $cycle->user_id, 'product_id' => $cycle->product_id,
                'line_id' => $cycle->line_id, 'limit_qty' => $master?->limit_qty ?? $cycle->limit_qty,
                'started_at' => $resetAt, 'baseline_confirmation_id' => $cutoff,
            ]);
        }, 3);
    }
}
