<?php

namespace Tests\Feature;

use App\Models\AdditionalBoxRequest;
use App\Models\NgType;
use App\Models\Plant;
use App\Models\RepairOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserDashboardLiveRefreshTest extends TestCase
{
    use RefreshDatabase;

    private function createOrder(User $user, int $sequence, string $status): RepairOrder
    {
        $line = $user->line;
        $model = $line->masterModels()->firstOrCreate(['model' => 'Model Live']);
        $product = $model->products()->firstOrCreate(['name' => 'Box Live']);
        $ngType = NgType::firstOrCreate(['code' => 'P'], ['name' => 'NG Part']);

        return RepairOrder::create([
            'order_number' => "LIVE-{$user->id}-{$sequence}",
            'user_id' => $user->id,
            'line_id' => $line->id,
            'order_year' => now()->year,
            'sequence' => $sequence,
            'master_model_id' => $model->id,
            'product_id' => $product->id,
            'ng_type_id' => $ngType->id,
            'order_date' => now()->toDateString(),
            'quantity' => 2,
            'status' => $status,
        ]);
    }

    public function test_perlu_tindakan_only_appears_when_completed_orders_exist(): void
    {
        $line = Plant::create(['name' => 'Live Plant'])->lines()->create(['name' => 'Live Line']);
        $user = User::factory()->create(['role' => 'user', 'line_id' => $line->id]);

        $this->actingAs($user)->get(route('dashboard'))->assertOk()
            ->assertDontSee('Perlu Tindakan')
            ->assertDontSee('Riwayat Request Additional Box / Download PDF &amp; Excel', false)
            ->assertDontSee('Scrap limit hanya sebagai peringatan.');

        $order = $this->createOrder($user, 1, 'completed');
        $this->get(route('dashboard'))->assertOk()->assertSee('Perlu Tindakan');

        $order->update(['status' => 'confirmed']);
        $this->get(route('dashboard'))->assertOk()->assertDontSee('Perlu Tindakan');
    }

    public function test_activity_version_is_scoped_to_current_user_and_omd_sees_order_updates(): void
    {
        $line = Plant::create(['name' => 'Live Plant'])->lines()->create(['name' => 'Live Line']);
        $owner = User::factory()->create(['role' => 'user', 'line_id' => $line->id]);
        $other = User::factory()->create(['role' => 'user', 'line_id' => $line->id]);
        $omd = User::factory()->create(['role' => 'omd']);

        $ownerBefore = $this->actingAs($owner)->getJson(route('activity.version'))
            ->assertOk()->json('version');
        $otherBefore = $this->actingAs($other)->getJson(route('activity.version'))
            ->assertOk()->json('version');
        $omdBefore = $this->actingAs($omd)->getJson(route('activity.version'))
            ->assertOk()->json('version');

        $order = $this->createOrder($owner, 1, 'submitted');
        $ownerAfter = $this->actingAs($owner)->getJson(route('activity.version'))->json('version');
        $otherAfter = $this->actingAs($other)->getJson(route('activity.version'))->json('version');
        $omdAfter = $this->actingAs($omd)->getJson(route('activity.version'))->json('version');

        $this->assertNotSame($ownerBefore, $ownerAfter);
        $this->assertSame($otherBefore, $otherAfter);
        $this->assertNotSame($omdBefore, $omdAfter);

        // Perubahan status tetap terdeteksi meskipun terjadi pada detik yang sama.
        $order->update(['status' => 'completed']);
        $this->assertNotSame($ownerAfter, $this->actingAs($owner)
            ->getJson(route('activity.version'))->json('version'));
    }

    public function test_additional_box_list_row_opens_its_document_and_version_changes(): void
    {
        $line = Plant::create(['name' => 'Live Plant'])->lines()->create(['name' => 'Live Line']);
        $user = User::factory()->create(['role' => 'user', 'line_id' => $line->id]);
        $this->actingAs($user);
        $before = $this->getJson(route('activity.version'))->json('version');

        $document = AdditionalBoxRequest::create([
            'user_id' => $user->id,
            'submission_token' => (string) Str::uuid(),
            'purpose' => AdditionalBoxRequest::PURPOSE,
        ]);

        $this->get(route('user.additional-box.index'))->assertOk()
            ->assertSee('additional-box-row')
            ->assertSee('data-href="'.route('user.additional-box.show', $document).'"', false);
        $this->assertNotSame($before, $this->getJson(route('activity.version'))->json('version'));
    }
}
