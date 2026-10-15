<?php

namespace Tests\Feature;

use App\Models\Plant;
use App\Models\ProductScrapLimit;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterProductInlineTest extends TestCase
{
    use RefreshDatabase;

    public function test_json_product_rename_preserves_its_model_line_plant_and_scrap_limits(): void
    {
        $omd = User::factory()->create(['role' => 'omd']);
        $this->actingAs($omd);
        [$plant, $line, $model, $product] = $this->makeProductHierarchy();
        $product->update(['code' => 'PRODUCT-A', 'is_active' => false]);
        $limit = $product->scrapLimits()->create([
            'limit_qty' => 25,
            'effective_from' => now()->startOfDay(),
            'source' => ProductScrapLimit::SOURCE_OMD,
            'created_by' => $omd->id,
        ]);

        $this->putJson(route('omd.master.product.update', $product), [
            'master_model_id' => $model->id,
            'name' => 'Product A Baru',
        ])->assertOk()->assertExactJson([
            'message' => 'Produk berhasil diperbarui',
            'product' => [
                'id' => $product->id,
                'name' => 'Product A Baru',
                'master_model_id' => $model->id,
            ],
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'master_model_id' => $model->id,
            'name' => 'Product A Baru',
            'code' => 'PRODUCT-A',
            'is_active' => false,
        ]);
        $this->assertSame($line->id, $model->fresh()->line_id);
        $this->assertSame($plant->id, $line->fresh()->plant_id);
        $this->assertSame($product->id, $limit->fresh()->product_id);
        $this->assertSame(25, $limit->fresh()->limit_qty);
        $this->assertSame($limit->id, $product->fresh()->scrapLimits->sole()->id);
    }

    public function test_repeated_json_renames_keep_the_same_product_and_model(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'omd']));
        [, , $model, $product] = $this->makeProductHierarchy();

        foreach (['Nama Baru', 'Produk & Komponen', str_repeat('a', 150)] as $name) {
            $this->putJson(route('omd.master.product.update', $product), [
                'master_model_id' => $model->id,
                'name' => $name,
            ])->assertOk()
                ->assertJsonPath('product.id', $product->id)
                ->assertJsonPath('product.master_model_id', $model->id)
                ->assertJsonPath('product.name', $name);

            $this->assertSame($name, $product->fresh()->name);
            $this->assertSame($model->id, $product->fresh()->master_model_id);
        }

        $this->assertDatabaseCount('products', 1);
    }

    public function test_empty_whitespace_and_overlong_product_names_are_rejected_without_writes(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'omd']));
        [, , $model, $product] = $this->makeProductHierarchy();

        foreach (['', '   ', str_repeat('a', 151)] as $invalidName) {
            $this->putJson(route('omd.master.product.update', $product), [
                'master_model_id' => $model->id,
                'name' => $invalidName,
            ])->assertUnprocessable()->assertJsonValidationErrors('name');

            $this->assertSame('Product A', $product->fresh()->name);
            $this->assertSame($model->id, $product->fresh()->master_model_id);
        }
    }

    public function test_product_rename_still_requires_an_existing_model(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'omd']));
        [, , $model, $product] = $this->makeProductHierarchy();

        foreach ([[], ['master_model_id' => null], ['master_model_id' => 999999]] as $parentInput) {
            $this->putJson(route('omd.master.product.update', $product), [
                ...$parentInput,
                'name' => 'Product A Baru',
            ])->assertUnprocessable()->assertJsonValidationErrors('master_model_id');

            $this->assertSame('Product A', $product->fresh()->name);
            $this->assertSame($model->id, $product->fresh()->master_model_id);
        }
    }

    public function test_duplicate_product_names_follow_existing_rules_and_remain_allowed(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'omd']));
        [, , $model, $product] = $this->makeProductHierarchy();
        $otherProduct = $model->products()->create(['name' => 'Product B']);

        $this->putJson(route('omd.master.product.update', $product), [
            'master_model_id' => $model->id,
            'name' => 'Product B',
        ])->assertOk();

        $this->assertSame('Product B', $product->fresh()->name);
        $this->assertSame('Product B', $otherProduct->fresh()->name);
        $this->assertSame(2, $model->products()->where('name', 'Product B')->count());
    }

    public function test_html_product_rename_keeps_its_selected_line_referrer(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'omd']));
        Plant::create(['name' => 'Body'])->lines()->create(['name' => 'A Body']);
        [, $line, $model, $product] = $this->makeProductHierarchy();
        $contextUrl = route('omd.master.model-product', ['line_id' => $line->id]);

        $this->from($contextUrl)->put(route('omd.master.product.update', $product), [
            'master_model_id' => $model->id,
            'name' => 'Product A Baru',
        ])->assertRedirect($contextUrl)
            ->assertSessionHas('success', 'Produk berhasil diperbarui');

        $this->get($contextUrl)->assertOk()
            ->assertViewHas('selectedLineId', (string) $line->id)
            ->assertViewHas('models', fn ($models) => $models->firstWhere('id', $model->id)
                ->products->firstWhere('id', $product->id)->name === 'Product A Baru');
    }

    public function test_model_product_page_loads_the_selected_line_and_product_relationships_for_omd(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'omd']));
        [$plant, $line, $model, $product] = $this->makeProductHierarchy();

        $this->get(route('omd.master.model-product', ['line_id' => $line->id]))
            ->assertOk()
            ->assertViewHas('selectedLineId', (string) $line->id)
            ->assertViewHas('models', function ($models) use ($plant, $line, $model, $product) {
                $loadedModel = $models->firstWhere('id', $model->id);

                return $loadedModel->relationLoaded('products')
                    && $loadedModel->relationLoaded('line')
                    && $loadedModel->line->id === $line->id
                    && $loadedModel->line->plant->id === $plant->id
                    && $loadedModel->products->contains('id', $product->id);
            });
    }

    public function test_guests_cannot_view_update_or_delete_master_products(): void
    {
        [, , $model, $product] = $this->makeProductHierarchy();

        $this->getJson(route('omd.master.model-product'))->assertUnauthorized();
        $this->putJson(route('omd.master.product.update', $product), [
            'master_model_id' => $model->id,
            'name' => 'Changed',
        ])->assertUnauthorized();
        $this->deleteJson(route('omd.master.product.destroy', $product))->assertUnauthorized();

        $this->assertSame('Product A', $product->fresh()->name);
        $this->assertSame($model->id, $product->fresh()->master_model_id);
    }

    public function test_non_omd_users_cannot_view_update_or_delete_master_products(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'user']));
        [, , $model, $product] = $this->makeProductHierarchy();

        $this->getJson(route('omd.master.model-product'))->assertForbidden();
        $this->putJson(route('omd.master.product.update', $product), [
            'master_model_id' => $model->id,
            'name' => 'Changed',
        ])->assertForbidden();
        $this->deleteJson(route('omd.master.product.destroy', $product))->assertForbidden();

        $this->assertSame('Product A', $product->fresh()->name);
        $this->assertSame($model->id, $product->fresh()->master_model_id);
    }

    public function test_product_update_requires_csrf_when_the_normal_test_bypass_is_disabled(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'omd']));
        [, , $model, $product] = $this->makeProductHierarchy();

        // Laravel bypasses CSRF in tests; exercise the production check and AJAX header.
        $this->app->bind(ValidateCsrfToken::class, fn ($app) => new class($app, $app['encrypter']) extends ValidateCsrfToken
        {
            protected function runningUnitTests()
            {
                return false;
            }
        });

        $this->putJson(route('omd.master.product.update', $product), [
            'master_model_id' => $model->id,
            'name' => 'Changed',
        ])->assertStatus(419);

        $this->assertSame('Product A', $product->fresh()->name);

        $this->withSession(['_token' => 'product-inline-test-token'])
            ->withHeaders(['X-CSRF-TOKEN' => 'product-inline-test-token'])
            ->putJson(route('omd.master.product.update', $product), [
                'master_model_id' => $model->id,
                'name' => 'Product A Baru',
            ])->assertOk();

        $this->assertSame('Product A Baru', $product->fresh()->name);
    }

    public function test_product_deletion_keeps_its_existing_redirect_and_cascade_behavior(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'omd']));
        [$plant, $line, $model, $product] = $this->makeProductHierarchy();
        $otherProduct = $model->products()->create(['name' => 'Product B']);
        $limit = $product->scrapLimits()->create([
            'limit_qty' => 25,
            'effective_from' => now()->startOfDay(),
            'source' => ProductScrapLimit::SOURCE_OMD,
        ]);
        $contextUrl = route('omd.master.model-product', ['line_id' => $line->id]);

        $this->from($contextUrl)->delete(route('omd.master.product.destroy', $product))
            ->assertRedirect($contextUrl)
            ->assertSessionHas('success', 'Produk berhasil dihapus');

        $this->assertModelMissing($product);
        $this->assertModelMissing($limit);
        $this->assertModelExists($otherProduct);
        $this->assertModelExists($model);
        $this->assertModelExists($line);
        $this->assertModelExists($plant);
    }

    private function makeProductHierarchy(): array
    {
        $plant = Plant::create(['name' => 'Electric']);
        $line = $plant->lines()->create(['name' => 'AS Electric']);
        $model = $line->masterModels()->create(['model' => 'Model A']);
        $product = $model->products()->create(['name' => 'Product A']);

        return [$plant, $line, $model, $product];
    }
}
