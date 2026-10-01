<?php

namespace App\Http\Controllers;

use App\Models\Line;
use App\Models\Target;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TargetController extends Controller
{
    public function index(Request $request)
    {
        $year = (int) $request->input('year', now()->year);
        $lineId = $request->input('line_id');

        $lines = Line::query()
            ->orderBy('name')
            ->get();

        $targets = Target::query()
            ->where('year', $year)
            ->when(
                $lineId,
                fn ($query) => $query->where('line_id', $lineId)
            )
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        return view('omd.targets.index', compact(
            'year',
            'lineId',
            'lines',
            'targets'
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
                'required',
                'integer',
                'exists:lines,id',
            ],

            'targets' => [
                'required',
                'array',
                'size:12',
            ],

            'targets.*.target_qty' => [
                'required',
                'integer',
                'min:0',
            ],

            'targets.*.scrap_limit' => [
                'required',
                'integer',
                'min:0',
            ],
        ]);

        DB::transaction(function () use ($data) {
            foreach ($data['targets'] as $month => $values) {
                Target::updateOrCreate(
                    [
                        'year' => $data['year'],
                        'month' => (int) $month,
                        'line_id' => $data['line_id'],
                    ],
                    [
                        'target_qty' => (int) $values['target_qty'],
                        'scrap_limit' => (int) $values['scrap_limit'],
                    ]
                );
            }
        });

        return redirect()
            ->route('omd.targets.index', [
                'year' => $data['year'],
                'line_id' => $data['line_id'],
            ])
            ->with(
                'success',
                'Target FY dan batas Scrap berhasil disimpan.'
            );
    }
}