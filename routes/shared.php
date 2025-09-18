<?php
// Shared routes, which are used by multiple departments

use App\Http\Controllers\global\SharedController;
use Illuminate\Support\Facades\Route;

Route::controller(SharedController::class)->middleware('auth', 'auth.session')->name('shared.')->group(function () {
    // AJAX request on "Order" create & "OrderProduct" create/edit pages
    Route::post('/processes/get-process-with-it-similar-records-for-order', 'getProcessWithItSimilarRecordsForOrder');
});
