<?php

namespace App\Support\Definers\GateDefiners;

use App\Models\Permission;
use Illuminate\Support\Facades\Gate;

class ExportGatesDefiner
{
    public static function defineAll()
    {
        // View
        Gate::define(
            'view-export-assemblages',
            fn($user) =>
            $user->hasPermission(Permission::CAN_VIEW_EXPORT_ASSEMBLAGES_NAME)
        );

        Gate::define(
            'view-export-batches',
            fn($user) =>
            $user->hasPermission(Permission::CAN_VIEW_EXPORT_BATCHES_NAME)
        );

        Gate::define(
            'view-export-invoices',
            fn($user) =>
            $user->hasPermission(Permission::CAN_VIEW_EXPORT_INVOICES_NAME)
        );

        // Edit
        Gate::define(
            'edit-export-assemblages',
            fn($user) =>
            $user->hasPermission(Permission::CAN_EDIT_EXPORT_ASSEMBLAGES_NAME)
        );

        Gate::define(
            'edit-export-batches',
            fn($user) =>
            $user->hasPermission(Permission::CAN_EDIT_EXPORT_BATCHES_NAME)
        );

        Gate::define(
            'edit-export-invoices',
            fn($user) =>
            $user->hasPermission(Permission::CAN_EDIT_EXPORT_INVOICES_NAME)
        );
    }
}
