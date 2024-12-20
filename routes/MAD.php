<?php

use App\Http\Controllers\ManufacturerController;
use App\Support\Generators\CRUDRouteGenerator;
use Illuminate\Support\Facades\Route;

// EPP
Route::prefix('manufacturers')->controller(ManufacturerController::class)->name('manufacturers.')->group(function () {
    CRUDRouteGenerator::defineDefaultRoutesExcept(['show'], 'id', 'can:view-MAD-EPP', 'can:edit-MAD-EPP');
});

// IVP
Route::prefix('products')->controller(ProductController::class)->name('products.')->group(function () {
    CRUDRouteGenerator::defineDefaultRoutesExcept(['show'], 'id', 'can:view-MAD-IVP', 'can:edit-MAD-IVP');
});

// VPS
Route::prefix('processes')->controller(ProcessController::class)->name('processes.')->group(function () {
    CRUDRouteGenerator::defineDefaultRoutesExcept(['show'], 'id', 'can:view-MAD-VPS', 'can:edit-MAD-VPS');
});

// KVPP
Route::prefix('product-searches')->controller(ProductSearchController::class)->name('product-searches.')->group(function () {
    CRUDRouteGenerator::defineDefaultRoutesExcept(['show'], 'id', 'can:view-MAD-KVPP', 'can:edit-MAD-KVPP');
});

// Meetings
Route::prefix('meetings')->controller(ProcessController::class)->name('meetings.')->group(function () {
    CRUDRouteGenerator::defineDefaultRoutesExcept(['show'], 'id', 'can:view-MAD-Meetings', 'can:edit-MAD-Meetings');
});
