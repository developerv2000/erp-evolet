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
})->daily();

Artisan::command('users:reset-settings', function () {
    User::resetAllSettingsToDefaultForAll();
    $this->info('All user settings have been reset!');
})->purpose("Reset all user settings");


Artisan::command('users:reset-settings', function () {
    $kpg = [18, 23, 16, 15, 19, 17, 20];
    $kpgID = 38;
    $bdmID = 39;

    // Validate kpg
    $processes = Process::withTrashed()
        ->whereHas('responsiblePeople', function ($q) use ($kpg) {
            $q->whereIn('id', $kpg);
        })->get();

    foreach ($processes as $processes) {
        $processes->responsiblePeople()->detach();
        $processes->responsiblePeople()->attach($kpgID);
    }

    // Validate bdm
    $processes = Process::withTrashed()
        ->whereDoesntHave('responsiblePeople', function ($q) use ($kpgID) {
            $q->where('id', $kpgID);
        })->get();

    foreach ($processes as $processes) {
        $processes->responsiblePeople()->detach();
        $processes->responsiblePeople()->attach($bdmID);
    }

    $this->info('All responsible people fixed!');
})->purpose("Fix responsible people");
