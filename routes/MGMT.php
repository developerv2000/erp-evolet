<?php

use App\Http\Controllers\MGMT\DepartmentController;
use App\Http\Controllers\MGMT\PermissionController;
use App\Http\Controllers\MGMT\RoleController;
use App\Http\Controllers\MGMT\UserController;
use App\Support\Generators\CRUDRouteGenerator;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'auth.session', 'can:administrate')->group(function () {
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');

    // Users
    Route::prefix('users')->controller(UserController::class)->name('users.')->group(function () {
        CRUDRouteGenerator::defineDefaultRoutesExcept(['show', 'trash', 'restore'], 'id');
        Route::patch('/password/{record}', 'updatePassword')->name('update-password');
    });
});
