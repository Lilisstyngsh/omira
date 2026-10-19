<?php

namespace App\Http\Controllers;

use App\Models\Line;
use App\Models\UserScrapLimitCycle;
use App\Services\UserScrapLimitService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OmdScrapCycleController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'start_date' => ['nullable', 'date_format:Y-m-d'],
            'end_date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            'line_id' => ['nullable', 'integer', 'exists:lines,id'],
            'search' => ['nullable', 'string', 'max:150'],
            'per_page' => ['nullable', Rule::in([10, 25, 50, 100])],
        ]);

        $search = trim($filters['search'] ?? '');
        $cycles = UserScrapLimitCycle::query()
            ->whereNotNull('reached_at')
            ->with(['user', 'product.masterModel.line.plant', 'line', 'resetter'])
            ->when(!empty($filters['start_date']), fn ($query) => $query->whereDate('reached_at', '>=', $filters['start_date']))
            ->when(!empty($filters['end_date']), fn ($query) => $query->whereDate('reached_at', '<=', $filters['end_date']))
            ->when(!empty($filters['line_id']), fn ($query) => $query->where('line_id', $filters['line_id']))
            ->when($search !== '', function ($query) use ($search) {
                $keyword = '%' . $search . '%';
                $query->where(function ($matching) use ($keyword) {
                    $matching->whereHas('user', fn ($user) => $user->where('name', 'like', $keyword))
                        ->orWhereHas('line', fn ($line) => $line->where('name', 'like', $keyword))
                        ->orWhereHas('product', fn ($product) => $product->where('name', 'like', $keyword))
                        ->orWhereHas('product.masterModel', fn ($model) => $model->where('model', 'like', $keyword))
                        ->orWhereHas('product.masterModel.line.plant', fn ($plant) => $plant->where('name', 'like', $keyword));
                });
            })
            ->orderByDesc('reached_at')
            ->orderByDesc('id')
            ->paginate((int) ($filters['per_page'] ?? 10))
            ->withQueryString();

        $lines = Line::query()->with('plant')->orderBy('name')->get();

        return view('omd.scrap-limits.cycles', compact('cycles', 'lines'));
    }

    public function reset(Request $request, UserScrapLimitCycle $cycle, UserScrapLimitService $service)
    {
        $data = $request->validate(['reason' => ['required', 'string', 'min:5', 'max:1000']]);
        $service->reset($cycle, $request->user(), trim($data['reason']));

        return back()->with('success', 'Scrap Limit direset ke default master. Riwayat sebelumnya tetap disimpan.');
    }
}
