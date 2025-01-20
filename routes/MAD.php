<?php

use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\ProcessController;
use App\Http\Controllers\ProcessStatusHistoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductSearchController;
use App\Support\Generators\CRUDRouteGenerator;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'auth.session')->group(function () {
    // EPP
    Route::prefix('manufacturers')->controller(ManufacturerController::class)->name('manufacturers.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesExcept(['show'], 'id', 'can:view-MAD-EPP', 'can:edit-MAD-EPP');
        Route::post('/export-as-excel', 'exportAsExcel')->name('export-as-excel')->middleware('can:export-records-as-excel');
    });

    // IVP
    Route::prefix('products')->controller(ProductController::class)->name('products.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesExcept(['show'], 'id', 'can:view-MAD-IVP', 'can:edit-MAD-IVP');

        Route::post('/export-as-excel', 'exportAsExcel')->name('export-as-excel')->middleware('can:export-records-as-excel');
        Route::post('/get-similar-records', 'getSimilarRecordsForRequest');  // AJAX request on create form for uniqness
    });

    // VPS
    Route::prefix('processes')->controller(ProcessController::class)->name('processes.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesExcept(['show'], 'id', 'can:view-MAD-VPS', 'can:edit-MAD-VPS');
        Route::post('/export-as-excel', 'exportAsExcel')->name('export-as-excel')->middleware('can:export-records-as-excel');

        // Duplication
        Route::get('/duplication/{record}', 'duplication')->name('duplication')->middleware('can:edit-MAD-VPS');
        Route::post('/duplicate', 'duplicate')->name('duplicate')->middleware('can:edit-MAD-VPS');

        // Ajax requests on checkbox toggle
        Route::post('/update-contracted-value', 'updateContractedValue')->middleware('can:control-MAD-ASP-processes');
        Route::post('/update-registered-value', 'updateRegisteredValue')->middleware('can:control-MAD-ASP-processes');

        // Ajax request for getting create/edit stage inputs
        Route::post('/get-create-form-stage-inputs', 'getCreateFormStageInputs');
        Route::post('/get-create-form-forecast-inputs', 'getCreateFormForecastInputs');
        Route::post('/get-edit-form-stage-inputs', 'getEditFormStageInputs');
        Route::post('/get-duplicate-form-stage-inputs', 'getDuplicateFormStageInputs');
    });

    Route::prefix('processes/{process}/status-history')
        ->controller(ProcessStatusHistoryController::class)
        ->name('processes.status-history.')
        ->middleware('can:edit-MAD-VPS-status-history')
        ->group(function () {
            CRUDRouteGenerator::defineDefaultRoutesOnly(['index', 'edit', 'update', 'destroy']);
        });

    // KVPP
    Route::prefix('product-searches')->controller(ProductSearchController::class)->name('product-searches.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesExcept(['show'], 'id', 'can:view-MAD-KVPP', 'can:edit-MAD-KVPP');

        Route::post('/export-as-excel', 'exportAsExcel')->name('export-as-excel')->middleware('can:export-records-as-excel');
        Route::post('/get-similar-records', 'getSimilarRecordsForRequest');  // AJAX request on create form for uniqness
    });

    // Meetings
    Route::prefix('meetings')->controller(MeetingController::class)->name('meetings.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesExcept(['show'], 'id', 'can:view-MAD-Meetings', 'can:edit-MAD-Meetings');
        Route::post('/export-as-excel', 'exportAsExcel')->name('export-as-excel')->middleware('can:export-records-as-excel');
    });
});
