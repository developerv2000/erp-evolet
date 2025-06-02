<?php

use App\Http\Controllers\PLPD\PLPDOrderController;
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

        Route::post('/toggle-is-sent-to-bdm-attribute', 'toggleIsSentToBDMAttribute');  // AJAX request
        Route::post('/toggle-is-confirmed-attribute', 'toggleIsConfirmedAttribute');  // AJAX request

        Route::post('/get-ready-for-order-processes-of-manufacturer', 'getReadyForOrderProcessesOfManufacturer');  // AJAX request on create/edit
        Route::post('/get-available-mahs-of-ready-for-order-process', 'getAvailableMAHsOfReadyForOrderProcess');  // AJAX request on create/edit

        Route::post('/export-as-excel', 'exportAsExcel')->name('export-as-excel')->middleware('can:export-records-as-excel');
    });
});
