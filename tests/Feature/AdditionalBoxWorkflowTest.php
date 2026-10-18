<?php

namespace Tests\Feature;

use App\Models\AdditionalBoxRequest;
use App\Models\NgType;
use App\Models\Plant;
use App\Models\ProductScrapLimit;
use App\Models\RepairOrder;
use App\Models\User;
use App\Models\UserScrapLimitCycle;
use App\Services\UserScrapLimitService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdditionalBoxWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private function fixture(): array
    {
        $line = Plant::create(['name' => 'Test Plant'])->lines()->create(['name' => 'Test Line']);
        $user = User::factory()->create(['role' => 'user', 'line_id' => $line->id]);
        $model = $line->masterModels()->create(['model' => 'Model Test']);
        $ng = NgType::firstOrCreate(['code' => 'S'], ['name' => 'Scrap']);
        $cycles = collect();
        foreach (['Box A', 'Box B'] as $index => $name) {
            $qty = $index === 0 ? 12 : 17;
            $product = $model->products()->create(['name' => $name]);
            ProductScrapLimit::create(['product_id' => $product->id, 'limit_qty' => 10,
                'effective_from' => now()->subDay(), 'source' => ProductScrapLimit::SOURCE_OMD]);
            // Confirmed OMD results are the ONLY permitted quantity source.
            $order = RepairOrder::create([
                'order_number' => 'TEST-' . ($index + 1), 'user_id' => $user->id, 'line_id' => $line->id,
                'order_year' => now()->year, 'sequence' => $index + 1,
                'product_id' => $product->id, 'ng_type_id' => $ng->id,
                'order_date' => now(), 'quantity' => $qty, 'status' => 'confirmed',
            ]);
            $order->items()->create([
                'product_id' => $product->id, 'ng_type_id' => $ng->id,
                'before_qty' => $qty, 'after_qty' => $qty,
            ]);
            $order->confirmation()->create(['confirmed_by_user_id' => $user->id, 'confirmed_at' => now()]);
            $cycles->push(UserScrapLimitCycle::create([
                'user_id' => $user->id, 'product_id' => $product->id,
                'line_id' => $line->id, 'limit_qty' => 10, 'started_at' => now()->subMonth(),
                'reached_at' => now(), 'reached_qty' => $qty,
            ]));
        }
        return [$user, $cycles];
    }

    private function payload($cycles, array $choices = ['yes', 'yes'], array $reasons = []): array
    {
        return [
            'submission_token' => (string) Str::uuid(),
            'decisions' => $cycles->map(fn ($cycle, $index) => [
                'cycle_id' => $cycle->id,
                'decision' => $choices[$index],
                'reason' => $reasons[$index] ?? '',
            ])->all(),
        ];
    }

    public function test_multiple_orders_use_confirmed_scrap_not_user_qty_and_retry_is_idempotent(): void
    {
        [$user, $cycles] = $this->fixture();
        $this->actingAs($user);
        $data = $this->payload($cycles);
        $this->post(route('user.additional-box.store'), $data)->assertRedirect();
        $document = AdditionalBoxRequest::firstOrFail();
        $this->assertSame('Penggantian scrap box', $document->purpose);
        $this->assertSame([12, 17], $document->items->pluck('quantity')->all());
        foreach ($cycles as $cycle) {
            $this->assertNotNull($cycle->fresh()->reset_at);
            $this->assertSame('yes', $cycle->fresh()->decision);
        }
        $this->post(route('user.additional-box.store'), $data)
            ->assertRedirect(route('user.additional-box.show', $document));
        $this->assertDatabaseCount('additional_box_requests', 1);
        $this->assertDatabaseCount('additional_box_request_items', 2);
        $this->assertDatabaseCount('additional_box_request_decisions', 2);
    }

    public function test_one_selected_order_submits_while_other_product_stays_in_warning(): void
    {
        [$user, $cycles] = $this->fixture();
        $first = $cycles->first();
        $second = $cycles->last();
        $this->actingAs($user)->post(route('user.additional-box.store'),
            $this->payload(collect([$first]), ['yes']))->assertRedirect();

        $document = AdditionalBoxRequest::firstOrFail();
        $this->assertSame(1, $document->decisions()->count());
        $this->assertSame(1, $document->items()->count());
        $this->assertSame(12, (int) $document->items->first()->quantity);
        $this->assertNotNull($first->fresh()->reset_at);
        $this->assertNull($second->fresh()->reset_at);
        $this->get(route('dashboard'))->assertOk()
            ->assertSee('Box B')->assertDontSee('data-scrap-choice-row="' . $first->id . '"', false);
    }

    public function test_one_selected_no_can_submit_without_order_and_leaves_other_alert(): void
    {
        [$user, $cycles] = $this->fixture();
        $first = $cycles->first();
        $second = $cycles->last();
        $this->actingAs($user)->post(route('user.additional-box.store'),
            $this->payload(collect([$first]), ['no'], ['Stok masih cukup']))->assertRedirect();

        $document = AdditionalBoxRequest::firstOrFail();
        $this->assertSame(1, $document->decisions()->count());
        $this->assertSame('no', $document->decisions->first()->decision);
        $this->assertSame(0, $document->items()->count());
        $this->assertNotNull($first->fresh()->reset_at);
        $this->assertNull($second->fresh()->reset_at);
        $this->get(route('dashboard'))->assertOk()
            ->assertSee('Box B')->assertDontSee('data-scrap-choice-row="' . $first->id . '"', false);
        $this->get(route('user.additional-box.show', $document))->assertOk()->assertSee('Stok masih cukup');
    }

    public function test_submit_without_selected_decision_is_rejected(): void
    {
        [$user, $cycles] = $this->fixture();
        $this->actingAs($user)->postJson(route('user.additional-box.store'), [
            'submission_token' => (string) Str::uuid(), 'decisions' => [],
        ])->assertUnprocessable()->assertJsonValidationErrors('decisions');
        $this->assertDatabaseCount('additional_box_requests', 0);
        $this->assertNull($cycles->first()->fresh()->reset_at);
        $this->assertNull($cycles->last()->fresh()->reset_at);
    }

    public function test_mixed_decisions_require_reason_and_reset_every_selected_cycle_atomically(): void
    {
        [$user, $cycles] = $this->fixture();
        $this->actingAs($user);
        $data = $this->payload($cycles, ['yes', 'no'], ['', 'Stok mencukupi']);
        $this->post(route('user.additional-box.store'), $data)->assertRedirect();
        $document = AdditionalBoxRequest::firstOrFail();
        $this->assertCount(1, $document->items);
        $this->assertCount(2, $document->decisions);
        $this->assertSame(['yes', 'no'], $document->decisions->pluck('decision')->all());
        $this->assertSame([12, 17], $document->decisions->pluck('scrap_qty')->all());
        $this->assertSame('Stok mencukupi', $document->decisions->last()->reason);
        $this->get(route('user.additional-box.show', $document))->assertOk()->assertSee('Stok mencukupi')->assertSee('Box B');
        $this->get(route('user.additional-box.index'))->assertOk()->assertSee('Box B')->assertSee('Box A');
        $this->assertSame(12, $document->items->first()->quantity);
        $this->assertSame('no', $cycles->last()->fresh()->decision);
        $this->assertSame('Stok mencukupi', $cycles->last()->fresh()->decision_reason);
        $this->assertNotNull($cycles->first()->fresh()->reset_at);
        $this->assertNotNull($cycles->last()->fresh()->reset_at);
    }

    public function test_all_no_creates_history_document_but_no_export(): void
    {
        [$user, $cycles] = $this->fixture();
        $this->actingAs($user)->post(route('user.additional-box.store'),
            $this->payload($cycles, ['no', 'no'], ['Stok cukup', 'Belum dibutuhkan']))
            ->assertRedirect();
        $this->assertDatabaseCount('additional_box_requests', 1);
        $document = AdditionalBoxRequest::firstOrFail();
        $this->assertSame(2, $document->decisions->count());
        $this->assertSame(0, $document->items->count());
        $this->get(route('user.additional-box.index'))->assertOk()->assertSee('Box A')->assertSee('Box B');
        $this->get(route('user.additional-box.show', $document))->assertOk()->assertSee('Stok cukup')->assertSee('Belum dibutuhkan');
        foreach (['pdf', 'excel'] as $format) {
            $this->get(route('user.additional-box.export', [$document, $format]))->assertNotFound();
        }
        $this->assertSame('no', $cycles->first()->fresh()->decision);
        $this->assertSame('no', $cycles->last()->fresh()->decision);
    }

    public function test_one_blank_no_reason_rejects_all_decisions_without_reset(): void
    {
        [$user, $cycles] = $this->fixture();
        $data = $this->payload($cycles, ['yes', 'no'], ['', '   ']);
        $this->actingAs($user)->postJson(route('user.additional-box.store'), $data)
            ->assertUnprocessable()->assertJsonValidationErrors('decisions');
        $this->assertNull($cycles->first()->fresh()->reset_at);
        $this->assertNull($cycles->last()->fresh()->reset_at);
        $this->assertDatabaseCount('additional_box_requests', 0);
    }

    public function test_stale_product_keeps_every_cycle_and_document_unchanged(): void
    {
        [$user, $cycles] = $this->fixture();
        $cycles->last()->update(['reset_at' => now()]);
        $this->actingAs($user)->postJson(route('user.additional-box.store'), $this->payload($cycles))
            ->assertUnprocessable()->assertJsonValidationErrors('decisions');
        $this->assertNull($cycles->first()->fresh()->reset_at);
        $this->assertDatabaseCount('additional_box_requests', 0);
    }

    public function test_user_cannot_supply_quantity_or_another_users_cycle(): void
    {
        [$user, $cycles] = $this->fixture();
        $data = $this->payload($cycles);
        $data['decisions'][0]['quantity'] = 999999;
        $this->actingAs($user)->postJson(route('user.additional-box.store'), $data)
            ->assertUnprocessable()->assertJsonValidationErrors('decisions.0.quantity');
        unset($data['decisions'][0]['quantity']);
        $other = User::factory()->create(['role' => 'user', 'line_id' => $user->line_id]);
        $this->actingAs($other)->postJson(route('user.additional-box.store'), $data)
            ->assertUnprocessable()->assertJsonValidationErrors('decisions');
        $this->assertDatabaseCount('additional_box_requests', 0);
    }

    public function test_user_cannot_download_another_users_documents(): void
    {
        [$user, $cycles] = $this->fixture();
        $this->actingAs($user)->post(route('user.additional-box.store'), $this->payload($cycles))
            ->assertRedirect();
        $doc = AdditionalBoxRequest::firstOrFail();
        $other = User::factory()->create(['role' => 'user', 'line_id' => $user->line_id]);
        $this->actingAs($other)->get(route('user.additional-box.show', $doc))->assertForbidden();
        foreach (['pdf', 'excel'] as $format) {
            $this->get(route('user.additional-box.export', [$doc, $format]))->assertForbidden();
        }
    }

    public function test_legacy_no_route_still_requires_reason_and_resets_only_one_cycle(): void
    {
        [$user, $cycles] = $this->fixture();
        $this->actingAs($user);
        $url = route('user.scrap-limit.decision', $cycles->first());
        $this->postJson($url, ['decision' => 'no', 'reason' => ' '])->assertUnprocessable();
        $this->post($url, ['decision' => 'no', 'reason' => 'Stok cukup'])->assertRedirect();
        $this->assertSame('Stok cukup', $cycles->first()->fresh()->decision_reason);
        $this->assertNull($cycles->last()->fresh()->reset_at);
        $this->assertDatabaseCount('additional_box_requests', 1);
        $this->assertDatabaseCount('additional_box_request_decisions', 1);
    }

    public function test_saving_no_reason_immediately_creates_one_history_and_resets_only_that_product(): void
    {
        [$user, $cycles] = $this->fixture();
        $first = $cycles->first();
        $second = $cycles->last();
        $this->actingAs($user);

        $url = route('user.scrap-limit.decision', $first);
        $this->postJson($url, ['decision' => 'no', 'reason' => 'Belum perlu pengganti'])
            ->assertOk()->assertJsonPath('success', true)
            ->assertJsonStructure(['document_number', 'detail_url']);

        $document = AdditionalBoxRequest::firstOrFail();
        $this->assertSame(1, $document->decisions()->count());
        $this->assertSame('no', $document->decisions->first()->decision);
        $this->assertSame('Belum perlu pengganti', $document->decisions->first()->reason);
        $this->assertSame(0, $document->items()->count());
        $this->assertNotNull($first->fresh()->reset_at);
        $this->assertNull($second->fresh()->reset_at);
        $this->get(route('dashboard'))->assertOk()->assertSee('Box B')
            ->assertDontSee('data-scrap-choice-row="' . $first->id . '"', false);
        $this->get(route('user.additional-box.show', $document))->assertOk()->assertSee('Belum perlu pengganti');
        foreach (['pdf', 'excel'] as $format) {
            $this->get(route('user.additional-box.export', [$document, $format]))->assertNotFound();
        }

        // A network retry must not create a second history document.
        $this->postJson($url, ['decision' => 'no', 'reason' => 'Belum perlu pengganti'])
            ->assertOk()->assertJsonPath('success', true);
        $this->assertDatabaseCount('additional_box_requests', 1);
        $this->assertDatabaseCount('additional_box_request_decisions', 1);
    }

    public function test_immediate_no_save_still_rejects_empty_reason_and_other_users_cycle(): void
    {
        [$user, $cycles] = $this->fixture();
        $url = route('user.scrap-limit.decision', $cycles->first());
        $this->actingAs($user)->postJson($url, ['decision' => 'no', 'reason' => '  '])
            ->assertUnprocessable();
        $other = User::factory()->create(['role' => 'user', 'line_id' => $user->line_id]);
        $this->actingAs($other)->postJson($url, ['decision' => 'no', 'reason' => 'Cukup'])
            ->assertForbidden();
        $this->assertDatabaseCount('additional_box_requests', 0);
        $this->assertNull($cycles->first()->fresh()->reset_at);
    }

    public function test_reset_excludes_old_confirmations_in_same_second(): void
    {
        [$user, $cycles] = $this->fixture();
        $this->freezeTime();
        $cycle = $cycles->first();
        $service = app(UserScrapLimitService::class);
        $this->assertSame(12, $service->quantity($user, $cycle->product_id, $cycle));
        $service->reset($cycle, $user, 'Test reset');
        $current = $service->cycle($user, $cycle->product_id);
        $this->assertSame(0, $service->quantity($user, $cycle->product_id, $current));
        $this->travelBack();
    }

    public function test_dashboard_keeps_dropdown_and_does_not_render_quantity_inputs(): void
    {
        [$user, $cycles] = $this->fixture();
        $this->actingAs($user)->get(route('dashboard'))->assertOk()
            ->assertSee('id="scrap-limit-toggle"', false)
            ->assertSee('data-scrap-no', false)
            ->assertDontSee('data-box-qty')
            ->assertSee('Batalkan pilihan Order')
            ->assertSee('data-scrap-reason-save', false)
            ->assertSee('>Simpan</button>', false)
            ->assertDontSee('Simpan Alasan')
            ->assertSee('Scrap');
    }

    public function test_archive_route_and_pdf_excel_exports_still_work(): void
    {
        [$user, $cycles] = $this->fixture();
        $this->actingAs($user)->post(route('user.additional-box.store'), $this->payload($cycles))
            ->assertRedirect();
        $document = AdditionalBoxRequest::firstOrFail();
        foreach (['pdf', 'excel'] as $format) {
            $this->get(route('user.additional-box.export', [$document, $format]))->assertOk()->assertDownload();
        }
    }
}
