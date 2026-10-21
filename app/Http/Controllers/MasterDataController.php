<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Models\Line;
use App\Models\MasterModel;
use App\Models\Product;
use App\Models\NgType;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Closure;


class MasterDataController extends Controller
{
    /** Nama master dibandingkan tanpa perbedaan kapital dan spasi berulang. */
    private function normalizeMasterName(Request $request): void
    {
        if (is_string($request->input('name'))) {
            $request->merge(['name' => Str::squish($request->input('name'))]);
        }
    }

    private function uniqueMasterName(string $type, ?int $ignoreId = null, ?int $plantId = null): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail) use ($type, $ignoreId, $plantId): void {
            if (!is_string($value) || $value === '') {
                return;
            }

            $query = $type === 'plant' ? Plant::query() : Line::query()->where('plant_id', $plantId);
            $needle = Str::lower(Str::squish($value));
            $duplicate = $query->get(['id', 'name'])->first(function ($row) use ($needle, $ignoreId) {
                return (int) $row->id !== $ignoreId && Str::lower(Str::squish($row->name)) === $needle;
            });

            if ($duplicate) {
                if ($type === 'plant') {
                    $fail("Plant {$value} sudah terdaftar.");
                } else {
                    $plantName = Plant::find($plantId)?->name ?? '-';
                    $fail("Line {$value} sudah terdaftar pada Plant {$plantName}.");
                }
            }
        };
    }

    public function plant()
    {
        $plants = Plant::orderBy('name')
            ->get();


        return view(
            'omd.master.plant',
            compact('plants')
        );
    }

    public function storePlant(Request $request)
    {
        $this->normalizeMasterName($request);
        $request->validate([
            'name' => ['required', 'string', 'max:100', $this->uniqueMasterName('plant')],
        ], ['name.required' => 'Nama Plant wajib diisi.']);


        Plant::create([

            'name' => $request->name,
            'is_active' => true

        ]);


        return back()
            ->with(
                'success',
                'Plant berhasil ditambahkan'
            );
    }

    public function updatePlant(
        Request $request,
        Plant $plant
    ) {
        $this->normalizeMasterName($request);
        $request->validate([
            'name' => ['required', 'string', 'max:100', $this->uniqueMasterName('plant', (int) $plant->id)],
        ], ['name.required' => 'Nama Plant wajib diisi.']);



        $plant->update([

            'name' => $request->name,

        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Plant berhasil diperbarui',
                'plant' => $plant->only(['id', 'name']),
            ]);
        }


        return back()
            ->with(
                'success',
                'Plant berhasil diperbarui'
            );
    }

    public function destroyPlant(Plant $plant)
    {
        DB::transaction(function () use ($plant) {

            $lines = $plant->lines()->get();

            foreach ($lines as $line) {

                $models = $line->masterModels()->get();

                foreach ($models as $model) {

                    $model->products()->delete();

                    $model->delete();
                }

                $line->delete();
            }

            $plant->delete();
        });

        return back()
            ->with(
                'success',
                'Plant berhasil dihapus'
            );
    }

    public function line(Request $request)
    {


        $plants = Plant::with(['lines' => fn($query) => $query->orderBy('name')])
            ->withCount('lines')
            ->orderBy('name')
            ->get();



        $selectedPlant = null;



        if ($request->plant) {

            $selectedPlant =
                $plants->firstWhere('id', $request->plant);
        }



        if (!$selectedPlant) {

            $selectedPlant =
                $plants->first();
        }



        $lines = $selectedPlant
            ? $selectedPlant->lines
            : collect();



        return view(
            'omd.master.line',
            compact(
                'plants',
                'selectedPlant',
                'lines'
            )
        );
    }

    public function storeLine(Request $request)
    {
        $this->normalizeMasterName($request);
        $validated = $request->validate([
            'plant_id' => [
                'required',
                'exists:plants,id'
            ],
            'name' => [
                'required',
                'string',
                'max:100',
                $this->uniqueMasterName('line', null, (int) $request->input('plant_id')),
            ]
        ], ['name.required' => 'Nama Line wajib diisi.']);

        Line::create([
            'plant_id' => $validated['plant_id'],
            'name' => $validated['name'],
            'is_active' => true
        ]);

        return redirect()
            ->route('omd.master.line', [
                'plant' => $validated['plant_id']
            ])
            ->with(
                'success',
                'Line berhasil ditambahkan'
            );
    }

    public function updateLine(
        Request $request,
        Line $line
    ) {
        $this->normalizeMasterName($request);
        $validated = $request->validate([
            'plant_id' => [
                'required',
                'exists:plants,id'
            ],

            'name' => [
                'required',
                'string',
                'max:100',
                $this->uniqueMasterName('line', (int) $line->id, (int) $request->input('plant_id')),
            ]
        ], ['name.required' => 'Nama Line wajib diisi.']);

        $line->update([
            'plant_id' => $validated['plant_id'],
            'name' => $validated['name']
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Line berhasil diperbarui',
                'line' => $line->only(['id', 'name', 'plant_id']),
            ]);
        }
        return redirect()
            ->route('omd.master.line', [
                'plant' => $line->plant_id
            ])
            ->with(
                'success',
                'Line berhasil diperbarui'
            );
    }

    public function destroyLine(Line $line)
    {
        if ($line->masterModels()->exists()) {
            return back()
                ->withErrors(
                    'Line tidak dapat dihapus karena masih memiliki Model'
                );
        }
        $plantId = $line->plant_id;

        $line->delete();

        return redirect()
            ->route('omd.master.line', [
                'plant' => $plantId
            ])
            ->with(
                'success',
                'Line berhasil dihapus'
            );
    }

    public function getLineByPlant(
        Plant $plant
    ) {


        $lines = $plant->lines()
            ->orderBy('name')
            ->get();



        return response()->json([

            'plant' => $plant->name,

            'lines' => $lines,

            'html' => view('omd.master.partials.line_table', [
                'lines' => $lines,
                'selectedPlant' => $plant,
            ])->render(),

        ]);
    }

    public function modelProduct(Request $request)
    {
        $filters = $request->validate([
            'plant_id' => ['nullable', 'integer', 'exists:plants,id'],
            'line_id' => ['nullable', 'integer', 'exists:lines,id'],
            'q' => ['nullable', 'string', 'max:150'],
            'per_page' => ['nullable', 'in:10,25,50,100'],
        ]);

        $selectedPlantId = $filters['plant_id'] ?? null;
        $selectedLineId = $filters['line_id'] ?? null;
        $search = trim($filters['q'] ?? '');
        $perPage = (int) ($filters['per_page'] ?? 10);

        $plants = Plant::query()->orderBy('name')->get();
        $lines = Line::query()->with('plant')->orderBy('name')->get();

        $models = MasterModel::query()
            ->with([
                'line.plant',
                'products' => fn($query) => $query->orderBy('name'),
                'products.scrapLimits' => fn($query) => $query
                    ->with('creator')
                    ->orderByDesc('effective_from')
                    ->orderByDesc('id'),
            ])
            ->when($selectedPlantId, fn($query) => $query->whereHas(
                'line',
                fn($line) => $line->where('plant_id', $selectedPlantId)
            ))
            ->when($selectedLineId, fn($query) => $query->where('line_id', $selectedLineId))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('model', 'like', '%' . $search . '%')
                        ->orWhereHas('products', fn($product) => $product
                            ->where('name', 'like', '%' . $search . '%'))
                        ->orWhereHas('line', fn($line) => $line
                            ->where('name', 'like', '%' . $search . '%')
                            ->orWhereHas('plant', fn($plant) => $plant
                                ->where('name', 'like', '%' . $search . '%')));
                });
            })
            ->orderBy('model')
            ->orderBy('id')
            ->paginate($perPage)
            ->withQueryString();

        return view('omd.master.model-product', compact(
            'models',
            'plants',
            'lines',
            'selectedPlantId',
            'selectedLineId',
            'search',
            'perPage'
        ));
    }

    public function storeModel(
        Request $request
    ) {
        $validated = $request->validate([
            'line_id' => [
                'required',
                'exists:lines,id'
            ],
            'model' => [
                'required',
                'string',
                'max:100'
            ]
        ]);

        MasterModel::create([
            'line_id' => $validated['line_id'],
            'model' => $validated['model'],
            'is_active' => true
        ]);

        return redirect(
            url('/omd/master/model-product?line_id=' . $validated['line_id'])
        )
            ->with(
                'success',
                'Model berhasil ditambahkan'
            );
    }







    public function updateModel(Request $request, MasterModel $model)
    {
        $validated = $request->validate([
            'line_id' => [
                'required',
                'exists:lines,id'
            ],
            'model' => [
                'required',
                'string',
                'max:100'
            ]
        ]);

        $model->update($validated);

        return back()
            ->with('success', 'Model berhasil diperbarui')
            ->with('selected_line_id', $validated['line_id']);
    }

    public function destroyModel(
        MasterModel $model
    ) {
        $lineId = $model->line_id;

        $model->delete();

        return redirect(
            url('/omd/master/model-product?line_id=' . $lineId)
        )
            ->with(
                'success',
                'Model dan produk berhasil dihapus'
            );
    }


    public function storeProduct(
        Request $request
    ) {


        $validated = $request->validate([


            'master_model_id' => [
                'required',
                'exists:master_models,id'
            ],


            'name' => [
                'required',
                'string',
                'max:150'
            ]

        ]);

        Product::create([

            'master_model_id' => $validated['master_model_id'],

            'name' => $validated['name'],

            'is_active' => true

        ]);

        return back()
            ->with(
                'success',
                'Produk berhasil ditambahkan'
            );
    }

    public function updateProduct(
        Request $request,
        Product $product
    ) {

        $validated = $request->validate([


            'master_model_id' => [
                'required',
                'exists:master_models,id'
            ],


            'name' => [
                'required',
                'string',
                'max:150'
            ]

        ]);



        $product->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Produk berhasil diperbarui',
                'product' => $product->only(['id', 'name', 'master_model_id']),
            ]);
        }


        return back()
            ->with(
                'success',
                'Produk berhasil diperbarui'
            );
    }


    public function destroyProduct(
        Product $product
    ) {


        $product->delete();



        return back()
            ->with(
                'success',
                'Produk berhasil dihapus'
            );
    }

    public function ngType()
    {
        $ngTypes = NgType::orderBy('code')
            ->get();

        return view(
            'omd.master.ng-type',
            compact('ngTypes')
        );
    }

    public function storeNgType(
        Request $request
    ) {


        $validated = $request->validate([


            'code' => [
                'required',
                'string',
                'max:5'
            ],


            'name' => [
                'required',
                'string',
                'max:100'
            ]


        ]);




        NgType::create([

            'code' => $validated['code'],

            'name' => $validated['name'],

            'is_active' => true

        ]);



        return back()
            ->with(
                'success',
                'Jenis NG berhasil ditambahkan'
            );
    }





    public function updateNgType(
        Request $request,
        NgType $ngType
    ) {


        $validated = $request->validate([

            'code' => [
                'required',
                'string',
                'max:5'
            ],

            'name' => [
                'required',
                'string',
                'max:100'
            ]

        ]);



        $ngType->update($validated);



        return back()
            ->with(
                'success',
                'Jenis NG berhasil diperbarui'
            );
    }






    public function destroyNgType(
        NgType $ngType
    ) {


        $ngType->delete();



        return back()
            ->with(
                'success',
                'Jenis NG berhasil dihapus'
            );
    }
}
