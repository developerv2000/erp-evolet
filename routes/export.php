<?php

use App\Http\Controllers\export\ExportAssemblageController;
use App\Http\Controllers\export\ExportInvoiceController;
use App\Http\Controllers\export\ExportProductController;
use App\Support\Generators\CRUDRouteGenerator;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'auth.session')->prefix('export')->name('export.')->group(function () {
    // Assemblages
    Route::prefix('/assemblages')->controller(ExportAssemblageController::class)->name('assemblages.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesOnly(
            ['index', 'create', 'store', 'edit', 'update', 'destroy'],
            'id',
            'can:view-export-assemblages',
            'can:edit-export-assemblages'
        );
    });

    // Products
    Route::prefix('/products')->controller(ExportProductController::class)->name('products.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesOnly(
            ['index', 'create', 'store', 'edit', 'update'],
            'id',
            'can:view-export-products',
            'can:edit-export-products'
        );
    });

    // Invoices
    Route::prefix('/invoices')->controller(ExportInvoiceController::class)->name('invoices.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesOnly(
            ['index', 'create', 'store', 'edit', 'update'],
            'id',
            'can:view-export-invoices',
            'can:edit-export-invoices'
        );
    });
});
