<?php

use App\Http\Controllers\MSD\MSDProductBatchController;
use App\Http\Controllers\MSD\MSDSerializedByManufacturerController;
use App\Support\Generators\CRUDRouteGenerator;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'auth.session')->prefix('msd')->name('msd.')->group(function () {
    // Order products
    Route::prefix('/orders/products/serialized-by-manufacturer') // // Serialized by manufacturer
        ->controller(MSDSerializedByManufacturerController::class)
        ->name('order-products.serialized-by-manufacturer.')
        ->group(function () {
            CRUDRouteGenerator::defineDefaultRoutesOnly(
                ['index', 'edit', 'update'],
                'id',
                'can:view-MSD-order-products',
                'can:edit-MSD-order-products'
            );
        });

    // Product batches
    Route::prefix('/product-batches') // Serialized by us
        ->controller(MSDProductBatchController::class)
        ->name('product-batches.')
        ->group(function () {
            CRUDRouteGenerator::defineDefaultRoutesOnly(
                ['index', 'edit', 'update'],
                'id',
                'can:view-MSD-product-batches',
                'can:edit-MSD-product-batches'
            );
        });
});
