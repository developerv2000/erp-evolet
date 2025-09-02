<?php

use App\Http\Controllers\ELD\ELDInvoiceController;
use App\Http\Controllers\ELD\ELDOrderProductController;
use App\Support\Generators\CRUDRouteGenerator;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'auth.session')->prefix('eld')->name('eld.')->group(function () {
    // Order products
    Route::prefix('/orders/products')->controller(ELDOrderProductController::class)->name('order-products.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesOnly(
            ['index', 'edit', 'update'],
            'id',
            'can:view-ELD-order-products',
            'can:edit-ELD-order-products'
        );
    });

    // Invoices
    Route::prefix('/invoices')->controller(ELDInvoiceController::class)->name('invoices.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesOnly(
            ['index', 'edit', 'update'],
            'id',
            'can:view-ELD-invoices',
            'can:edit-ELD-invoices'
        );
    });
});
