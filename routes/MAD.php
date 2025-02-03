<?php

use App\Http\Controllers\MadAspController;
use App\Http\Controllers\MADKPIController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\ProcessController;
use App\Http\Controllers\ProcessStatusHistoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductSearchController;
use App\Http\Controllers\ProductSelectionController;
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
        Route::post('/update-contracted-in-asp-value', 'updateContractedInAspValue')->middleware('can:control-MAD-ASP-processes');
        Route::post('/update-registered-in-asp-value', 'updateRegisteredInAspValue')->middleware('can:control-MAD-ASP-processes');

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

    // VP
    Route::prefix('product-selection')->controller(ProductSelectionController::class)->name('product-selection.')->group(function () {
        Route::post('/export-as-excel', 'exportAsExcel')->name('export-as-excel')->middleware('can:export-records-as-excel');
    });

    // Meetings
    Route::prefix('meetings')->controller(ManufacturerController::class)->name('meetings.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesExcept(['show'], 'id', 'can:view-MAD-Meetings', 'can:edit-MAD-Meetings');
        Route::post('/export-as-excel', 'exportAsExcel')->name('export-as-excel')->middleware('can:export-records-as-excel');
    });

    // KPI
    Route::prefix('kpi')->controller(MADKPIController::class)->name('mad-kpi.')->group(function () {
        Route::get('/', 'index')->name('index')->middleware('can:view-MAD-KPI');
    });

    // ASP
    Route::prefix('mad-asp')->controller(MadAspController::class)->name('mad-asp.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesExcept(['trash', 'restore'], 'year', 'can:view-MAD-ASP', 'can:edit-MAD-ASP');
        Route::post('/export-as-excel', 'exportAsExcel')->name('export-as-excel')->middleware('can:export-records-as-excel');

        // Countries
        Route::prefix('/{record:year}/countries')->name('countries.')->middleware('can:edit-MAD-ASP')->group(function () {
            Route::get('/', 'countriesIndex')->name('index');
            Route::get('/create', 'countriesCreate')->name('create');

            Route::post('/store', 'countriesStore')->name('store');
            Route::delete('/destroy', 'countriesDestroy')->name('destroy');
        });

        // MAHs
        Route::prefix('/{record:year}/countries/{country}/mahs')->name('mahs.')->middleware('can:edit-MAD-ASP')->group(function () {
            Route::get('/', 'MAHsIndex')->name('index');
            Route::get('/create', 'MAHsCreate')->name('create');
            Route::get('/edit/{mah}', 'MAHsEdit')->name('edit');

            Route::post('/store', 'MAHsStore')->name('store');
            Route::patch('/update/{mah}', 'MAHsUpdate')->name('update');
            Route::delete('/destroy', 'MAHsDestroy')->name('destroy');
        });
    });
});
