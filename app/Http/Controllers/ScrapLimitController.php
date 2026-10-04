<?php

namespace App\Http\Controllers;

use App\Models\Line;
use App\Models\MasterModel;
use App\Models\ProductScrapLimit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ScrapLimitController extends Controller
{
    public function index(Request $request)
    {
        $lineFilter = (string) $request->query('line_id', 'all');
        $lineId = ctype_digit($lineFilter) ? (int) $lineFilter : null;
        $search = trim((string) $request->query('q', ''));
        $now = now();
        $today = $now->toDateString();

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
                        })
                        ->orWhereHas('products', function ($productQuery) use ($search) {
                            $productQuery->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->with([
                'line.plant',
                'products' => fn ($query) => $query
                    ->where('is_active', true)
                    ->orderBy('name'),
                'products.scrapLimits' => fn ($query) => $query
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
            'now',
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
            'limits' => [
                'required',
                'array',
                'min:1',
            ],
            'limits.*' => [
                'nullable',
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

        $model = MasterModel::query()
            ->with(['products' => fn ($query) => $query->where('is_active', true)])
            ->findOrFail($data['master_model_id']);

        $allowedProducts = $model->products->keyBy('id');
        $submittedLimits = collect($data['limits'])
            ->filter(fn ($value) => $value !== null && $value !== '')
            ->mapWithKeys(function ($value, $productId) use ($allowedProducts) {
                $productId = (int) $productId;

                if (! $allowedProducts->has($productId)) {
                    return [];
                }

                return [$productId => (int) $value];
            });

        if ($submittedLimits->isEmpty()) {
            throw ValidationException::withMessages([
                'limits' => 'Isi minimal satu Qty Limit Scrap.',
            ]);
        }

        $selectedDate = Carbon::parse($data['effective_from']);
        $effectiveFrom = $selectedDate->isToday()
            ? now()->startOfSecond()
            : $selectedDate->startOfDay();

        $changed = DB::transaction(function () use ($submittedLimits, $effectiveFrom, $data) {
            $changedCount = 0;

            foreach ($submittedLimits as $productId => $limitQty) {
                $history = ProductScrapLimit::query()
                    ->where('product_id', $productId)
                    ->lockForUpdate()
                    ->orderBy('effective_from')
                    ->orderBy('id')
                    ->get();

                $active = $history
                    ->filter(function ($limit) use ($effectiveFrom) {
                        return $limit->effective_from->lte($effectiveFrom)
                            && ($limit->effective_to === null || $limit->effective_to->gte($effectiveFrom));
                    })
                    ->sortByDesc(fn ($limit) => sprintf(
                        '%s-%020d',
                        $limit->effective_from->format('Y-m-d H:i:s'),
                        $limit->id
                    ))
                    ->first();

                if ($active && (int) $active->limit_qty === (int) $limitQty) {
                    continue;
                }

                // Hindari interval negatif bila user menyimpan koreksi dua kali
                // pada detik efektif yang sama.
                if ($active && $active->effective_from->equalTo($effectiveFrom)) {
                    $active->update([
                        'limit_qty' => (int) $limitQty,
                        'source' => ProductScrapLimit::SOURCE_OMD,
                        'note' => $data['note'] ?? null,
                        'created_by' => auth()->id(),
                    ]);

                    $changedCount++;
                    continue;
                }

                $next = $history
                    ->filter(fn ($limit) => $limit->effective_from->gt($effectiveFrom))
                    ->sortBy('effective_from')
                    ->first();

                if ($active) {
                    $active->update([
                        'effective_to' => $effectiveFrom->copy()->subSecond(),
                    ]);
                }

                ProductScrapLimit::create([
                    'product_id' => $productId,
                    'limit_qty' => (int) $limitQty,
                    'effective_from' => $effectiveFrom,
                    'effective_to' => $next
                        ? $next->effective_from->copy()->subSecond()
                        : null,
                    'source' => ProductScrapLimit::SOURCE_OMD,
                    'note' => $data['note'] ?? null,
                    'created_by' => auth()->id(),
                ]);

                $changedCount++;
            }

            return $changedCount;
        });

        $returnLine = (string) $request->input('return_line', 'all');
        if ($returnLine !== 'all' && ! ctype_digit($returnLine)) {
            $returnLine = (string) $model->line_id;
        }

        $message = $changed > 0
            ? $changed . ' Scrap Limit produk berhasil diperbarui.'
            : 'Tidak ada perubahan Scrap Limit.';

        return redirect()
            ->route('omd.scrap-limits.index', array_filter([
                'line_id' => $returnLine,
                'q' => trim((string) $request->input('return_q', '')) ?: null,
            ], fn ($value) => $value !== null && $value !== ''))
            ->with('success', $message);
    }
}
