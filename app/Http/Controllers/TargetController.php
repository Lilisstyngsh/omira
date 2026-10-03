<?php

namespace App\Http\Controllers;

use App\Models\FiscalYearTarget;
use App\Models\Line;
use App\Models\MonitoringAbnormality;
use App\Models\Target;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TargetController extends Controller
{
    public function index(Request $request)
    {
        $year = (int) $request->input('year', now()->year);
        $lineId = $request->filled('line_id')
            ? (int) $request->input('line_id')
            : null;
        $scopeKey = FiscalYearTarget::scopeKey($lineId);

        $lines = Line::query()
            ->with('plant')
            ->where('is_active', true)
            ->orderBy('plant_id')
            ->orderBy('name')
            ->get();

        $target = FiscalYearTarget::query()
            ->with(['line', 'updater'])
            ->where('year', $year)
            ->where('scope_key', $scopeKey)
            ->first();

        $targetHistory = FiscalYearTarget::query()
            ->with(['line', 'updater'])
            ->where('year', $year)
            ->orderByRaw("CASE WHEN line_id IS NULL THEN 0 ELSE 1 END")
            ->orderBy('line_id')
            ->get();

        $abnormalities = MonitoringAbnormality::query()
            ->with(['line', 'creator', 'updater'])
            ->where('metric', MonitoringAbnormality::METRIC_TARGET_FY)
            ->where('year', $year)
            ->where('scope_key', $scopeKey)
            ->orderByDesc('month')
            ->orderByDesc('updated_at')
            ->get();

        return view('omd.targets.index', compact(
            'year',
            'lineId',
            'scopeKey',
            'lines',
            'target',
            'targetHistory',
            'abnormalities'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'year' => [
                'required',
                'integer',
                'min:2020',
                'max:2100',
            ],
            'line_id' => [
                'nullable',
                'integer',
                Rule::exists('lines', 'id')->where(
                    fn ($query) => $query->where('is_active', true)
                ),
            ],
            'target_qty' => [
                'required',
                'integer',
                'min:0',
            ],
        ]);

        $lineId = isset($data['line_id'])
            ? (int) $data['line_id']
            : null;
        $scopeKey = FiscalYearTarget::scopeKey($lineId);
        $userId = $request->user()->id;

        DB::transaction(function () use ($data, $lineId, $scopeKey, $userId) {
            $target = FiscalYearTarget::query()
                ->where('year', (int) $data['year'])
                ->where('scope_key', $scopeKey)
                ->lockForUpdate()
                ->first();

            if ($target) {
                $target->update([
                    'line_id' => $lineId,
                    'target_qty' => (int) $data['target_qty'],
                    'updated_by' => $userId,
                ]);
            } else {
                FiscalYearTarget::create([
                    'year' => (int) $data['year'],
                    'scope_key' => $scopeKey,
                    'line_id' => $lineId,
                    'target_qty' => (int) $data['target_qty'],
                    'created_by' => $userId,
                    'updated_by' => $userId,
                ]);
            }

            /*
             * Compatibility bridge sementara untuk Dashboard lama.
             * Batch Dashboard berikutnya akan membaca fiscal_year_targets
             * secara langsung dan bridge ini dapat dihapus bersama tabel
             * targets legacy.
             */
            foreach (range(1, 12) as $month) {
                $legacyTarget = Target::query()
                    ->where('year', (int) $data['year'])
                    ->where('month', $month)
                    ->when(
                        $lineId,
                        fn ($query) => $query->where('line_id', $lineId),
                        fn ($query) => $query->whereNull('line_id')
                    )
                    ->first();

                if (! $legacyTarget) {
                    $legacyTarget = new Target([
                        'year' => (int) $data['year'],
                        'month' => $month,
                        'line_id' => $lineId,
                    ]);
                }

                $legacyTarget->target_qty = (int) $data['target_qty'];
                $legacyTarget->save();
            }
        });

        return redirect()
            ->route('omd.targets.index', [
                'year' => $data['year'],
                'line_id' => $lineId,
            ])
            ->with(
                'success',
                'Target FY berhasil disimpan. Nilai ini berlaku sebagai pembanding bulanan pada tahun dan scope yang dipilih.'
            );
    }
}
