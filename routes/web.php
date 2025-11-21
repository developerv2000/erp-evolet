<?php

use App\Http\Controllers\global\AttachmentController;
use App\Http\Controllers\global\AuthenticatedSessionController;
use App\Http\Controllers\global\CommentController;
use App\Http\Controllers\global\MainController;
use App\Http\Controllers\global\MiscModelController;
use App\Http\Controllers\global\NotificationController;
use App\Http\Controllers\global\ProfileController;
use App\Http\Controllers\global\SettingController;
use App\Support\Generators\CRUDRouteGenerator;
use Illuminate\Support\Facades\Route;

Route::controller(AuthenticatedSessionController::class)->group(function () {
    Route::get('login', 'create')->middleware('guest')->name('login');
    Route::post('login', 'store')->middleware('guest');
    Route::post('logout', 'destroy')->middleware('auth')->name('logout');
});

Route::middleware('auth', 'auth.session')->group(function () {
    Route::controller(MainController::class)->group(function () {
        Route::get('/', 'redirectToHomePage')->name('home');
        Route::post('/upload-simditor-image', 'uploadSimditorImage');
        Route::post('/navigate-to-page-number', 'navigateToPageNumber')->name('navigate-to-page-number');
    });

    Route::controller(SettingController::class)->prefix('/settings')->name('settings.')->group(function () {
        Route::patch('locale', 'updateLocale')->name('update-locale');
        Route::patch('preferred-theme', 'toggleTheme')->name('toggle-theme');
        Route::patch('collapsed-leftbar', 'toggleLeftbar')->name('toggle-leftbar'); // ajax request
        Route::patch('table-columns/{key}', 'updateTableColumns')->name('update-table-columns');
    });

    Route::prefix('notifications')->controller(NotificationController::class)->name('notifications.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::patch('/mark-as-read', 'markAsRead')->name('mark-as-read');
        Route::delete('/destroy', 'destroy')->name('destroy');
    });

    Route::controller(ProfileController::class)->name('profile.')->group(function () {
        Route::get('profile', 'edit')->name('edit');
        Route::patch('profile', 'update')->name('update');
        Route::patch('password', 'updatePassword')->name('update-password');
    });

    Route::prefix('comments')->controller(CommentController::class)->name('comments.')->group(function () {
        Route::get('/view/{commentable_type}/{commentable_id}', 'index')->name('index');

        CRUDRouteGenerator::defineDefaultRoutesOnly(['edit', 'store', 'update', 'destroy'], 'id', null, 'can:edit-comments');
    });

    Route::prefix('model-attachments')->controller(AttachmentController::class)->name('attachments.')->group(function () {
        Route::get('/view/{attachable_type}/{attachable_id}', 'index')->name('index');
        Route::delete('/destroy', 'destroy')->name('destroy');
    });

    Route::prefix('misc-models')->controller(MiscModelController::class)->name('misc-models.')->group(function () {
        Route::get('/department/{department}/models', 'departmentModels')->name('department-models');
        Route::get('/model/{model}', 'index')->name('index');
        Route::get('/model/{model}/create', 'create')->name('create');
        Route::get('/model/{model}/edit/{id}', 'edit')->name('edit');

        Route::post('/model/{model}/store', 'store')->name('store');
        Route::patch('/model/{model}/update/{id}', 'update')->name('update');
        Route::delete('/model/{model}/destroy', 'destroy')->name('destroy');
    });
});

require __DIR__ . '/shared.php';
require __DIR__ . '/MGMT.php';
require __DIR__ . '/MAD.php';
require __DIR__ . '/PLPD.php';
require __DIR__ . '/CMD.php';
require __DIR__ . '/DD.php';
require __DIR__ . '/PRD.php';
require __DIR__ . '/MSD.php';
require __DIR__ . '/ELD.php';
require __DIR__ . '/warehouse.php';
require __DIR__ . '/export.php';
