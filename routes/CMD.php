<?php

use App\Http\Controllers\CMD\CMDOrderController;
use App\Support\Generators\CRUDRouteGenerator;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'auth.session')->prefix('cmd')->name('cmd.')->group(function () {
    // Orders
    Route::prefix('/orders')->controller(CMDOrderController::class)->name('orders.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesOnly(['index', 'edit', 'update'], 'id', 'can:view-CMD-orders', 'can:edit-CMD-orders');

        Route::post('/toggle-is-sent-to-confirmation-attribute', 'toggleIsSentToConfirmationAttribute');  // AJAX request
        Route::post('/toggle-is-sent-to-manufacturer-attribute', 'toggleIsSentToManufacturerAttribute');  // AJAX request
    });
});
