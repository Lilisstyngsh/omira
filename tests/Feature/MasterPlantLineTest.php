<?php

namespace Tests\Feature;

use App\Models\Plant;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterPlantLineTest extends TestCase
{
    use RefreshDatabase;

    public function test_line_page_preloads_each_plant_table_and_keeps_the_requested_context(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'omd']));
        $body = Plant::create(['name' => 'Body']);
        $body->lines()->create(['name' => 'AS Body']);
        $electric = Plant::create(['name' => 'Electric']);
        $electric->lines()->create(['name' => 'Z Electric']);
        $electric->lines()->create(['name' => 'A Electric']);

        $this->get(route('omd.master.line', ['plant' => $electric->id]))
            ->assertOk()
            ->assertViewHas('plants', fn ($plants) => $plants->every(
                fn ($plant) => $plant->relationLoaded('lines')
            ))
            ->assertViewHas('selectedPlant', fn ($plant) => $plant->id === $electric->id)
            ->assertViewHas('lines', fn ($lines) => $lines->pluck('name')->all() === ['A Electric', 'Z Electric'])
            ->assertSee('data-plant-lines="'.$body->id.'"', false)
            ->assertSee('data-plant-lines="'.$electric->id.'"', false);
    }

    public function test_json_line_rename_preserves_plant_and_related_models(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'omd']));
        $plant = Plant::create(['name' => 'Electric']);
        $line = $plant->lines()->create(['name' => 'AS Electric']);
        $model = $line->masterModels()->create(['model' => 'Model A']);

        $this->putJson(route('omd.master.line.update', $line), [
            'plant_id' => $plant->id,
            'name' => 'AS Electric Baru',
        ])->assertOk()->assertExactJson([
            'message' => 'Line berhasil diperbarui',
            'line' => [
                'id' => $line->id,
                'name' => 'AS Electric Baru',
                'plant_id' => $plant->id,
            ],
        ]);

        $this->assertDatabaseHas('lines', [
            'id' => $line->id,
            'plant_id' => $plant->id,
            'name' => 'AS Electric Baru',
        ]);
        $this->assertSame($line->id, $model->fresh()->line_id);
    }

    public function test_multiple_json_line_renames_remain_in_the_same_plant(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'omd']));
        $plant = Plant::create(['name' => 'Unit']);

        foreach (['AS Unit', 'MA'] as $name) {
            $line = $plant->lines()->create(['name' => $name]);

            $this->putJson(route('omd.master.line.update', $line), [
                'plant_id' => $plant->id,
                'name' => $name.' Baru',
            ])->assertOk()->assertJsonPath('line.plant_id', $plant->id);

            $this->assertSame($plant->id, $line->fresh()->plant_id);
        }
    }

    public function test_html_line_rename_redirects_to_its_plant_context(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'omd']));
        Plant::create(['name' => 'Body']);
        $plant = Plant::create(['name' => 'Unit']);
        $line = $plant->lines()->create(['name' => 'AS Unit']);

        $this->put(route('omd.master.line.update', $line), [
            'plant_id' => $plant->id,
            'name' => 'AS Unit Baru',
        ])->assertRedirect(route('omd.master.line', ['plant' => $plant->id]))
            ->assertSessionHas('success', 'Line berhasil diperbarui');

        $this->get(route('omd.master.line', ['plant' => $plant->id]))
            ->assertOk()
            ->assertViewHas('selectedPlant', fn ($selectedPlant) => $selectedPlant->id === $plant->id)
            ->assertViewHas('lines', fn ($lines) => $lines->contains('id', $line->id));
    }

    public function test_json_plant_rename_preserves_its_id_and_line_relationships(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'omd']));
        $plant = Plant::create(['name' => 'Unit']);
        $line = $plant->lines()->create(['name' => 'AS Unit']);
        $model = $line->masterModels()->create(['model' => 'Model A']);

        $this->putJson(route('omd.master.plant.update', $plant), [
            'name' => 'Unit Baru',
        ])->assertOk()->assertExactJson([
            'message' => 'Plant berhasil diperbarui',
            'plant' => ['id' => $plant->id, 'name' => 'Unit Baru'],
        ]);

        $this->assertDatabaseHas('plants', ['id' => $plant->id, 'name' => 'Unit Baru']);
        $this->assertSame($plant->id, $line->fresh()->plant_id);
        $this->assertSame($line->id, $model->fresh()->line_id);
    }

    public function test_html_plant_rename_keeps_the_selected_plant_referrer(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'omd']));
        Plant::create(['name' => 'Body']);
        $plant = Plant::create(['name' => 'Unit']);
        $contextUrl = route('omd.master.line', ['plant' => $plant->id]);

        $this->from($contextUrl)->put(route('omd.master.plant.update', $plant), [
            'name' => 'Unit Baru',
        ])->assertRedirect($contextUrl)
            ->assertSessionHas('success', 'Plant berhasil diperbarui');
    }

    public function test_separate_plant_page_keeps_its_existing_html_redirect(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'omd']));
        $plant = Plant::create(['name' => 'Unit']);

        $this->from(route('omd.master.plant'))->put(route('omd.master.plant.update', $plant), [
            'name' => 'Unit Baru',
        ])->assertRedirect(route('omd.master.plant'));
    }

    public function test_empty_and_overlong_names_are_rejected_without_changing_existing_names(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'omd']));
        $plant = Plant::create(['name' => 'Unit']);
        $line = $plant->lines()->create(['name' => 'AS Unit']);

        foreach (['', '   ', str_repeat('a', 101)] as $invalidName) {
            $this->putJson(route('omd.master.plant.update', $plant), [
                'name' => $invalidName,
            ])->assertUnprocessable()->assertJsonValidationErrors('name');

            $this->putJson(route('omd.master.line.update', $line), [
                'plant_id' => $plant->id,
                'name' => $invalidName,
            ])->assertUnprocessable()->assertJsonValidationErrors('name');
        }

        $this->assertSame('Unit', $plant->fresh()->name);
        $this->assertSame('AS Unit', $line->fresh()->name);
        $this->assertSame($plant->id, $line->fresh()->plant_id);
    }

    public function test_line_update_still_requires_an_existing_plant(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'omd']));
        $plant = Plant::create(['name' => 'Unit']);
        $line = $plant->lines()->create(['name' => 'AS Unit']);

        foreach ([null, 999999] as $invalidPlantId) {
            $this->putJson(route('omd.master.line.update', $line), [
                'plant_id' => $invalidPlantId,
                'name' => 'AS Unit Baru',
            ])->assertUnprocessable()->assertJsonValidationErrors('plant_id');
        }

        $this->assertSame('AS Unit', $line->fresh()->name);
        $this->assertSame($plant->id, $line->fresh()->plant_id);
    }

    public function test_duplicate_names_follow_existing_rules_and_remain_allowed(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'omd']));
        $plant = Plant::create(['name' => 'Unit']);
        Plant::create(['name' => 'Electric']);
        $line = $plant->lines()->create(['name' => 'AS Unit']);
        $plant->lines()->create(['name' => 'MA']);

        $this->putJson(route('omd.master.plant.update', $plant), [
            'name' => 'Electric',
        ])->assertOk();

        $this->putJson(route('omd.master.line.update', $line), [
            'plant_id' => $plant->id,
            'name' => 'MA',
        ])->assertOk();

        $this->assertSame('Electric', $plant->fresh()->name);
        $this->assertSame('MA', $line->fresh()->name);
    }

    public function test_line_fetch_only_returns_lines_from_the_requested_plant(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'omd']));
        $body = Plant::create(['name' => 'Body']);
        $body->lines()->create(['name' => 'AS Body']);
        $unit = Plant::create(['name' => 'Unit']);
        $unitLine = $unit->lines()->create(['name' => 'AS Unit']);

        $this->getJson(route('omd.master.line.byPlant', $unit))
            ->assertOk()
            ->assertJsonPath('plant', 'Unit')
            ->assertJsonCount(1, 'lines')
            ->assertJsonPath('lines.0.id', $unitLine->id)
            ->assertJsonPath('lines.0.plant_id', $unit->id);
    }

    public function test_guests_cannot_update_or_delete_plants_and_lines(): void
    {
        $plant = Plant::create(['name' => 'Unit']);
        $line = $plant->lines()->create(['name' => 'AS Unit']);

        $this->putJson(route('omd.master.plant.update', $plant), ['name' => 'Changed'])->assertUnauthorized();
        $this->putJson(route('omd.master.line.update', $line), [
            'plant_id' => $plant->id,
            'name' => 'Changed',
        ])->assertUnauthorized();
        $this->deleteJson(route('omd.master.plant.destroy', $plant))->assertUnauthorized();
        $this->deleteJson(route('omd.master.line.destroy', $line))->assertUnauthorized();

        $this->assertSame('Unit', $plant->fresh()->name);
        $this->assertSame('AS Unit', $line->fresh()->name);
    }

    public function test_non_omd_users_cannot_update_delete_or_fetch_plants_and_lines(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'user']));
        $plant = Plant::create(['name' => 'Unit']);
        $line = $plant->lines()->create(['name' => 'AS Unit']);

        $this->putJson(route('omd.master.plant.update', $plant), ['name' => 'Changed'])->assertForbidden();
        $this->putJson(route('omd.master.line.update', $line), [
            'plant_id' => $plant->id,
            'name' => 'Changed',
        ])->assertForbidden();
        $this->deleteJson(route('omd.master.plant.destroy', $plant))->assertForbidden();
        $this->deleteJson(route('omd.master.line.destroy', $line))->assertForbidden();
        $this->getJson(route('omd.master.line.byPlant', $plant))->assertForbidden();

        $this->assertSame('Unit', $plant->fresh()->name);
        $this->assertSame('AS Unit', $line->fresh()->name);
    }

    public function test_line_with_a_model_cannot_be_deleted(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'omd']));
        $plant = Plant::create(['name' => 'Unit']);
        $line = $plant->lines()->create(['name' => 'AS Unit']);
        $line->masterModels()->create(['model' => 'Model A']);
        $contextUrl = route('omd.master.line', ['plant' => $plant->id]);

        $this->from($contextUrl)->delete(route('omd.master.line.destroy', $line))
            ->assertRedirect($contextUrl)
            ->assertSessionHasErrors();

        $this->assertDatabaseHas('lines', ['id' => $line->id, 'plant_id' => $plant->id]);
    }

    public function test_deleting_an_empty_line_keeps_the_plant_context(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'omd']));
        $plant = Plant::create(['name' => 'Unit']);
        $line = $plant->lines()->create(['name' => 'AS Unit']);

        $this->delete(route('omd.master.line.destroy', $line))
            ->assertRedirect(route('omd.master.line', ['plant' => $plant->id]))
            ->assertSessionHas('success', 'Line berhasil dihapus');

        $this->assertDatabaseMissing('lines', ['id' => $line->id]);
        $this->assertDatabaseHas('plants', ['id' => $plant->id]);
    }

    public function test_plant_deletion_keeps_the_existing_cascade_behavior(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'omd']));
        $plant = Plant::create(['name' => 'Unit']);
        $line = $plant->lines()->create(['name' => 'AS Unit']);
        $model = $line->masterModels()->create(['model' => 'Model A']);
        $product = $model->products()->create(['name' => 'Product A']);
        $otherPlant = Plant::create(['name' => 'Body']);
        $otherLine = $otherPlant->lines()->create(['name' => 'AS Body']);

        $this->from(route('omd.master.line'))->delete(route('omd.master.plant.destroy', $plant))
            ->assertRedirect(route('omd.master.line'))
            ->assertSessionHas('success', 'Plant berhasil dihapus');

        $this->assertModelMissing($plant);
        $this->assertModelMissing($line);
        $this->assertModelMissing($model);
        $this->assertModelMissing($product);
        $this->assertModelExists($otherPlant);
        $this->assertModelExists($otherLine);
    }

    public function test_updates_require_csrf_when_the_normal_test_bypass_is_disabled(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'omd']));
        $plant = Plant::create(['name' => 'Unit']);
        $line = $plant->lines()->create(['name' => 'AS Unit']);

        // Laravel bypasses CSRF in tests by default; exercise its production check here.
        $this->app->bind(ValidateCsrfToken::class, fn ($app) => new class($app, $app['encrypter']) extends ValidateCsrfToken
        {
            protected function runningUnitTests()
            {
                return false;
            }
        });

        $this->putJson(route('omd.master.plant.update', $plant), ['name' => 'Changed'])
            ->assertStatus(419);
        $this->putJson(route('omd.master.line.update', $line), [
            'plant_id' => $plant->id,
            'name' => 'Changed',
        ])->assertStatus(419);

        $this->assertSame('Unit', $plant->fresh()->name);
        $this->assertSame('AS Unit', $line->fresh()->name);

        $this->withSession(['_token' => 'plant-line-test-token'])
            ->putJson(route('omd.master.plant.update', $plant), [
                '_token' => 'plant-line-test-token',
                'name' => 'Unit Baru',
            ])->assertOk();
    }
}
