<?php

namespace App\Support\Definers\GateDefiners;

use App\Models\Permission;
use Illuminate\Support\Facades\Gate;

class CMDGatesDefiner
{
    public static function defineAll()
    {
        // View
        Gate::define(
            'view-CMD-orders',
            fn($user) =>
            $user->hasPermission(Permission::CAN_VIEW_CMD_ORDERS_NAME)
        );

        // Edit
        Gate::define(
            'edit-CMD-orders',
            fn($user) =>
            $user->hasPermission(Permission::CAN_EDIT_CMD_ORDERS_NAME)
        );

        // Other gates
        Gate::define(
            'receive-notification-when-PLPD-order-is-sent-to-CMD-BDM',
            fn($user) =>
            $user->hasPermission(Permission::CAN_RECEIVE_NOTIFICATION_WHEN_PLPD_ORDER_IS_SENT_TO_CMD_BDM)
        );

        Gate::define(
            'receive-notification-when-PLPD-order-is-confirmed',
            fn($user) =>
            $user->hasPermission(Permission::CAN_RECEIVE_NOTIFICATION_WHEN_PLPD_ORDER_IS_CONFIRMED)
        );
    }
}
