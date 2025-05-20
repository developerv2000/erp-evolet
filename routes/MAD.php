<?php

use App\Http\Controllers\MAD\MADASPController;
use App\Http\Controllers\MAD\MADDecisionHubController;
use App\Http\Controllers\MAD\MADKPIController;
use App\Http\Controllers\MAD\MADManufacturerController;
use App\Http\Controllers\MAD\MADMeetingController;
use App\Http\Controllers\MAD\MADProcessController;
use App\Http\Controllers\MAD\MADProcessStatusHistoryController;
use App\Http\Controllers\MAD\MADProductController;
use App\Http\Controllers\MAD\MADProductSearchController;
use App\Http\Controllers\MAD\MADProductSelectionController;
use App\Support\Generators\CRUDRouteGenerator;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'auth.session')->prefix('mad')->name('mad.')->group(function () {
    // EPP
    Route::prefix('/manufacturers')->controller(MADManufacturerController::class)->name('manufacturers.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesExcept(['show'], 'id', 'can:view-MAD-EPP', 'can:edit-MAD-EPP');

        Route::post('/export-as-excel', 'exportAsExcel')->name('export-as-excel')->middleware('can:export-records-as-excel');
        Route::post('/get-smart-filter-dependencies', 'getSmartFilterDependencies');  // AJAX request on smart filter
    });

    // IVP
    Route::prefix('/products')->controller(MADProductController::class)->name('products.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesExcept(['show'], 'id', 'can:view-MAD-IVP', 'can:edit-MAD-IVP');

        Route::post('/export-as-excel', 'exportAsExcel')->name('export-as-excel')->middleware('can:export-records-as-excel');
        Route::post('/get-smart-filter-dependencies', 'getSmartFilterDependencies');  // AJAX request on smart filter
        Route::post('/get-similar-records', 'getSimilarRecordsForRequest');  // AJAX request on create form for uniqness
        Route::post('/get-atx-inputs', 'getATXInputs');  // AJAX request on create/edit forms
        Route::post('/get-dynamic-rows-list-item-inputs', 'getDynamicRowsListItemInputs');  // AJAX request on create form for multiple store
    });

    // VPS
    Route::prefix('/processes')->controller(MADProcessController::class)->name('processes.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesExcept(['show'], 'id', 'can:view-MAD-VPS', 'can:edit-MAD-VPS');
        Route::post('/export-as-excel', 'exportAsExcel')->name('export-as-excel')->middleware('can:export-records-as-excel');

        // Duplication
        Route::get('/duplication/{record}', 'duplication')->name('duplication')->middleware('can:edit-MAD-VPS');
        Route::post('/duplicate', 'duplicate')->name('duplicate')->middleware('can:edit-MAD-VPS');

        // AJAX request on smart filter
        Route::post('/get-smart-filter-dependencies', 'getSmartFilterDependencies');

        // Ajax requests on checkbox toggle
        Route::post('/update-contracted-in-asp-value', 'updateContractedInAspValue')->middleware('can:control-MAD-ASP-processes');
        Route::post('/update-registered-in-asp-value', 'updateRegisteredInAspValue')->middleware('can:control-MAD-ASP-processes');

        // Ajax request for getting create/edit stage inputs
        Route::post('/get-create-form-stage-inputs', 'getCreateFormStageInputs');
        Route::post('/get-create-form-forecast-inputs', 'getCreateFormForecastInputs');
        Route::post('/get-edit-form-stage-inputs', 'getEditFormStageInputs');
        Route::post('/get-duplicate-form-stage-inputs', 'getDuplicateFormStageInputs');
    });

    Route::prefix('/processes/{process}/status-history')
        ->controller(MADProcessStatusHistoryController::class)
        ->name('processes.status-history.')
        ->middleware('can:edit-MAD-VPS-status-history')
        ->group(function () {
            CRUDRouteGenerator::defineDefaultRoutesOnly(['index', 'edit', 'update', 'destroy']);
        });

    // KVPP
    Route::prefix('/product-searches')->controller(MADProductSearchController::class)->name('product-searches.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesExcept(['show'], 'id', 'can:view-MAD-KVPP', 'can:edit-MAD-KVPP');

        Route::post('/export-as-excel', 'exportAsExcel')->name('export-as-excel')->middleware('can:export-records-as-excel');
        Route::post('/get-similar-records', 'getSimilarRecordsForRequest');  // AJAX request on create form for uniqness
    });

    // VP
    Route::prefix('/product-selection')->controller(MADProductSelectionController::class)->name('product-selection.')->group(function () {
        Route::post('/export-as-excel', 'exportAsExcel')->name('export-as-excel')->middleware('can:export-records-as-excel');
    });

    // Meetings
    Route::prefix('/meetings')->controller(MADMeetingController::class)->name('meetings.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesExcept(['show'], 'id', 'can:view-MAD-Meetings', 'can:edit-MAD-Meetings');
        Route::post('/export-as-excel', 'exportAsExcel')->name('export-as-excel')->middleware('can:export-records-as-excel');
    });

    // KPI
    Route::prefix('/kpi')->controller(MADKPIController::class)->name('kpi.')->group(function () {
        Route::get('/', 'index')->name('index')->middleware('can:view-MAD-KPI');
    });

    // ASP
    Route::prefix('/asp')->controller(MADASPController::class)->name('asp.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesExcept(['trash', 'restore'], 'year', 'can:view-MAD-ASP', 'can:edit-MAD-ASP');
        Route::post('/{record:year}/export-as-excel', 'exportAsExcel')->name('export-as-excel')->middleware('can:export-records-as-excel');

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

    // DH
    Route::prefix('/decision-hub')->controller(MADDecisionHubController::class)->name('decision-hub.')->group(function () {
        Route::get('/', 'index')->name('index')->middleware('can:view-MAD-DH');
    });
});
