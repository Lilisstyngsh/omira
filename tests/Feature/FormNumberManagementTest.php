<?php

namespace Tests\Feature;

use App\Models\FormNumber;
use App\Models\FormNumberHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormNumberManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_migration_preserves_existing_form_number_as_active(): void
    {
        $this->assertDatabaseHas('form_numbers', [
            'number' => 'FRM-PSD-P3-057-00',
            'is_active' => 1,
        ]);
        $this->assertSame('FRM-PSD-P3-057-00', FormNumber::activeNumber());
    }

    public function test_omd_can_change_active_number_without_deleting_old_number_or_history(): void
    {
        $omd = User::factory()->create(['role' => 'omd']);
        $old = FormNumber::where('is_active', true)->firstOrFail();

        $this->actingAs($omd)->post(route('omd.master.form-numbers.store'), [
            'number' => 'FRM-PSD-P3-057-01',
            'description' => 'Revisi dokumen form',
            'is_active' => '1',
        ])->assertSessionHasNoErrors();

        $new = FormNumber::where('number', 'FRM-PSD-P3-057-01')->firstOrFail();
        $this->assertTrue($new->is_active);
        $this->assertFalse($old->fresh()->is_active);
        $this->assertDatabaseHas('form_numbers', ['id' => $old->id]);
        $this->assertSame('FRM-PSD-P3-057-01', FormNumber::activeNumber());
        $this->assertSame(1, FormNumber::where('is_active', true)->count());
        $this->assertDatabaseHas('form_number_histories', [
            'form_number_id' => $old->id, 'action' => 'deactivated', 'changed_by' => $omd->id,
        ]);
        $this->assertDatabaseHas('form_number_histories', [
            'form_number_id' => $new->id, 'action' => 'created', 'changed_by' => $omd->id,
        ]);
    }

    public function test_omd_can_edit_active_number_repeatedly(): void
    {
        $omd = User::factory()->create(['role' => 'omd']);
        $form = FormNumber::where('is_active', true)->firstOrFail();

        foreach (['FRM-PSD-P3-057-02', 'FRM-PSD-P3-057-03'] as $newNumber) {
            $this->actingAs($omd)->put(route('omd.master.form-numbers.update', $form), [
                'number' => $newNumber,
                'description' => 'Perbarui kode dokumen',
                'is_active' => '1',
            ])->assertSessionHasNoErrors();
        }

        $this->assertSame('FRM-PSD-P3-057-03', FormNumber::activeNumber());
        $this->assertSame(2, FormNumberHistory::where('form_number_id', $form->id)->where('action', 'updated')->count());
    }

    public function test_active_number_cannot_be_archived_or_deactivated(): void
    {
        $omd = User::factory()->create(['role' => 'omd']);
        $active = FormNumber::where('is_active', true)->firstOrFail();
        $this->actingAs($omd)->delete(route('omd.master.form-numbers.destroy', $active))
            ->assertSessionHasErrors('is_active');
        $this->actingAs($omd)->put(route('omd.master.form-numbers.update', $active), [
            'number' => $active->number,
            'description' => $active->description,
            'is_active' => '0',
        ])->assertSessionHasErrors('is_active');
        $this->assertTrue($active->fresh()->is_active);
    }

    public function test_inactive_number_is_archived_without_deleting_its_row_or_history(): void
    {
        $omd = User::factory()->create(['role' => 'omd']);
        $inactive = FormNumber::create(['number' => 'FRM-PSD-P3-057-04', 'is_active' => false]);
        $this->actingAs($omd)->delete(route('omd.master.form-numbers.destroy', $inactive))
            ->assertSessionHasNoErrors();
        $this->assertSoftDeleted('form_numbers', ['id' => $inactive->id]);
        $this->assertDatabaseHas('form_number_histories', [
            'form_number_id' => $inactive->id, 'action' => 'archived',
        ]);
    }

    public function test_user_cannot_access_or_mutate_form_number_master(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user)->get(route('omd.master.form-numbers.index'))->assertForbidden();
        $this->actingAs($user)->post(route('omd.master.form-numbers.store'), [
            'number' => 'FRM-PSD-P3-057-05', 'is_active' => '1',
        ])->assertForbidden();
    }

    public function test_preview_routes_reuse_pdf_download_with_inline_flag(): void
    {
        $userUrl = route('user.orders.history.export', [
            'order' => 7, 'format' => 'pdf', 'preview' => 1,
        ]);
        $omdUrl = route('omd.orders.history.export', [
            'order' => 7, 'format' => 'pdf', 'preview' => 1,
        ]);
        $this->assertStringContainsString('preview=1', $userUrl);
        $this->assertStringContainsString('preview=1', $omdUrl);
    }
}
