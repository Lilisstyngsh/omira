<?php

namespace App\Http\Controllers;

use App\Models\Line;
use App\Models\MasterModel;
use App\Models\ModelScrapLimit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ScrapLimitController extends Controller
{
    public function index(Request $request)
    {
        $lineFilter = (string) $request->query('line_id', 'all');
        $lineId = ctype_digit($lineFilter) ? (int) $lineFilter : null;
        $search = trim((string) $request->query('q', ''));
        $today = now()->toDateString();

        $lines = Line::query()
            ->with('plant')
            ->where('is_active', true)
            ->orderBy('plant_id')
            ->orderBy('name')
            ->get();

        $models = MasterModel::query()
            ->where('is_active', true)
            ->when($lineId, fn ($query) => $query->where('line_id', $lineId))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('model', 'like', '%' . $search . '%')
                        ->orWhereHas('line', function ($lineQuery) use ($search) {
                            $lineQuery->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->with([
                'line.plant',
                'scrapLimits' => fn ($query) => $query
                    ->with('creator')
                    ->orderByDesc('effective_from')
                    ->orderByDesc('id'),
            ])
            ->orderBy('line_id')
            ->orderBy('model')
            ->get();

        return view('omd.scrap-limits.index', compact(
            'lineFilter',
            'lineId',
            'search',
            'lines',
            'models',
            'today'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'master_model_id' => [
                'required',
                'integer',
                'exists:master_models,id',
            ],
            'limit_qty' => [
                'required',
                'integer',
                'min:0',
            ],
            'effective_from' => [
                'required',
                'date',
            ],
            'note' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $model = MasterModel::query()->findOrFail($data['master_model_id']);
        $effectiveFrom = Carbon::parse($data['effective_from'])->startOfDay();

        DB::transaction(function () use ($data, $model, $effectiveFrom) {
            $duplicate = ModelScrapLimit::query()
                ->where('master_model_id', $model->id)
                ->whereDate('effective_from', $effectiveFrom->toDateString())
                ->exists();

            if ($duplicate) {
                throw ValidationException::withMessages([
                    'effective_from' => 'Tanggal mulai tersebut sudah memiliki Scrap Limit untuk model ini.',
                ]);
            }

            $previous = ModelScrapLimit::query()
                ->where('master_model_id', $model->id)
                ->whereDate('effective_from', '<', $effectiveFrom->toDateString())
                ->orderByDesc('effective_from')
                ->lockForUpdate()
                ->first();

            $next = ModelScrapLimit::query()
                ->where('master_model_id', $model->id)
                ->whereDate('effective_from', '>', $effectiveFrom->toDateString())
                ->orderBy('effective_from')
                ->lockForUpdate()
                ->first();

            if (
                $previous
                && (
                    $previous->effective_to === null
                    || $previous->effective_to->gte($effectiveFrom)
                )
            ) {
                $previous->update([
                    'effective_to' => $effectiveFrom->copy()->subDay()->toDateString(),
                ]);
            }

            ModelScrapLimit::create([
                'master_model_id' => $model->id,
                'limit_qty' => (int) $data['limit_qty'],
                'effective_from' => $effectiveFrom->toDateString(),
                'effective_to' => $next
                    ? Carbon::parse($next->effective_from)->subDay()->toDateString()
                    : null,
                'note' => $data['note'] ?? null,
                'created_by' => auth()->id(),
            ]);
        });

        $returnLine = (string) $request->input('return_line', 'all');
        if ($returnLine !== 'all' && !ctype_digit($returnLine)) {
            $returnLine = (string) $model->line_id;
        }

        return redirect()
            ->route('omd.scrap-limits.index', array_filter([
                'line_id' => $returnLine,
                'q' => trim((string) $request->input('return_q', '')) ?: null,
            ], fn ($value) => $value !== null && $value !== ''))
            ->with('success', 'Scrap Limit berhasil disimpan.');
    }
}
