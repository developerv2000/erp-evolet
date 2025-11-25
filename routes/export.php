<?php

use App\Http\Controllers\export\ExportAssemblageController;
use App\Http\Controllers\export\ExportInvoiceController;
use App\Http\Controllers\export\ExportBatchController;
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

        Route::post('/request-assembly/{record}', 'requestAssembly')->name('request-assembly');
        Route::post('/accept-assembly-request/{record}', 'acceptAssemblyRequest')->name('accept-assembly-request');
        
        Route::post('/get-dynamic-rows-list-item-inputs', 'getDynamicRowsListItemInputs');  // AJAX request on create
        Route::post('/get-matched-batches-on-create', 'getMatchedBatchesOnCreate');  // AJAX request on create
    });

    // Batches
    Route::prefix('/batches')->controller(ExportBatchController::class)->name('batches.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesOnly(
            ['index', 'create', 'store', 'edit', 'update', 'destroy'],
            'id',
            'can:view-export-batches',
            'can:edit-export-batches'
        );
    });

    // Invoices
    Route::prefix('/invoices')->controller(ExportInvoiceController::class)->name('invoices.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesOnly(
            ['index', 'create', 'store', 'edit', 'update', 'destroy'],
            'id',
            'can:view-export-invoices',
            'can:edit-export-invoices'
        );
    });
});
