<?php

use App\Models\Country;
use App\Models\Currency;
use App\Models\Process;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    Country::recalculateAllProcessCounts();
    Currency::updateAllUSDRatios();
})->daily();

Artisan::command('users:reset-settings', function () {
    User::resetAllSettingsToDefaultForAll();
    $this->info('All user settings have been reset!');
})->purpose("Reset all user settings");

Artisan::command('vps:fix-responsibles', function () {
    Process::withTrashed()->chunk(500, function ($processes) {
        foreach ($processes as $process) {
            $oldID = DB::table('process_process_responsible_people')
                ->where('process_id', $process->id)
                ->value('responsible_person_id');

            if ($oldID != null) {
                $process->forceFill(['responsible_person_id' => $oldID]);
                $process->timestamps = false;
                $process->saveQuietly();
            }
        }
    });
})->purpose("Reset all user settings");
