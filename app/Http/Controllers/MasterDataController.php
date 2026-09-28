<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Models\Line;
use App\Models\MasterModel;
use App\Models\Product;
use App\Models\NgType;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MasterDataController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | PLANT
    |--------------------------------------------------------------------------
    */

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

        $request->validate([

            'name' => 'required|max:100',

        ]);


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

        $request->validate([

            'name' => 'required|max:100',

        ]);



        $plant->update([

            'name' => $request->name,

        ]);



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

    /*
|--------------------------------------------------------------------------
| LINE
|--------------------------------------------------------------------------
*/


    public function line(Request $request)
    {


        $plants = Plant::withCount('lines')
            ->orderBy('name')
            ->get();



        $selectedPlant = null;



        if ($request->plant) {

            $selectedPlant =
                Plant::find($request->plant);
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
        $validated = $request->validate([
            'plant_id' => [
                'required',
                'exists:plants,id'
            ],
            'name' => [
                'required',
                'string',
                'max:100'
            ]
        ]);

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
        $validated = $request->validate([
            'plant_id' => [
                'required',
                'exists:plants,id'
            ],

            'name' => [
                'required',
                'string',
                'max:100'
            ]
        ]);

        $line->update([
            'plant_id' => $validated['plant_id'],
            'name' => $validated['name']
        ]);

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

            'lines' => $lines

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | MODEL & PRODUCT
    |--------------------------------------------------------------------------
    */



    public function modelProduct()
    {
        $models = MasterModel::with([
            'line.plant',
            'products'
        ])
            ->orderBy('model')
            ->get();

        $lines = Line::with('plant')
            ->orderBy('name')
            ->get();

        $selectedLineId = request()->query('line_id');

        if (!$selectedLineId) {
            $selectedLineId = $lines->first()?->id;
        }

        return view(
            'omd.master.model-product',
            compact(
                'models',
                'lines',
                'selectedLineId'
            )
        );
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







    /*
    |--------------------------------------------------------------------------
    | PRODUCT
    |--------------------------------------------------------------------------
    */



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






    /*
    |--------------------------------------------------------------------------
    | NG TYPE
    |--------------------------------------------------------------------------
    */



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
