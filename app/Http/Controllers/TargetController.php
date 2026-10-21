<?php

namespace App\Http\Controllers;

use App\Models\FiscalYearTarget;
use App\Models\MonitoringAbnormality;
use App\Models\Target;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TargetController extends Controller
{
    public function index()
    {
        $currentYear = now()->year;
        $globalScopeKey = FiscalYearTarget::scopeKey(null);

        $targetHistory = FiscalYearTarget::query()
            ->with(['creator', 'updater'])
            ->where('scope_key', $globalScopeKey)
            ->orderByDesc('year')
            ->get();

        $abnormalities = MonitoringAbnormality::query()
            ->with(['creator', 'updater'])
            ->where('metric', MonitoringAbnormality::METRIC_TARGET_FY)
            ->where('scope_key', $globalScopeKey)
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->orderByDesc('created_at')
            ->get();

        return view('omd.targets.index', compact(
            'currentYear',
            'targetHistory',
            'abnormalities'
        ));
    }

    public function abnormalityArchive(Request $request)
    {
        $data = $request->validate(['year' => ['nullable', 'integer', 'between:2000,2100']]);
        $year = (int) ($data['year'] ?? (now()->month >= 4 ? now()->year : now()->year - 1));
        $query = MonitoringAbnormality::query()
            ->where('metric', MonitoringAbnormality::METRIC_TARGET_FY)
            ->where('scope_key', FiscalYearTarget::scopeKey(null));
        $years = (clone $query)->distinct()->pluck('year')->push($year)->unique()->sortDesc()->values();
        $abnormalities = (clone $query)->where('year', $year)
            ->orderByRaw('CASE WHEN month >= 4 THEN month ELSE month + 12 END')->get();
        return view('omd.targets.abnormality-archive', compact('year', 'years', 'abnormalities'));
    }

    public function store(Request $request)
    {
        $currentYear = now()->year;

        $data = $request->validate([
            'year' => [
                'required',
                'integer',
                'min:' . $currentYear,
                'max:2100',
            ],
            'target_qty' => [
                'required',
                'integer',
                'min:0',
            ],
        ], [
            'year.min' => 'Target FY hanya dapat ditambah atau diperbarui untuk tahun berjalan dan tahun berikutnya.',
        ]);

        $year = (int) $data['year'];
        $targetQty = (int) $data['target_qty'];
        $scopeKey = FiscalYearTarget::scopeKey(null);
        $userId = $request->user()->id;

        DB::transaction(function () use ($year, $targetQty, $scopeKey, $userId) {
            $target = FiscalYearTarget::query()
                ->where('year', $year)
                ->where('scope_key', $scopeKey)
                ->lockForUpdate()
                ->first();

            if ($target) {
                $target->update([
                    'line_id' => null,
                    'target_qty' => $targetQty,
                    'updated_by' => $userId,
                ]);
            } else {
                $target = FiscalYearTarget::create([
                    'year' => $year,
                    'scope_key' => $scopeKey,
                    'line_id' => null,
                    'target_qty' => $targetQty,
                    'created_by' => $userId,
                    'updated_by' => $userId,
                ]);
            }

            /*
             * Sinkronisasi tabel legacy agar modul lama yang masih membaca
             * targets tetap menerima target global terbaru untuk tahun tersebut.
             */
            foreach (range(1, 12) as $month) {
                Target::query()->updateOrCreate(
                    [
                        'year' => $year,
                        'month' => $month,
                        'line_id' => null,
                    ],
                    [
                        'target_qty' => $targetQty,
                    ]
                );
            }

            DB::table('monitoring_abnormalities')
                ->where('metric', MonitoringAbnormality::METRIC_TARGET_FY)
                ->where('year', $year)
                ->where('scope_key', $scopeKey)
                ->update([
                    'fiscal_year_target_id' => $target->id,
                    'threshold_qty' => $targetQty,
                ]);
        });

        return redirect()
            ->route('omd.targets.index')
            ->with(
                'success',
                'Target FY berhasil disimpan dan berlaku untuk seluruh Plant & Line.'
            );
    }
}
