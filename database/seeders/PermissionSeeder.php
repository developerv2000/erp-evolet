<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Global permissions
        |--------------------------------------------------------------------------
        */

        $globals = [
            Permission::CAN_DELETE_FROM_TRASH_NAME,
            Permission::CAN_EDIT_COMMENTS_NAME,
            Permission::CAN_EXPORT_RECORDS_AS_EXCEL_NAME,
            Permission::CAN_NOT_EXPORT_RECORDS_AS_EXCEL_NAME,
            Permission::CAN_EXPORT_UNLIMITED_RECORDS_AS_EXCEL_NAME,
        ];

        foreach ($globals as $global) {
            Permission::create([
                'name' => $global,
                'global' => true,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | MAD permissions
        |--------------------------------------------------------------------------
        */

        $MADs = [
            Permission::CAN_VIEW_MAD_EPP_NAME,
            Permission::CAN_VIEW_MAD_KVPP_NAME,
            Permission::CAN_VIEW_MAD_IVP_NAME,
            Permission::CAN_VIEW_MAD_VPS_NAME,
            Permission::CAN_VIEW_MAD_MEETINGS_NAME,
            Permission::CAN_VIEW_MAD_KPI_NAME,
            Permission::CAN_VIEW_MAD_ASP_NAME,
            Permission::CAN_VIEW_MAD_USERS_NAME,
            Permission::CAN_VIEW_MAD_MISC_NAME,

            Permission::CAN_NOT_VIEW_MAD_EPP_NAME,
            Permission::CAN_NOT_VIEW_MAD_KVPP_NAME,
            Permission::CAN_NOT_VIEW_MAD_IVP_NAME,
            Permission::CAN_NOT_VIEW_MAD_VPS_NAME,
            Permission::CAN_NOT_VIEW_MAD_MEETINGS_NAME,
            Permission::CAN_NOT_VIEW_MAD_KPI_NAME,
            Permission::CAN_NOT_VIEW_MAD_ASP_NAME,
            Permission::CAN_NOT_VIEW_MAD_USERS_NAME,
            Permission::CAN_NOT_VIEW_MAD_MISC_NAME,

            Permission::CAN_EDIT_MAD_EPP_NAME,
            Permission::CAN_EDIT_MAD_KVPP_NAME,
            Permission::CAN_EDIT_MAD_IVP_NAME,
            Permission::CAN_EDIT_MAD_VPS_NAME,
            Permission::CAN_EDIT_MAD_MEETINGS_NAME,
            Permission::CAN_EDIT_MAD_ASP_NAME,
            Permission::CAN_EDIT_MAD_USERS_NAME,
            Permission::CAN_EDIT_MAD_MISC_NAME,

            Permission::CAN_NOT_EDIT_MAD_EPP_NAME,
            Permission::CAN_NOT_EDIT_MAD_KVPP_NAME,
            Permission::CAN_NOT_EDIT_MAD_IVP_NAME,
            Permission::CAN_NOT_EDIT_MAD_VPS_NAME,
            Permission::CAN_NOT_EDIT_MAD_MEETINGS_NAME,
            Permission::CAN_NOT_EDIT_MAD_ASP_NAME,
            Permission::CAN_NOT_EDIT_MAD_USERS_NAME,
            Permission::CAN_NOT_EDIT_MAD_MISC_NAME,

            Permission::CAN_VIEW_MAD_KVPP_MATCHING_PROCESSES_NAME,

            Permission::CAN_VIEW_KPI_EXTENDED_VERSION_NAME,
            Permission::CAN_VIEW_KPI_OF_ALL_ANALYSTS,

            Permission::CAN_CONTROL_MAD_ASP_PROCESSES,

            Permission::CAN_VIEW_MAD_VPS_OF_ALL_ANALYSTS_NAME,
            Permission::CAN_EDIT_MAD_VPS_OF_ALL_ANALYSTS_NAME,
            Permission::CAN_EDIT_MAD_VPS_STATUS_HISTORY_NAME,
            Permission::CAN_UPGRADE_MAD_VPS_STATUS_AFTER_CONTRACT_STAGE_NAME,
            Permission::CAN_RECEIVE_NOTIFICATION_ON_MAD_VPS_CONTRACT,
        ];

        foreach ($MADs as $mad) {
            Permission::create([
                'name' => $mad,
                'department_id' => Department::findByName(Department::MAD_NAME)->id,
            ]);
        }
    }
}
