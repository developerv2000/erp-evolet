<?php

namespace App\Support\Definers\GateDefiners;

use App\Models\Permission;
use Illuminate\Support\Facades\Gate;

class ELDGatesDefiner
{
    public static function defineAll()
    {
        // View
        Gate::define(
            'view-ELD-order-products',
            fn($user) =>
            $user->hasPermission(Permission::CAN_VIEW_ELD_ORDER_PRODUCTS_NAME)
        );

        Gate::define(
            'view-ELD-invoices',
            fn($user) =>
            $user->hasPermission(Permission::CAN_VIEW_ELD_INVOICES_NAME)
        );

        // Edit
        Gate::define(
            'edit-ELD-order-products',
            fn($user) =>
            $user->hasPermission(Permission::CAN_EDIT_ELD_ORDER_PRODUCTS_NAME)
        );

        Gate::define(
            'edit-ELD-invoices',
            fn($user) =>
            $user->hasPermission(Permission::CAN_EDIT_ELD_INVOICES_NAME)
        );
    }
}
