<?php

use App\Models\Country;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    Country::recalculateAllUsageCounts();
})->daily();

Artisan::command('users:reset-settings', function () {
    User::resetAllSettingsToDefaultForAll();
    $this->info('All user settings have been reset!');
})->purpose("Reset all user settings");
