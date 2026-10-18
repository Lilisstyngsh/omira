<?php

namespace Tests\Feature;

use App\Models\FiscalYearTarget;
use App\Models\MonitoringAbnormality;
use App\Models\RepairMonthlySnapshot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MonitoringAbnormalityTest extends TestCase
{
    use RefreshDatabase;

    private function existingAbnormality(User $creator, int $year = 2026, int $month = 10): MonitoringAbnormality
    {
        return MonitoringAbnormality::create([
            'metric' => MonitoringAbnormality::METRIC_TARGET_FY,
            'year' => $year,
            'month' => $month,
            'scope_key' => FiscalYearTarget::scopeKey(null),
            'line_id' => null,
            'actual_qty' => 75,
            'threshold_qty' => 50,
            'reason' => 'Alasan awal abnormality.',
            'created_by' => $creator->id,
            'updated_by' => $creator->id,
        ]);
    }

    public function test_omd_can_edit_existing_reason_repeatedly_without_creating_new_record(): void
    {
        $creator = User::factory()->create(['role' => 'omd']);
        $editor = User::factory()->create(['role' => 'omd']);
        $entry = $this->existingAbnormality($creator);

        // Tidak ada target aktif: alasan lama harus tetap bisa diedit.
        foreach (['Perbaikan pertama pada alasan.', 'Perbaikan kedua pada alasan.'] as $reason) {
            $this->actingAs($editor)->post(route('omd.dashboard.abnormality.store'), [
                'year' => 2026,
                'month' => 10,
                'reason' => $reason,
            ])->assertRedirect(route('dashboard', ['year' => 2026]));
        }

        $this->assertDatabaseCount('monitoring_abnormalities', 1);
        $entry->refresh();
        $this->assertSame('Perbaikan kedua pada alasan.', $entry->reason);
        $this->assertSame($creator->id, $entry->created_by);
        $this->assertSame($editor->id, $entry->updated_by);
        $this->assertSame(75, $entry->actual_qty);
        $this->assertSame(50, $entry->threshold_qty);
    }

    public function test_existing_january_reason_redirects_to_correct_fiscal_year(): void
    {
        $omd = User::factory()->create(['role' => 'omd']);
        $entry = $this->existingAbnormality($omd, 2027, 1);

        $this->actingAs($omd)->post(route('omd.dashboard.abnormality.store'), [
            'year' => 2027,
            'month' => 1,
            'reason' => 'Alasan sudah diperbaiki.',
        ])->assertRedirect(route('dashboard', ['year' => 2026]));

        $this->assertSame('Alasan sudah diperbaiki.', $entry->fresh()->reason);
    }

    public function test_short_reason_is_rejected_without_modifying_existing_abnormality(): void
    {
        $omd = User::factory()->create(['role' => 'omd']);
        $entry = $this->existingAbnormality($omd);

        $this->actingAs($omd)->from(route('dashboard'))->post(route('omd.dashboard.abnormality.store'), [
            'year' => 2026,
            'month' => 10,
            'reason' => 'bad',
        ])->assertSessionHasErrors('reason');

        $this->assertSame('Alasan awal abnormality.', $entry->fresh()->reason);
        $this->assertDatabaseCount('monitoring_abnormalities', 1);
    }

    public function test_september_snapshot_over_target_is_clickable_without_seeded_reason(): void
    {
        \Illuminate\Support\Carbon::setTestNow('2026-10-19 10:00:00');
        try {
            $omd = User::factory()->create(['role' => 'omd']);
            FiscalYearTarget::create([
                'year' => 2026,
                'scope_key' => FiscalYearTarget::scopeKey(null),
                'target_qty' => 855,
                'created_by' => $omd->id,
                'updated_by' => $omd->id,
            ]);
            RepairMonthlySnapshot::create([
                'year' => 2026, 'month' => 9,
                'order_qty' => 992, 'ok_qty' => 953, 'scrap_qty' => 39,
                'target_qty' => 855, 'source' => 'test',
            ]);

            $this->actingAs($omd)->get(route('dashboard', ['year' => 2026]))
                ->assertOk()
                ->assertSee('data-abnormality-month-number="9"', false)
                ->assertSee('data-abnormality-actual="992"', false)
                ->assertSee('data-abnormality-existing="0"', false);

            $this->assertDatabaseCount('monitoring_abnormalities', 0);

            $this->actingAs($omd)->post(route('omd.dashboard.abnormality.store'), [
                'year' => 2026, 'month' => 9,
                'reason' => 'Penyebab abnormality September.',
            ])->assertRedirect(route('dashboard', ['year' => 2026]));

            $entry = MonitoringAbnormality::query()->where('year', 2026)->where('month', 9)->sole();
            $this->assertSame(992, $entry->actual_qty);
            $this->assertSame(855, $entry->threshold_qty);

            $this->actingAs($omd)->post(route('omd.dashboard.abnormality.store'), [
                'year' => 2026, 'month' => 9,
                'reason' => 'Penyebab September diperbarui.',
            ])->assertRedirect(route('dashboard', ['year' => 2026]));

            $this->assertDatabaseCount('monitoring_abnormalities', 1);
            $this->assertSame('Penyebab September diperbarui.', $entry->fresh()->reason);
        } finally {
            \Illuminate\Support\Carbon::setTestNow();
        }
    }

}
