<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OmdOrderController;
use App\Http\Controllers\UserOrderController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\TpsRepairController;
use App\Http\Controllers\MasterDataController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.store');
});


/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Root
    |--------------------------------------------------------------------------
    */

    Route::get('/', fn() => redirect()->route('dashboard'));


    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', function () {

        return auth()->user()->role === 'user'
            ? app(UserDashboardController::class)->index(request())
            : app(DashboardController::class)->index(request());
    })
        ->middleware('role:omd_leader,omd_member,user')
        ->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | User Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:user')
        ->prefix('user')
        ->name('user.')
        ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | TPS Tool - User
            |--------------------------------------------------------------------------
            */

            Route::get('/tps', [TpsRepairController::class, 'userIndex'])
                ->name('tps.index');

            Route::get('/tps/create', [TpsRepairController::class, 'create'])
                ->name('tps.create');

            Route::post('/tps', [TpsRepairController::class, 'store'])
                ->name('tps.store');

            Route::get('/tps/{order}', [TpsRepairController::class, 'userShow'])
                ->name('tps.show');

            Route::post('/tps/{order}/confirm', [TpsRepairController::class, 'confirm'])
                ->name('tps.confirm');


            /*
            |--------------------------------------------------------------------------
            | Order Repair - User
            |--------------------------------------------------------------------------
            */

            Route::get('/orders', [UserOrderController::class, 'index'])
                ->name('orders.index');

            Route::get('/orders/create', [UserOrderController::class, 'create'])
                ->name('orders.create');

            Route::post('/orders', [UserOrderController::class, 'store'])
                ->name('orders.store');

            Route::get('/orders/{order}', [UserOrderController::class, 'show'])
                ->name('orders.show');

            Route::post('/orders/{order}/confirm', [UserOrderController::class, 'confirm'])
                ->name('orders.confirm');
        });


    /*
    |--------------------------------------------------------------------------
    | OMD Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:omd_member,omd_leader')
        ->prefix('omd')
        ->name('omd.')
        ->group(function () {
            Route::middleware('role:omd_leader')
                ->prefix('users')
                ->name('users.')
                ->group(function () {

                    Route::get('/', [UserManagementController::class, 'index'])
                        ->name('index');

                    Route::get('/create', [UserManagementController::class, 'create'])
                        ->name('create');

                    Route::post('/', [UserManagementController::class, 'store'])
                        ->name('store');

                    Route::get('/{user}/edit', [UserManagementController::class, 'edit'])
                        ->name('edit');

                    Route::put('/{user}', [UserManagementController::class, 'update'])
                        ->name('update');

                    Route::delete('/{user}', [UserManagementController::class, 'destroy'])
                        ->name('destroy');
                });


            /*
|--------------------------------------------------------------------------
| Data Master
|--------------------------------------------------------------------------
|
| Struktur Baru:
|
| Plant
|   └── Line
|        └── Model
|             └── Product
|                  └── NG Type
|
*/


            Route::middleware('role:omd_leader')
                ->prefix('master')
                ->name('master.')
                ->group(function () {


                    // PLANT

                    Route::get(
                        '/plant',
                        [MasterDataController::class, 'plant']
                    )->name('plant');


                    Route::post(
                        '/plant',
                        [MasterDataController::class, 'storePlant']
                    )->name('plant.store');


                    Route::put(
                        '/plant/{plant}',
                        [MasterDataController::class, 'updatePlant']
                    )->name('plant.update');


                    Route::delete(
                        '/plant/{plant}',
                        [MasterDataController::class, 'destroyPlant']
                    )->name('plant.destroy');



                    /*
        |--------------------------------------------------------------------------
        | Line
        |--------------------------------------------------------------------------
        */
                    Route::get(
                        '/line/by-plant/{plant}',
                        [MasterDataController::class, 'getLineByPlant']
                    )->name('line.byPlant');

                    Route::get(
                        '/line',
                        [MasterDataController::class, 'line']
                    )->name('line');



                    Route::post(
                        '/line',
                        [MasterDataController::class, 'storeLine']
                    )->name('line.store');



                    Route::put(
                        '/line/{line}',
                        [MasterDataController::class, 'updateLine']
                    )->name('line.update');



                    Route::delete(
                        '/line/{line}',
                        [MasterDataController::class, 'destroyLine']
                    )->name('line.destroy');



                    /*
        |--------------------------------------------------------------------------
        | Model & Product
        |--------------------------------------------------------------------------
        */


                    Route::get(
                        '/model-product',
                        [MasterDataController::class, 'modelProduct']
                    )->name('model-product');



                    Route::post(
                        '/model',
                        [MasterDataController::class, 'storeModel']
                    )->name('model.store');



                    Route::put(
                        '/model/{model}',
                        [MasterDataController::class, 'updateModel']
                    )->name('model.update');



                    Route::delete(
                        '/model/{model}',
                        [MasterDataController::class, 'destroyModel']
                    )->name('model.destroy');




                    Route::post(
                        '/product',
                        [MasterDataController::class, 'storeProduct']
                    )->name('product.store');



                    Route::put(
                        '/product/{product}',
                        [MasterDataController::class, 'updateProduct']
                    )->name('product.update');



                    Route::delete(
                        '/product/{product}',
                        [MasterDataController::class, 'destroyProduct']
                    )->name('product.destroy');




                    /*
        |--------------------------------------------------------------------------
        | Jenis NG
        |--------------------------------------------------------------------------
        */


                    Route::get(
                        '/ng-type',
                        [MasterDataController::class, 'ngType']
                    )->name('ng-type');



                    Route::post(
                        '/ng-type',
                        [MasterDataController::class, 'storeNgType']
                    )->name('ng-type.store');



                    Route::put(
                        '/ng-type/{ngType}',
                        [MasterDataController::class, 'updateNgType']
                    )->name('ng-type.update');



                    Route::delete(
                        '/ng-type/{ngType}',
                        [MasterDataController::class, 'destroyNgType']
                    )->name('ng-type.destroy');
                });

            /*
            |--------------------------------------------------------------------------
            | TPS Tool - OMD
            |--------------------------------------------------------------------------
            */

            Route::get('/tps', [TpsRepairController::class, 'omdIndex'])
                ->name('tps.index');

            Route::get('/tps/{order}', [TpsRepairController::class, 'omdShow'])
                ->name('tps.show');

            Route::post('/tps/{order}/leader-check', [TpsRepairController::class, 'leaderCheck'])
                ->name('tps.leader-check');

            Route::post('/tps/{order}/verify', [TpsRepairController::class, 'verify'])
                ->name('tps.verify');

            Route::post('/tps/{order}/schedule', [TpsRepairController::class, 'schedule'])
                ->name('tps.schedule');

            Route::post('/tps/{order}/complete', [TpsRepairController::class, 'complete'])
                ->name('tps.complete');


            /*
            |--------------------------------------------------------------------------
            | Order Repair - OMD
            |--------------------------------------------------------------------------
            */

            Route::get('/orders', [OmdOrderController::class, 'index'])
                ->name('orders.index');

            Route::get('/orders/{order}', [OmdOrderController::class, 'show'])
                ->name('orders.show');

            Route::post('/orders/{order}/verify', [OmdOrderController::class, 'verify'])
                ->name('orders.verify');

            Route::post('/orders/{order}/start-repair', [OmdOrderController::class, 'startRepair'])
                ->name('orders.start');

            Route::post('/orders/{order}/complete', [OmdOrderController::class, 'complete'])
                ->name('orders.complete');


            /*
            |--------------------------------------------------------------------------
            | Recap
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/recap',
                fn() =>
                redirect()->route('dashboard')
            )
                ->name('recap');
        });
});
