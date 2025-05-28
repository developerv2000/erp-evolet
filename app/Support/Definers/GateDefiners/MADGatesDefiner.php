<?php

namespace App\Support\Definers\GateDefiners;

use App\Models\Permission;
use Illuminate\Support\Facades\Gate;

class MADGatesDefiner
{
    public static function defineAll()
    {
        // View
        Gate::define('view-MAD-EPP', fn($user) => $user->hasPermission(Permission::CAN_VIEW_MAD_EPP_NAME));
        Gate::define('view-MAD-KVPP', fn($user) => $user->hasPermission(Permission::CAN_VIEW_MAD_KVPP_NAME));
        Gate::define('view-MAD-IVP', fn($user) => $user->hasPermission(Permission::CAN_VIEW_MAD_IVP_NAME));
        Gate::define('view-MAD-VPS', fn($user) => $user->hasPermission(Permission::CAN_VIEW_MAD_VPS_NAME));
        Gate::define('view-MAD-Meetings', fn($user) => $user->hasPermission(Permission::CAN_VIEW_MAD_MEETINGS_NAME));
        Gate::define('view-MAD-KPI', fn($user) => $user->hasPermission(Permission::CAN_VIEW_MAD_KPI_NAME));
        Gate::define('view-MAD-ASP', fn($user) => $user->hasPermission(Permission::CAN_VIEW_MAD_ASP_NAME));
        Gate::define('view-MAD-Misc', fn($user) => $user->hasPermission(Permission::CAN_VIEW_MAD_MISC_NAME));
        Gate::define('view-MAD-Users', fn($user) => $user->hasPermission(Permission::CAN_VIEW_MAD_USERS_NAME));
        Gate::define('view-MAD-Decision-hub', fn($user) => $user->hasPermission(Permission::CAN_VIEW_MAD_DH_NAME));

        // Edit
        Gate::define('edit-MAD-EPP', fn($user) => $user->hasPermission(Permission::CAN_EDIT_MAD_EPP_NAME));
        Gate::define('edit-MAD-KVPP', fn($user) => $user->hasPermission(Permission::CAN_EDIT_MAD_KVPP_NAME));
        Gate::define('edit-MAD-IVP', fn($user) => $user->hasPermission(Permission::CAN_EDIT_MAD_IVP_NAME));
        Gate::define('edit-MAD-VPS', fn($user) => $user->hasPermission(Permission::CAN_EDIT_MAD_VPS_NAME));
        Gate::define('edit-MAD-Meetings', fn($user) => $user->hasPermission(Permission::CAN_EDIT_MAD_MEETINGS_NAME));
        Gate::define('edit-MAD-ASP', fn($user) => $user->hasPermission(Permission::CAN_EDIT_MAD_ASP_NAME));
        Gate::define('edit-MAD-Misc', fn($user) => $user->hasPermission(Permission::CAN_EDIT_MAD_MISC_NAME));
        Gate::define('edit-MAD-Users', fn($user) => $user->hasPermission(Permission::CAN_EDIT_MAD_USERS_NAME));

        // Other permissions
        Gate::define('view-MAD-KVPP-matching-processes', fn($user) => $user->hasPermission(Permission::CAN_VIEW_MAD_KVPP_MATCHING_PROCESSES_NAME));
        Gate::define('view-MAD-extended-KPI-version', fn($user) => $user->hasPermission(Permission::CAN_VIEW_KPI_EXTENDED_VERSION_NAME));
        Gate::define('view-MAD-KPI-of-all-analysts', fn($user) => $user->hasPermission(Permission::CAN_VIEW_KPI_OF_ALL_ANALYSTS));
        Gate::define('control-MAD-ASP-processes', fn($user) => $user->hasPermission(Permission::CAN_CONTROL_MAD_ASP_PROCESSES));
        Gate::define('view-MAD-VPS-of-all-analysts', fn($user) => $user->hasPermission(Permission::CAN_VIEW_MAD_VPS_OF_ALL_ANALYSTS_NAME));
        Gate::define('edit-MAD-VPS-of-all-analysts', fn($user) => $user->hasPermission(Permission::CAN_EDIT_MAD_VPS_OF_ALL_ANALYSTS_NAME));
        Gate::define('edit-MAD-VPS-status-history', fn($user) => $user->hasPermission(Permission::CAN_EDIT_MAD_VPS_STATUS_HISTORY_NAME));
        Gate::define('upgrade-MAD-VPS-status-after-contract-stage', fn($user) => $user->hasPermission(Permission::CAN_UPGRADE_MAD_VPS_STATUS_AFTER_CONTRACT_STAGE_NAME));
        Gate::define('receive-notification-on-MAD-VPS-contract', fn($user) => $user->hasPermission(Permission::CAN_RECEIVE_NOTIFICATION_ON_MAD_VPS_CONTRACT));
        Gate::define('mark-MAD-VPS-as-ready-for-order', fn($user) => $user->hasPermission(Permission::CAN_MARK_MAD_VPS_AS_READY_FOR_ORDER));
    }
}
