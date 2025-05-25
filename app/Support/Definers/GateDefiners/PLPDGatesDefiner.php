<?php

namespace App\Support\Definers\GateDefiners;

use Illuminate\Support\Facades\Gate;

class PLPDGatesDefiner
{
    public static function defineAll()
    {
        // View
        Gate::define('view-PLPD-ready-for-order-processes', function ($user) {
            return $user->hasPermission('can view PLPD ready for order processes');
        });

        Gate::define('view-PLPD-orders', function ($user) {
            return $user->hasPermission('can view PLPD orders');
        });

        Gate::define('view-PLPD-order-products', function ($user) {
            return $user->hasPermission('can view PLPD order products');
        });

        // Edit
        Gate::define('edit-PLPD-orders', function ($user) {
            return $user->hasPermission('can edit PLPD orders');
        });

        Gate::define('edit-PLPD-order-products', function ($user) {
            return $user->hasPermission('can edit PLPD order products');
        });
    }
}
