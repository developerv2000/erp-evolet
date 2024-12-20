<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('users:reset-settings', function () {
    User::resetAllSettingsToDefaultForAll();
    $this->info('All user settings have been reset!');
})->purpose("Reset all user settings");
