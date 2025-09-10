<?php

use App\Models\Country;
use App\Models\Currency;
use App\Models\Process;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    Country::recalculateAllProcessCounts();
    Currency::updateAllUSDRatios();
    Process::validateAllOrderPriorityAttributes();
})->daily();

Artisan::command('users:reset-settings', function () {
    User::resetAllSettingsToDefaultForAll();
    $this->info('All user settings have been reset!');
})->purpose("Reset all user settings");

Artisan::command('users:reset-specific-table-settings-for-all {key}', function (string $key) {
    try {
        User::all()->each(function (User $user) use ($key) {
            $user->resetSpecificTableColumnSettings($key);
        });

        $this->info("All users '{$key}' settings were reset successfully.");
    } catch (Throwable $e) {
        $this->error("Error: " . $e->getMessage());
    }
})->describe('Reset a specific table column setting for all users');

Artisan::command('users:delete-unnecessary-images', function () {
    $deleted = User::deleteUnnecessaryImages();
    $this->info("Removed {$deleted} unnecessary images!");
})->purpose("Delete unnecessary user images");
