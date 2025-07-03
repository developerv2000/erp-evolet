<?php

namespace App\Support\Definers\GateDefiners;

use App\Models\Permission;
use Illuminate\Support\Facades\Gate;

class PRDGatesDefiner
{
    public static function defineAll()
    {
        // View
        Gate::define(
            'view-PRD-orders',
            fn($user) =>
            $user->hasPermission(Permission::CAN_VIEW_PRD_ORDERS_NAME)
        );

        Gate::define(
            'view-PRD-order-products',
            fn($user) =>
            $user->hasPermission(Permission::CAN_VIEW_PRD_ORDER_PRODUCTS_NAME)
        );

        Gate::define(
            'view-PRD-invoices',
            fn($user) =>
            $user->hasPermission(Permission::CAN_VIEW_PRD_INVOICES_NAME)
        );

        // Edit
        Gate::define(
            'edit-PRD-orders',
            fn($user) =>
            $user->hasPermission(Permission::CAN_EDIT_PRD_ORDERS_NAME)
        );

        Gate::define(
            'edit-PRD-order-products',
            fn($user) =>
            $user->hasPermission(Permission::CAN_EDIT_PRD_ORDER_PRODUCTS_NAME)
        );

        Gate::define(
            'edit-PRD-invoices',
            fn($user) =>
            $user->hasPermission(Permission::CAN_EDIT_PRD_INVOICES_NAME)
        );

        // Other gates
        Gate::define(
            'receive-notification-when-CMD-invoice-is-sent-for-payment',
            fn($user) =>
            $user->hasPermission(Permission::CAN_RECEIVE_NOTIFICATION_WHEN_CMD_INVOICE_IS_SENT_FOR_PAYMENT)
        );
    }
}
