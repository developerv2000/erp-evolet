<?php

namespace App\Support\Definers\GateDefiners;

use App\Models\Permission;
use Illuminate\Support\Facades\Gate;

class PLPDGatesDefiner
{
    public static function defineAll()
    {
        // View
        Gate::define(
            'view-PLPD-ready-for-order-processes',
            fn($user) =>
            $user->hasPermission(Permission::CAN_VIEW_PLPD_READY_FOR_ORDER_PROCESSES_NAME)
        );

        Gate::define(
            'view-PLPD-orders',
            fn($user) =>
            $user->hasPermission(Permission::CAN_VIEW_PLPD_ORDERS_NAME)
        );

        Gate::define(
            'view-PLPD-order-products',
            fn($user) =>
            $user->hasPermission(Permission::CAN_VIEW_PLPD_ORDER_PRODUCTS_NAME)
        );

        // Edit
        Gate::define(
            'edit-PLPD-orders',
            fn($user) =>
            $user->hasPermission(Permission::CAN_EDIT_PLPD_ORDERS_NAME)
        );

        // Edit
        Gate::define(
            'edit-PLPD-order-products',
            fn($user) =>
            $user->hasPermission(Permission::CAN_EDIT_PLPD_ORDER_PRODUCTS_NAME)
        );

        // Other gates
        Gate::define(
            'receive-notification-when-MAD-VPS-is-marked-as-ready-for-order',
            fn($user) =>
            $user->hasPermission(Permission::CAN_RECEIVE_NOTIFICATION_WHEN_MAD_VPS_IS_MARKED_AS_READY_FOR_ORDER)
        );

        Gate::define(
            'receive-notification-when-CMD-order-is-sent-for-confirmation',
            fn($user) =>
            $user->hasPermission(Permission::CAN_RECEIVE_NOTIFICATION_WHEN_CMD_ORDER_IS_SENT_FOR_CONFIRMATION)
        );
    }
}
