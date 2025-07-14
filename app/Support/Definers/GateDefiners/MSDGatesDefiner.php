<?php

namespace App\Support\Definers\GateDefiners;

use App\Models\Permission;
use Illuminate\Support\Facades\Gate;

class MSDGatesDefiner
{
    public static function defineAll()
    {
        // View
        Gate::define(
            'view-MSD-order-products',
            fn($user) =>
            $user->hasPermission(Permission::CAN_VIEW_MSD_ORDER_PRODUCTS_NAME)
        );

        // Edit
        Gate::define(
            'edit-MSD-order-products',
            fn($user) =>
            $user->hasPermission(Permission::CAN_EDIT_MSD_ORDER_PRODUCTS_NAME)
        );
    }
}
