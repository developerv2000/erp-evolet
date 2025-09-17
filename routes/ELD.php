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

        Route::post('/start-shipment-from-manufacturer/{record}', 'startShipmentFromManufacturer')->name('start-shipment-from-manufacturer');
        Route::post('/request-delivery-to-warehouse/{record}', 'requestDeliveryToWarehouse')->name('request-delivery-to-warehouse');
        Route::post('/end-shipment-from-manufacturer/{record}', 'endShipmentFromManufacturer')->name('end-shipment-from-manufacturer'); // AJAX request
        Route::post('/mark-as-arrived-at-warehouse/{record}', 'markAsArrivedAtWarehouse')->name('mark-as-arrived-at-warehouse'); // AJAX request
    });

    // Invoices
    Route::prefix('/invoices')->controller(ELDInvoiceController::class)->name('invoices.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesOnly(
            ['index', 'create', 'store', 'edit', 'update'],
            'id',
            'can:view-ELD-invoices',
            'can:edit-ELD-invoices'
        );
    });
});
