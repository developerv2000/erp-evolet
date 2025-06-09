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

        $departmentID = Department::findByName(Department::MAD_NAME)->id;

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
            Permission::CAN_VIEW_MAD_DH_NAME,

            Permission::CAN_NOT_VIEW_MAD_EPP_NAME,
            Permission::CAN_NOT_VIEW_MAD_KVPP_NAME,
            Permission::CAN_NOT_VIEW_MAD_IVP_NAME,
            Permission::CAN_NOT_VIEW_MAD_VPS_NAME,
            Permission::CAN_NOT_VIEW_MAD_MEETINGS_NAME,
            Permission::CAN_NOT_VIEW_MAD_KPI_NAME,
            Permission::CAN_NOT_VIEW_MAD_ASP_NAME,
            Permission::CAN_NOT_VIEW_MAD_USERS_NAME,
            Permission::CAN_NOT_VIEW_MAD_MISC_NAME,
            Permission::CAN_NOT_VIEW_MAD_DH_NAME,

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
            Permission::CAN_MARK_MAD_VPS_AS_READY_FOR_ORDER,
        ];

        foreach ($MADs as $mad) {
            Permission::create([
                'name' => $mad,
                'department_id' => $departmentID,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | PLPD permissions
        |--------------------------------------------------------------------------
        */

        $departmentID = Department::findByName(Department::PLPD_NAME)->id;

        $PLPDs = [
            Permission::CAN_VIEW_PLPD_READY_FOR_ORDER_PROCESSES_NAME,
            Permission::CAN_VIEW_PLPD_ORDERS_NAME,

            Permission::CAN_EDIT_PLPD_ORDERS_NAME,

            Permission::CAN_RECEIVE_NOTIFICATION_WHEN_MAD_VPS_IS_MARKED_AS_READY_FOR_ORDER,
            Permission::CAN_RECEIVE_NOTIFICATION_WHEN_CMD_ORDER_IS_SENT_FOR_CONFIRMATION,
        ];

        foreach ($PLPDs as $plpd) {
            Permission::create([
                'name' => $plpd,
                'department_id' => $departmentID,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | BDM permissions
        |--------------------------------------------------------------------------
        */

        $departmentID = Department::findByName(Department::CMD_NAME)->id;

        $CMDs = [
            Permission::CAN_VIEW_CMD_ORDERS_NAME,

            Permission::CAN_EDIT_CMD_ORDERS_NAME,

            Permission::CAN_RECEIVE_NOTIFICATION_WHEN_PLPD_ORDER_IS_SENT_TO_CMD_BDM,
            Permission::CAN_RECEIVE_NOTIFICATION_WHEN_PLPD_ORDER_IS_CONFIRMED,
        ];

        foreach ($CMDs as $cmd) {
            Permission::create([
                'name' => $cmd,
                'department_id' => $departmentID,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | DD permissions
        |--------------------------------------------------------------------------
        */

        $departmentID = Department::findByName(Department::DD_NAME)->id;

        // Global permissions
        $ddGlobals = [
            Permission::CAN_RECEIVE_NOTIFICATION_WHEN_CMD_ORDER_IS_SENT_TO_MANUFACTURER,
        ];

        foreach ($ddGlobals as $global) {
            Permission::create([
                'name' => $global,
                'global' => true,
            ]);
        }

        // Non global permissions
        $dds = [
            Permission::CAN_VIEW_DD_ORDERS_NAME,
            Permission::CAN_EDIT_DD_ORDERS_NAME,
        ];

        foreach ($dds as $dd) {
            Permission::create([
                'name' => $dd,
                'department_id' => $departmentID,
            ]);
        }
    }
}
