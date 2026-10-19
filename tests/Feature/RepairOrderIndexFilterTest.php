<?php

namespace Tests\Feature;

use App\Models\Plant;
use App\Models\RepairOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RepairOrderIndexFilterTest extends TestCase
{
    use RefreshDatabase;

    private function makeOrder(User $user, int $lineId, string $number, string $date, string $status = 'submitted'): RepairOrder
    {
        $order = RepairOrder::create([
            'order_number' => $number,
            'user_id' => $user->id,
            'line_id' => $lineId,
            'order_year' => 2026,
            'sequence' => (int) substr($number, -2),
            'order_date' => $date,
            'quantity' => 5,
            'status' => $status,
        ]);
        $order->created_at = $date . ' 09:00:00';
        $order->save();
        return $order;
    }

    public function test_user_index_filters_own_active_orders_by_date_and_number_without_line_filter(): void
    {
        $plant = Plant::create(['name' => 'Plant Repair Filter']);
        $line = $plant->lines()->create(['name' => 'LINE-A']);
        $owner = User::factory()->create(['role' => 'user', 'line_id' => $line->id]);
        $other = User::factory()->create(['role' => 'user', 'line_id' => $line->id]);

        $this->makeOrder($owner, $line->id, 'RB-U-01', '2026-10-18');
        $this->makeOrder($owner, $line->id, 'RB-U-02', '2026-09-18');
        $this->makeOrder($other, $line->id, 'RB-U-03', '2026-10-18');
        $this->makeOrder($owner, $line->id, 'RB-U-04', '2026-10-18', 'confirmed');

        $this->actingAs($owner)->get(route('user.orders.index', [
            'start_date' => '2026-10-01', 'end_date' => '2026-10-31',
            'search' => 'RB-U', 'per_page' => 25,
        ]))->assertOk()
            ->assertSee('RB-U-01')
            ->assertDontSee('RB-U-02')
            ->assertDontSee('RB-U-03')
            ->assertDontSee('RB-U-04')
            ->assertDontSee('name="line_id"', false)
            ->assertViewHas('orders', fn ($orders) => $orders->total() === 1 && $orders->perPage() === 25);
    }

    public function test_omd_index_can_filter_by_line_date_and_user_name(): void
    {
        $plant = Plant::create(['name' => 'Plant OMD Filter']);
        $lineA = $plant->lines()->create(['name' => 'INJ']);
        $lineB = $plant->lines()->create(['name' => 'PT']);
        $userA = User::factory()->create(['name' => 'Ayu Testing', 'role' => 'user', 'line_id' => $lineA->id]);
        $userB = User::factory()->create(['name' => 'Budi Testing', 'role' => 'user', 'line_id' => $lineB->id]);
        $omd = User::factory()->create(['role' => 'omd']);

        $this->makeOrder($userA, $lineA->id, 'RB-O-01', '2026-10-18');
        $this->makeOrder($userB, $lineB->id, 'RB-O-02', '2026-10-18');
        $this->makeOrder($userA, $lineA->id, 'RB-O-03', '2026-09-18');

        $this->actingAs($omd)->get(route('omd.orders.index', [
            'start_date' => '2026-10-01', 'end_date' => '2026-10-31',
            'line_id' => $lineA->id, 'search' => 'Ayu Testing', 'per_page' => 10,
        ]))->assertOk()
            ->assertSee('RB-O-01')
            ->assertDontSee('RB-O-02')
            ->assertDontSee('RB-O-03')
            ->assertSee('name="line_id"', false)
            ->assertViewHas('orders', fn ($orders) => $orders->total() === 1);
    }

    public function test_invalid_date_range_is_rejected_for_both_roles(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $omd = User::factory()->create(['role' => 'omd']);
        $params = ['start_date' => '2026-10-20', 'end_date' => '2026-10-19'];

        $this->actingAs($user)->get(route('user.orders.index', $params))->assertSessionHasErrors('end_date');
        $this->actingAs($omd)->get(route('omd.orders.index', $params))->assertSessionHasErrors('end_date');
    }
}
