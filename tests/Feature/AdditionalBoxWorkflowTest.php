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
        $cycles = collect();
        foreach (['Box A', 'Box B'] as $name) {
            $product = $model->products()->create(['name' => $name]);
            ProductScrapLimit::create(['product_id' => $product->id, 'limit_qty' => 10,
                'effective_from' => now()->subDay(), 'source' => ProductScrapLimit::SOURCE_OMD]);
            $cycles->push(UserScrapLimitCycle::create(['user_id' => $user->id, 'product_id' => $product->id,
                'line_id' => $line->id, 'limit_qty' => 10, 'started_at' => now()->subMonth(),
                'reached_at' => now(), 'reached_qty' => 12]));
        }
        return [$user, $cycles];
    }

    private function payload($cycles): array
    {
        return ['submission_token' => (string) Str::uuid(), 'items' => $cycles->map(fn ($cycle) =>
            ['cycle_id' => $cycle->id, 'quantity' => 25])->all()];
    }

    public function test_multiple_products_create_one_document_reset_only_selected_cycles_and_retry_is_idempotent(): void
    {
        [$user, $cycles] = $this->fixture();
        $this->actingAs($user);
        $data = $this->payload($cycles);
        $this->post(route('user.additional-box.store'), $data)->assertRedirect();
        $document = AdditionalBoxRequest::firstOrFail();
        $this->assertSame('Penggantian scrap box', $document->purpose);
        $this->assertCount(2, $document->items);
        $this->assertSame(25, $document->items->first()->quantity);
        foreach ($cycles as $cycle) {
            $this->assertNotNull($cycle->fresh()->reset_at);
            $this->assertSame('yes', $cycle->fresh()->decision);
        }
        $this->assertSame(2, UserScrapLimitCycle::where('user_id', $user->id)->whereNull('reset_at')->count());
        $this->post(route('user.additional-box.store'), $data)->assertRedirect(route('user.additional-box.show', $document));
        $this->assertDatabaseCount('additional_box_requests', 1);
        $this->assertDatabaseCount('additional_box_request_items', 2);
        $this->assertSame(4, UserScrapLimitCycle::where('user_id', $user->id)->count());
        $this->get(route('user.additional-box.show', $document))->assertOk()->assertSee('Box A')->assertSee('Box B');
    }

    public function test_failed_batch_keeps_every_cycle_and_document_unchanged(): void
    {
        [$user, $cycles] = $this->fixture();
        $cycles->last()->update(['reset_at' => now()]);
        $this->actingAs($user)->postJson(route('user.additional-box.store'), $this->payload($cycles))
            ->assertUnprocessable()->assertJsonValidationErrors('items');
        $this->assertNull($cycles->first()->fresh()->reset_at);
        $this->assertNull($cycles->first()->fresh()->decision);
        $this->assertDatabaseCount('additional_box_requests', 0);
    }

    public function test_owner_cannot_submit_another_users_cycle_or_download_their_form(): void
    {
        [$owner, $cycles] = $this->fixture();
        $other = User::factory()->create(['role' => 'user', 'line_id' => $owner->line_id]);
        $this->actingAs($other)->postJson(route('user.additional-box.store'), $this->payload($cycles))->assertUnprocessable();
        $this->actingAs($owner)->post(route('user.additional-box.store'), $this->payload($cycles))->assertRedirect();
        $doc = AdditionalBoxRequest::firstOrFail();
        $this->actingAs($other)->get(route('user.additional-box.show', $doc))->assertForbidden();
        foreach (['pdf', 'excel'] as $format) {
            $this->get(route('user.additional-box.export', [$doc, $format]))->assertForbidden();
        }
    }

    public function test_no_order_requires_reason_resets_one_product_and_preserves_history(): void
    {
        [$user, $cycles] = $this->fixture();
        $this->actingAs($user);
        $url = route('user.scrap-limit.decision', $cycles->first());
        $this->postJson($url, ['decision' => 'no', 'reason' => '   '])->assertUnprocessable();
        $this->assertNull($cycles->first()->fresh()->reset_at);
        $this->post($url, ['decision' => 'no', 'reason' => 'Stok masih cukup'])->assertRedirect();
        $this->assertSame('Stok masih cukup', $cycles->first()->fresh()->decision_reason);
        $this->assertNotNull($cycles->first()->fresh()->reset_at);
        $this->assertNull($cycles->last()->fresh()->reset_at);
        $this->post($url, ['decision' => 'no', 'reason' => 'Stok masih cukup'])->assertRedirect();
        $this->assertSame(3, UserScrapLimitCycle::where('user_id', $user->id)->count());
        $this->assertDatabaseCount('additional_box_requests', 0);
    }

    public function test_invalid_or_duplicate_product_quantities_do_not_reset_anything(): void
    {
        [$user, $cycles] = $this->fixture();
        $this->actingAs($user);
        foreach ([0, -1, 1.5, 1000001] as $quantity) {
            $data = $this->payload($cycles);
            $data['items'][0]['quantity'] = $quantity;
            $this->postJson(route('user.additional-box.store'), $data)->assertUnprocessable();
        }
        $data = $this->payload($cycles);
        $data['items'][1]['cycle_id'] = $data['items'][0]['cycle_id'];
        $this->postJson(route('user.additional-box.store'), $data)->assertUnprocessable();
        $this->assertNull($cycles->first()->fresh()->reset_at);
        $this->assertDatabaseCount('additional_box_requests', 0);
    }

    public function test_reset_excludes_old_confirmations_in_same_second_and_counts_new_confirmations(): void
    {
        [$user, $cycles] = $this->fixture();
        $this->freezeTime();
        $cycle = $cycles->first();
        $ng = NgType::firstOrCreate(['code' => 'S'], ['name' => 'Scrap']);
        $createOrder = function (int $seq) use ($user, $cycle, $ng) {
            $order = RepairOrder::create(['order_number' => 'TEST-' . $seq, 'user_id' => $user->id,
                'line_id' => $user->line_id, 'order_year' => now()->year, 'sequence' => $seq,
                'product_id' => $cycle->product_id, 'ng_type_id' => $ng->id,
                'order_date' => now(), 'quantity' => 12, 'status' => 'confirmed']);
            $order->items()->create(['product_id' => $cycle->product_id, 'ng_type_id' => $ng->id, 'before_qty' => 12, 'after_qty' => 12]);
            $order->confirmation()->create(['confirmed_by_user_id' => $user->id, 'confirmed_at' => now()]);
        };
        $createOrder(1);
        $service = app(UserScrapLimitService::class);
        $service->reset($cycle, $user, 'Test reset');
        $current = $service->cycle($user, $cycle->product_id);
        $this->assertSame(0, $service->quantity($user, $cycle->product_id, $current));
        $createOrder(2);
        $this->assertSame(12, $service->quantity($user, $cycle->product_id, $current));
        $this->assertFalse($service->isBlocked($user, $cycle->product_id));
        $this->travelBack();
    }

    public function test_two_items_export_without_empty_rows_and_with_compact_left_aligned_body(): void
    {
        [$user, $cycles] = $this->fixture();
        $this->actingAs($user)->post(route('user.additional-box.store'), $this->payload($cycles))->assertRedirect();
        $document = AdditionalBoxRequest::with('items')->firstOrFail();
        $service = app(\App\Services\AdditionalBoxExportService::class);

        // Both file types share the same filtered, unpadded item rows.
        $pages = (new \ReflectionMethod($service, 'pages'))->invoke($service, $document);
        $this->assertCount(1, $pages);
        $this->assertCount(2, $pages[0]);
        $this->assertSame([1, 2], array_column($pages[0], 'number'));
        $html = view('user.additional-box.pdf', [
            'document' => $document, 'logoData' => null, 'pages' => $pages,
        ])->render();
        $this->assertMatchesRegularExpression('/<tbody>\s*<tr>.*?<\/tr>\s*<tr>.*?<\/tr>\s*<\/tbody>/s', $html);
        $this->assertStringContainsString('width:5%', $html);
        $this->assertStringContainsString('width:9%', $html);

        ob_start();
        try {
            $service->excel($document)->sendContent();
            $content = ob_get_contents();
        } finally {
            ob_end_clean();
        }
        $temp = tempnam(sys_get_temp_dir(), 'additional-box-');
        try {
            file_put_contents($temp, $content);
            $workbook = \PhpOffice\PhpSpreadsheet\IOFactory::load($temp);
            $sheet = $workbook->getActiveSheet();
            $this->assertSame(1, $sheet->getCell('A10')->getValue());
            $this->assertSame(2, $sheet->getCell('A11')->getValue());
            $this->assertNull($sheet->getCell('A12')->getValue());
            $this->assertEqualsWithDelta(15, $sheet->getRowDimension(10)->getRowHeight(), 0.01);
            $this->assertSame('A1:P36', $sheet->getPageSetup()->getPrintArea());
            $this->assertSame(1, $sheet->getPageSetup()->getFitToWidth());
            $this->assertSame(1, $sheet->getPageSetup()->getFitToHeight());
            $this->assertSame('left', $sheet->getStyle('B10')->getAlignment()->getHorizontal());
            $this->assertSame('left', $sheet->getStyle('N10')->getAlignment()->getHorizontal());
            $this->assertSame('center', $sheet->getStyle('B9')->getAlignment()->getHorizontal());
            $workbook->disconnectWorksheets();
        } finally {
            @unlink($temp);
        }
    }

    public function test_archive_route_and_pdf_excel_exports_are_available(): void
    {
        [$user, $cycles] = $this->fixture();
        $this->actingAs($user)->post(route('user.additional-box.store'), $this->payload($cycles))->assertRedirect();
        $document = AdditionalBoxRequest::firstOrFail();
        foreach (['pdf', 'excel'] as $format) {
            $this->get(route('user.additional-box.export', [$document, $format]))->assertOk()->assertDownload();
        }
        $this->actingAs(User::factory()->create(['role' => 'omd']))
            ->get(route('omd.targets.abnormality.archive', ['year' => 2026]))->assertOk();
    }
}
