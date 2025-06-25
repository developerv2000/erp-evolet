<?php
// Shared routes, which are used by multiple departments

use App\Http\Controllers\global\SharedController;
use Illuminate\Support\Facades\Route;

// Order products
Route::controller(SharedController::class)->name('shared.')->group(function () {
    // AJAX request on "Order" create & "OrderProduct" create/edit pages
    Route::post('/processes/get-process-with-it-similar-records-for-order', 'getProcessWithItSimilarRecordsForOrder');
});
