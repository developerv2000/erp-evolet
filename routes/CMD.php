<?php

use App\Http\Controllers\CMD\CMDAttachedOrderInvoiceController;
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
    });

    // Order products
    Route::prefix('/orders/products')->controller(CMDOrderProductController::class)->name('order-products.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesOnly(['index', 'edit', 'update'], 'id', 'can:view-CMD-order-products', 'can:edit-CMD-order-products');

        Route::post('/export-as-excel', 'exportAsExcel')->name('export-as-excel')->middleware('can:export-records-as-excel');
    });

    // Attached order invoices
    Route::controller(CMDAttachedOrderInvoiceController::class)->name('attached-order-invoices.')->group(function () {
        Route::prefix('/orders/{order}/attached-invoices')->group(function () {
            CRUDRouteGenerator::defineDefaultRoutesOnly(
                ['index', 'create'],
                'id',
                'can:view-CMD-attached-order-invoices',
                'can:edit-CMD-attached-order-invoices'
            );
        });

        Route::prefix('/orders/attached-invoices')->group(function () {
            CRUDRouteGenerator::defineDefaultRoutesOnly(
                ['edit', 'update', 'delete'],
                'id',
                'can:view-CMD-attached-order-invoices',
                'can:edit-CMD-attached-order-invoices'
            );
        });
    });
});
