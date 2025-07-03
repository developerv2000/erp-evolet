<?php

use App\Http\Controllers\PRD\PRDInvoiceController;
use App\Http\Controllers\PRD\PRDOrderController;
use App\Http\Controllers\PRD\PRDOrderProductController;
use App\Support\Generators\CRUDRouteGenerator;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'auth.session')->prefix('prd')->name('prd.')->group(function () {
    // Orders
    Route::prefix('/orders')->controller(PRDOrderController::class)->name('orders.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesOnly(['index', 'edit', 'update'], 'id', 'can:view-PRD-orders', 'can:edit-PRD-orders');
    });

    // Order products
    Route::prefix('/orders/products')->controller(PRDOrderProductController::class)->name('order-products.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesOnly(['index', 'edit', 'update'], 'id', 'can:view-PRD-order-products', 'can:edit-PRD-order-products');

        Route::post('/export-as-excel', 'exportAsExcel')->name('export-as-excel')->middleware('can:export-records-as-excel');
    });

    // Order invoices
    Route::prefix('/orders/invoices')->controller(PRDInvoiceController::class)->name('invoices.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesOnly(['index', 'edit', 'update'], 'id', 'can:view-PRD-invoices', 'can:edit-PRD-invoices');
    });
});
