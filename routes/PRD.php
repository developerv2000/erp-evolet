<?php

use App\Http\Controllers\PRD\PRDInvoiceController;
use App\Http\Controllers\PRD\PRDOrderController;
use App\Http\Controllers\PRD\PRDOrderProductController;
use App\Support\Generators\CRUDRouteGenerator;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'auth.session')->prefix('prd')->name('prd.')->group(function () {
    // Order invoices
    Route::prefix('/orders/invoices')->controller(PRDInvoiceController::class)->name('invoices.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesOnly(['index', 'edit', 'update'], 'id', 'can:view-PRD-invoices', 'can:edit-PRD-invoices');

        Route::post('/toggle-is-accepted-by-financier-attribute', 'toggleIsAcceptedByFinancierAttribute');  // AJAX request
        Route::post('/toggle-payment-is-completed-attribute', 'togglePaymentIsCompletedAttribute');  // AJAX request
    });

    // Orders
    Route::prefix('/orders')->controller(PRDOrderController::class)->name('orders.')->group(function () {
        Route::get('/', 'index')->middleware('can:view-PRD-orders')->name('index');
    });

    // Order products
    Route::prefix('/orders/products')->controller(PRDOrderProductController::class)->name('order-products.')->group(function () {
        Route::get('/', 'index')->middleware('can:view-PRD-order-products')->name('index');
    });
});
