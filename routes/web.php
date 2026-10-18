<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OmdOrderController;
use App\Http\Controllers\OmdHistoryExportController;
use App\Http\Controllers\UserOrderController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\TargetController;
use App\Http\Controllers\ScrapLimitController;
use App\Http\Controllers\UserScrapLimitController;
use App\Http\Controllers\OmdScrapCycleController;
use App\Http\Controllers\RecapController;
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

    // Polling perubahan order sesuai role; hanya mengirim fingerprint data.
    Route::get('/activity-version', [\App\Http\Controllers\ActivityVersionController::class, 'show'])
        ->middleware('role:omd,user')
        ->name('activity.version');

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
*/


    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

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
        ->middleware('role:omd,user')
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
            | Order Repair - User
            |--------------------------------------------------------------------------
            */

            Route::get('/orders', [UserOrderController::class, 'index'])
                ->name('orders.index');

            Route::get('/orders/history', [UserOrderController::class, 'history'])
                ->name('orders.history');

            Route::get('/orders/history/{order}/export/{format}', [UserOrderController::class, 'exportHistory'])->where('format', 'pdf|excel')->name('orders.history.export');

            Route::get('/orders/history/{order}', [UserOrderController::class, 'historyShow'])
                ->name('orders.history.show');

            Route::get('/orders/pending-confirmation-count', [UserOrderController::class, 'pendingConfirmationCount'])
                ->name('orders.pendingConfirmationCount');

            Route::get('/orders/create', [UserOrderController::class, 'create'])
                ->name('orders.create');

            Route::get('/additional-box', [\App\Http\Controllers\AdditionalBoxRequestController::class, 'index'])->name('additional-box.index');
            Route::post('/additional-box', [\App\Http\Controllers\AdditionalBoxRequestController::class, 'store'])->name('additional-box.store');
            Route::get('/additional-box/{document}', [\App\Http\Controllers\AdditionalBoxRequestController::class, 'show'])->name('additional-box.show');
            Route::get('/additional-box/{document}/preview', [\App\Http\Controllers\AdditionalBoxRequestController::class, 'preview'])->name('additional-box.preview');
            Route::get('/additional-box/{document}/export/{format}', [\App\Http\Controllers\AdditionalBoxRequestController::class, 'export'])->where('format', 'pdf|excel')->name('additional-box.export');

            Route::post('/scrap-limit/{cycle}/decision', [UserScrapLimitController::class, 'decide'])
                ->name('scrap-limit.decision');

            Route::post('/orders', [UserOrderController::class, 'store'])
                ->name('orders.store');

            Route::get('/orders/{order}', [UserOrderController::class, 'show'])
                ->name('orders.show');

            Route::post('/orders/{order}/confirm', [UserOrderController::class, 'confirm'])
                ->name('orders.confirm');

            Route::post('/orders/{order}/request-revision', [UserOrderController::class, 'requestRevision'])
                ->name('orders.requestRevision');
        });


    /*
    |--------------------------------------------------------------------------
    | OMD Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:omd')
        ->prefix('omd')
        ->name('omd.')
        ->group(function () {


            /*
            |--------------------------------------------------------------------------
            | Manajemen Akun
            |--------------------------------------------------------------------------
            */

            Route::prefix('users')
                ->name('users.')
                ->group(function () {

                    Route::get(
                        '/',
                        [UserManagementController::class, 'index']
                    )->name('index');

                    Route::get(
                        '/create',
                        [UserManagementController::class, 'create']
                    )->name('create');

                    Route::post(
                        '/',
                        [UserManagementController::class, 'store']
                    )->name('store');

                    Route::get(
                        '/{user}/edit',
                        [UserManagementController::class, 'edit']
                    )->name('edit');

                    Route::put(
                        '/{user}',
                        [UserManagementController::class, 'update']
                    )->name('update');

                    Route::post(
                        '/{user}/reset-password',
                        [UserManagementController::class, 'resetPassword']
                    )->name('reset-password');

                    Route::delete(
                        '/{user}',
                        [UserManagementController::class, 'destroy']
                    )->name('destroy');
                });


            /*
            |--------------------------------------------------------------------------
            | Data Master
            |--------------------------------------------------------------------------
            */

            Route::prefix('master')
                ->name('master.')
                ->group(function () {

                    /*
                    |--------------------------------------------------------------------------
                    | PLANT
                    |--------------------------------------------------------------------------
                    */

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
                    | LINE
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
                    | MODEL & PRODUCT
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
                    | JENIS NG
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
            | Order Repair - OMD
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/orders',
                [OmdOrderController::class, 'index']
            )->name('orders.index');

            Route::get('/orders/history/{order}/export/{format}', [OmdHistoryExportController::class, 'exportHistory'])
                ->where('format', 'pdf|excel')->name('orders.history.export');

            Route::get(
                '/orders/history/{order}',
                [OmdOrderController::class, 'historyShow']
            )->name('orders.history.show');

            Route::get(
                '/orders/history',
                [OmdOrderController::class, 'history']
            )->name('orders.history');

            Route::get(
                '/orders/pending-count',
                [OmdOrderController::class, 'pendingCount']
            )->name('orders.pendingCount');

            Route::get(
                '/orders/{order}',
                [OmdOrderController::class, 'show']
            )->name('orders.show');

            Route::post(
                '/orders/{order}/verify',
                [OmdOrderController::class, 'verify']
            )->name('orders.verify');

            Route::post(
                '/orders/{order}/start-repair',
                [OmdOrderController::class, 'startRepair']
            )->name('orders.start');

            Route::post(
                '/orders/{order}/complete',
                [OmdOrderController::class, 'complete']
            )->name('orders.complete');

            Route::post(
                '/orders/{order}/handover',
                [OmdOrderController::class, 'handover']
            )->name('orders.handover');

            /*
            |--------------------------------------------------------------------------
            | Dashboard Monitoring
            |--------------------------------------------------------------------------
            */
            Route::post(
                '/dashboard/abnormality',
                [DashboardController::class, 'storeAbnormality']
            )->name('dashboard.abnormality.store');

            /*
            |--------------------------------------------------------------------------
            | Target
            |--------------------------------------------------------------------------
            */
            Route::get('/targets', [TargetController::class, 'index'])
                ->name('targets.index');

            Route::get('/targets/abnormality-archive', [TargetController::class, 'abnormalityArchive'])
                ->name('targets.abnormality.archive');

            Route::post('/targets', [TargetController::class, 'store'])
                ->name('targets.store');

            /*
            |--------------------------------------------------------------------------
            | Scrap Limit per Model
            |--------------------------------------------------------------------------
            */
            Route::get('/scrap-limit-history', [OmdScrapCycleController::class, 'index'])
                ->name('scrap-cycles.index');
            Route::post('/scrap-limit-history/{cycle}/reset', [OmdScrapCycleController::class, 'reset'])
                ->name('scrap-cycles.reset');

            Route::get('/scrap-limits', [ScrapLimitController::class, 'index'])
                ->name('scrap-limits.index');

            Route::post('/scrap-limits', [ScrapLimitController::class, 'store'])
                ->name('scrap-limits.store');

            /*
            |--------------------------------------------------------------------------
            | Recap
            |--------------------------------------------------------------------------
            */

            Route::get('/recap', [RecapController::class, 'index'])
                ->name('recap');

            Route::get('/recap/export/pdf', [RecapController::class, 'exportPdf'])
                ->name('recap.pdf');

            Route::get('/recap/chart-preview', [RecapController::class, 'chartPreview'])
                ->name('recap.chart-preview');

            Route::post('/recap/export/excel', [RecapController::class, 'exportExcel'])
                ->name('recap.excel');
        });
});
