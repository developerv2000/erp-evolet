<?php

use App\Http\Controllers\DD\DDOrderController;
use App\Support\Generators\CRUDRouteGenerator;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'auth.session')->prefix('dd')->name('dd.')->group(function () {
    // Orders
    Route::prefix('/orders')->controller(DDOrderController::class)->name('orders.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesOnly(['index', 'edit', 'update'], 'id', 'can:view-DD-orders', 'can:edit-DD-orders');
    });
});
