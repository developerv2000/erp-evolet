<?php

use App\Http\Controllers\CMD\CMDInvoiceController;
use App\Http\Controllers\CMD\CMDOrderController;
use App\Http\Controllers\CMD\CMDOrderProductController;
use App\Support\Generators\CRUDRouteGenerator;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'auth.session')->prefix('cmd')->name('cmd.')->group(function () {
    // Orders
    Route::prefix('/orders')->controller(CMDOrderController::class)->name('orders.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesOnly(['index', 'edit', 'update'], 'id', 'can:view-CMD-orders', 'can:edit-CMD-orders');

        Route::post('/toggle-is-sent-to-confirmation-attribute', 'toggleIsSentToConfirmationAttribute');  // AJAX request
        Route::post('/toggle-is-sent-to-manufacturer-attribute', 'toggleIsSentToManufacturerAttribute');  // AJAX request

        Route::post('/toggle-production-is-started-attribute', 'toggleProductionIsStartedAttribute');  // AJAX request
    });

    // Order products
    Route::prefix('/orders/products')->controller(CMDOrderProductController::class)->name('order-products.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesOnly(['index', 'edit', 'update'], 'id', 'can:view-CMD-order-products', 'can:edit-CMD-order-products');

        Route::post('/toggle-production-is-finished-attribute', 'toggleProductionIsFinishedAttribute');  // AJAX request
        Route::post('/toggle-is-ready-for-shipment-attribute', 'toggleIsReadyForShipmentAttribute');  // AJAX request
    });

    // Order invoices
    Route::prefix('/orders/invoices')->controller(CMDInvoiceController::class)->name('invoices.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesExcept(['show', 'trash', 'restore'], 'id', 'can:view-CMD-invoices', 'can:edit-CMD-invoices');

        Route::post('/toggle-is-sent-for-payment-attribute', 'toggleIsSentForPaymentAttribute');  // AJAX request
    });
});
