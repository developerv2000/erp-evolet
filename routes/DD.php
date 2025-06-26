<?php

use App\Http\Controllers\DD\DDOrderProductController;
use App\Support\Generators\CRUDRouteGenerator;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'auth.session')->prefix('dd')->name('dd.')->group(function () {
    // Order products
    Route::prefix('/order-products')->controller(DDOrderProductController::class)->name('order-products.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesOnly(['index', 'edit', 'update'], 'id', 'can:view-DD-order-products', 'can:edit-DD-order-products');

        Route::post('/export-as-excel', 'exportAsExcel')->name('export-as-excel')->middleware('can:export-records-as-excel');
    });
});
