<?php

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
});
