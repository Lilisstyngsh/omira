<?php
namespace Tests\Feature;

use App\Models\NgType;
use App\Models\Plant;
use App\Models\RepairOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RepairConfirmationWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private function fixture(): array
    {
        $line = Plant::create(['name' => 'Plant Test'])->lines()->create(['name' => 'Line Test']);
        $user = User::factory()->create(['role' => 'user', 'line_id' => $line->id]);
        $product = $line->masterModels()->create(['model' => 'Test'])->products()->create(['name' => 'Box']);
        $ng = NgType::firstOrCreate(['code' => 'S'], ['name' => 'Scrap']);
        $order = RepairOrder::create(['order_number' => 'TEST-001', 'user_id' => $user->id,
            'line_id' => $line->id, 'order_year' => now()->year, 'sequence' => 1,
            'product_id' => $product->id, 'ng_type_id' => $ng->id, 'order_date' => now(),
            'quantity' => 3, 'status' => 'completed', 'repair_completed_at' => now()]);
        $order->result()->create(['ok_qty' => 2, 'scrap_qty' => 1, 'ng_qty' => 0]);
        return [$user, $order];
    }

    public function test_confirmation_is_atomic_and_repeated_submit_preserves_original_time(): void
    {
        [$user, $order] = $this->fixture();
        $this->actingAs($user)->post(route('user.orders.confirm', $order))->assertRedirect();
        $confirmedAt = $order->fresh()->confirmation->confirmed_at->toDateTimeString();
        $this->travel(5)->minutes();
        $this->post(route('user.orders.confirm', $order))->assertRedirect();
        $this->assertSame('confirmed', $order->fresh()->status);
        $this->assertSame($confirmedAt, $order->fresh()->confirmation->confirmed_at->toDateTimeString());
        $this->assertSame(1, $order->confirmation()->count());
        $this->postJson(route('user.orders.requestRevision', $order), ['reason' => 'Late revision'])->assertUnprocessable();
        $this->travelBack();
    }

    public function test_failed_confirmation_write_does_not_change_order_status(): void
    {
        [$user, $order] = $this->fixture();
        $event = 'eloquent.creating: App\\Models\\RepairConfirmation';
        Event::listen($event, function () { throw new \RuntimeException('Simulated storage failure'); });
        $this->withoutExceptionHandling();
        try {
            $this->actingAs($user)->post(route('user.orders.confirm', $order));
            $this->fail('Expected storage exception.');
        } catch (\RuntimeException $exception) {
            $this->assertSame('Simulated storage failure', $exception->getMessage());
            $this->assertSame('completed', $order->fresh()->status);
            $this->assertFalse($order->confirmation()->exists());
        } finally {
            Event::forget($event);
        }
    }

    public function test_handover_is_idempotent_and_does_not_confirm_for_the_user(): void
    {
        [, $order] = $this->fixture();
        $omd = User::factory()->create(['role' => 'omd']);
        $this->actingAs($omd)->post(route('omd.orders.handover', $order))->assertRedirect();
        $first = $order->fresh()->handed_over_at->toDateTimeString();
        $this->travel(5)->minutes();
        $this->post(route('omd.orders.handover', $order))->assertRedirect();
        $this->assertSame($first, $order->fresh()->handed_over_at->toDateTimeString());
        $this->assertSame('completed', $order->fresh()->status);
        $this->assertFalse($order->confirmation()->exists());
        $this->travelBack();
    }
}
