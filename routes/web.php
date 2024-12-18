<?php

use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthenticatedSessionController::class)->group(function () {
    Route::get('login', 'create')->middleware('guest')->name('login');
    Route::post('login', 'store')->middleware('guest');
    Route::post('logout', 'destroy')->middleware('auth')->name('logout');
});

Route::middleware('auth', 'auth.session')->group(function () {
    Route::controller(SettingController::class)->prefix('/settings')->name('settings.')->group(function () {
        Route::patch('preferred-theme', 'toggleTheme')->name('toggle-theme');
        Route::patch('collapsed-leftbar', 'toggleDashboardLeftbar')->name('toggle-leftbar'); // ajax request
    });

    Route::controller(ProfileController::class)->name('profile.')->group(function () {
        Route::get('profile', 'edit')->name('edit');
        Route::patch('profile', 'update')->name('update');
        Route::patch('password', 'updatePassword')->name('update-password');
    });

    Route::get('/', function () {
        return view('home');
    });
});
