<?php

namespace App\Support\Definers\GateDefiners;

use App\Models\User;
use Illuminate\Support\Facades\Gate;

class GlobalGatesDefiner
{
    public static function defineAll()
    {
        // Full access for admins
        Gate::before(function (User $user, string $ability) {
            if ($user->isGlobalAdministrator()) {
                return true;
            }
        });

        /*
        |--------------------------------------------------------------------------
        | Gates
        |--------------------------------------------------------------------------
        */

        // View roles
        Gate::define('view-Roles-table', function ($user) {
            return $user->hasPermission('can view Roles table');
        });

        // Delete from trash
        Gate::define('delete-from-trash', function ($user) {
            return $user->hasPermission('can delete from trash');
        });

        // Edit comments
        Gate::define('edit-comments', function ($user) {
            return $user->hasPermission('can edit comments');
        });

        // Export
        Gate::define('export-records-as-excel', function ($user) {
            return $user->hasPermission('can export records as excel');
        });

        Gate::define('export-unlimited-records-as-excel', function ($user) {
            return $user->hasPermission('can export unlimited records as excel');
        });
    }
}
