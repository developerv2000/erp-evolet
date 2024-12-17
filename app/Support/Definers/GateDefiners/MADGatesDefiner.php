<?php

namespace App\Support\Definers\GateDefiners;

use Illuminate\Support\Facades\Gate;

class MADGatesDefiner
{
    public static function defineAll()
    {
        // View
        Gate::define('view-MAD-EPP', function ($user) {
            return $user->hasPermission('can view MAD EPP');
        });

        Gate::define('view-MAD-KVPP', function ($user) {
            return $user->hasPermission('can view MAD KVPP');
        });

        Gate::define('view-MAD-IVP', function ($user) {
            return $user->hasPermission('can view MAD IVP');
        });

        Gate::define('view-MAD-VPS', function ($user) {
            return $user->hasPermission('can view MAD VPS');
        });

        Gate::define('view-MAD-Meetings', function ($user) {
            return $user->hasPermission('can view MAD Meetings');
        });

        Gate::define('view-MAD-KPI', function ($user) {
            return $user->hasPermission('can view MAD KPI');
        });

        Gate::define('view-MAD-ASP', function ($user) {
            return $user->hasPermission('can view MAD ASP');
        });

        Gate::define('view-MAD-Misc', function ($user) {
            return $user->hasPermission('can view MAD Misc');
        });

        Gate::define('view-MAD-Users', function ($user) {
            return $user->hasPermission('can view MAD Users');
        });

        // Edit
        Gate::define('edit-MAD-EPP', function ($user) {
            return $user->hasPermission('can edit MAD EPP');
        });

        Gate::define('edit-MAD-KVPP', function ($user) {
            return $user->hasPermission('can edit MAD KVPP');
        });

        Gate::define('edit-MAD-IVP', function ($user) {
            return $user->hasPermission('can edit MAD IVP');
        });

        Gate::define('edit-MAD-VPS', function ($user) {
            return $user->hasPermission('can edit MAD VPS');
        });

        Gate::define('edit-MAD-Meetings', function ($user) {
            return $user->hasPermission('can edit MAD Meetings');
        });

        Gate::define('edit-MAD-ASP', function ($user) {
            return $user->hasPermission('can edit MAD ASP');
        });

        Gate::define('edit-MAD-Misc', function ($user) {
            return $user->hasPermission('can edit MAD Misc');
        });

        Gate::define('edit-MAD-Users', function ($user) {
            return $user->hasPermission('can edit MAD Users');
        });

        // Other permissions
        Gate::define('view-MAD-KVPP-coincident-processes', function ($user) {
            return $user->hasPermission('can view MAD KVPP coincident processes');
        });

        Gate::define('view-MAD-extended-KPI-version', function ($user) {
            return $user->hasPermission('can view MAD extended KPI version');
        });

        Gate::define('view-MAD-KPI-of-all-analysts', function ($user) {
            return $user->hasPermission('can view MAD KPI of all analysts');
        });

        Gate::define('control-MAD-ASP-processes', function ($user) {
            return $user->hasPermission('can control ASP processes');
        });

        Gate::define('view-MAD-VPS-of-all-analysts', function ($user) {
            return $user->hasPermission('can view MAD VPS of all analysts');
        });

        Gate::define('edit-MAD-VPS-of-all-analysts', function ($user) {
            return $user->hasPermission('can edit MAD VPS of all analysts');
        });

        Gate::define('edit-MAD-VPS-status-history', function ($user) {
            return $user->hasPermission('can edit MAD VPS status history');
        });

        Gate::define('upgrade-MAD-VPS-status-after-contract-stage', function ($user) {
            return $user->hasPermission('can upgrade MAD VPS status after contract stage');
        });

        Gate::define('receive-notification-on-MAD-VPS-contract', function ($user) {
            return $user->hasPermission('can receive notification on MAD VPS contract');
        });
    }
}
