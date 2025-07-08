<?php

namespace App\Support\Definers\GateDefiners;

use App\Models\Permission;
use Illuminate\Support\Facades\Gate;

class SharedGatesDefiner
{
    public static function defineAll()
    {
        // PLPD and DD
        Gate::define(
            'receive-notification-when-CMD-order-is-sent-to-manufacturer',
            fn($user) =>
            $user->hasPermission(Permission::CAN_RECEIVE_NOTIFICATION_WHEN_CMD_ORDER_IS_SENT_TO_MANUFACTURER)
        );

        // PLPD and PRD
        Gate::define(
            'receive-notification-when-CMD-invoice-is-sent-for-payment',
            fn($user) =>
            $user->hasPermission(Permission::CAN_RECEIVE_NOTIFICATION_WHEN_CMD_INVOICE_IS_SENT_FOR_PAYMENT)
        );

        // PLPD and CMD
        Gate::define(
            'receive-notification-when-PRD-invoice-payment-is-completed',
            fn($user) =>
            $user->hasPermission(Permission::CAN_RECEIVE_NOTIFICATION_WHEN_PRD_INVOICE_PAYMENT_IS_COMPLETED)
        );
    }
}
