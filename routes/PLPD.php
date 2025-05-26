<?php

use App\Http\Controllers\PLPD\PLPDOrderController;
use App\Http\Controllers\PLPD\PLPDOrderProductController;
use App\Http\Controllers\PLPD\PLPDReadyForOrderProcessController;
use App\Support\Generators\CRUDRouteGenerator;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'auth.session')->prefix('plpd')->name('plpd.')->group(function () {
    // Processes ready for order
    Route::prefix('/processes-ready-for-order')
        ->controller(PLPDReadyForOrderProcessController::class)
        ->name('processes.ready-for-order.')
        ->group(function () {
            Route::get('/', 'index')->name('index')->middleware('can:view-PLPD-ready-for-order-processes');
        });

    // Orders
    Route::prefix('/orders')->controller(PLPDOrderController::class)->name('orders.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesExcept(['show'], 'id', 'can:view-PLPD-orders', 'can:edit-PLPD-orders');

        Route::post('/export-as-excel', 'exportAsExcel')->name('export-as-excel')->middleware('can:export-records-as-excel');
    });

    // Order Products
    Route::prefix('/order-products')->controller(PLPDOrderProductController::class)->name('order-products.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesExcept(['show'], 'id', 'can:view-PLPD-order-products', 'can:edit-PLPD-order-products');

        Route::post('/export-as-excel', 'exportAsExcel')->name('export-as-excel')->middleware('can:export-records-as-excel');
    });
});
