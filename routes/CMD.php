<?php

use App\Http\Controllers\CMD\CMDOrderController;
use App\Support\Generators\CRUDRouteGenerator;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'auth.session')->prefix('cmd')->name('cmd.')->group(function () {
    // Orders
    Route::prefix('/orders')->controller(CMDOrderController::class)->name('orders.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesExcept(['show'], 'id', 'can:view-CMD-orders', 'can:edit-CMD-orders');

        Route::post('/toggle-is-sent-to-bdm-attribute', 'toggleIsSentToBDMAttribute');  // AJAX request
    });
});
