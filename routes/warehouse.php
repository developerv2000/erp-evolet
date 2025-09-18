<?php

use App\Http\Controllers\warehouse\WarehouseProductBatchController;
use App\Http\Controllers\warehouse\WarehouseProductController;
use App\Support\Generators\CRUDRouteGenerator;
use Illuminate\Support\Facades\Route;

Route::prefix('/warehouse')->middleware('auth', 'auth.session')->name('warehouse.')->group(function () {
    // Products
    Route::prefix('/products')->controller(WarehouseProductController::class)->name('products.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesOnly(
            ['index', 'edit', 'update'],
            'id',
            'can:view-warehouse-products',
            'can:edit-warehouse-products'
        );
    });

    // Batches
    Route::prefix('/product-batches')->controller(WarehouseProductBatchController::class)->name('product-batches.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesOnly(
            ['index', 'create', 'store', 'edit', 'update'],
            'id',
            'can:view-warehouse-product-batches',
            'can:edit-warehouse-product-batches'
        );
    });
});
