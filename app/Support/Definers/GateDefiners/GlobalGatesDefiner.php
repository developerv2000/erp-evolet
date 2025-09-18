<?php

namespace App\Support\Definers\GateDefiners;

use App\Models\Permission;
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

        // Administrate
        Gate::define('administrate', fn($user) => $user->isAnyAdministrator());

        // Delete from trash
        Gate::define('delete-from-trash', fn($user) => $user->hasPermission(Permission::CAN_DELETE_FROM_TRASH_NAME));

        // Edit comments
        Gate::define('edit-comments', fn($user) => $user->hasPermission(Permission::CAN_EDIT_COMMENTS_NAME));

        // Export
        Gate::define('export-records-as-excel', fn($user) => $user->hasPermission(Permission::CAN_EXPORT_RECORDS_AS_EXCEL_NAME));
        Gate::define('export-unlimited-records-as-excel', fn($user) => $user->hasPermission(Permission::CAN_EXPORT_UNLIMITED_RECORDS_AS_EXCEL_NAME));

        // Warehouse
        Gate::define('view-warehouse-products', fn($user) => $user->hasPermission(Permission::CAN_VIEW_WAREHOUSE_PRODUCTS_NAME));
        Gate::define('edit-warehouse-products', fn($user) => $user->hasPermission(Permission::CAN_EDIT_WAREHOUSE_PRODUCTS_NAME));
        Gate::define('view-warehouse-product-batches', fn($user) => $user->hasPermission(Permission::CAN_VIEW_WAREHOUSE_PRODUCT_BATCHES_NAME));
        Gate::define('edit-warehouse-product-batches', fn($user) => $user->hasPermission(Permission::CAN_EDIT_WAREHOUSE_PRODUCT_BATCHES_NAME));
    }
}
