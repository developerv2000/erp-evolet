<?php

use App\Http\Controllers\MSD\MSDSerializedByManufacturerController;
use App\Http\Controllers\MSD\MSDSerializedByUsController;
use App\Support\Generators\CRUDRouteGenerator;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'auth.session')->prefix('msd')->name('msd.')->group(function () {
    // Order products
    Route::prefix('/orders/products/serialized-by-manufacturer')
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

    Route::prefix('/orders/products/serialized-by-us')
        ->controller(MSDSerializedByUsController::class)
        ->name('order-products.serialized-by-us.')
        ->group(function () {
            CRUDRouteGenerator::defineDefaultRoutesOnly(
                ['index', 'edit', 'update'],
                'id',
                'can:view-MSD-order-products',
                'can:edit-MSD-order-products'
            );
        });
});
