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
            Permission::CAN_VIEW_PLPD_ORDER_PRODUCTS_NAME,
            Permission::CAN_VIEW_PLPD_INVOICES_NAME,

            Permission::CAN_EDIT_PLPD_ORDERS_NAME,
            Permission::CAN_EDIT_PLPD_ORDER_PRODUCTS_NAME,

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
            Permission::CAN_VIEW_CMD_ORDER_PRODUCTS_NAME,
            Permission::CAN_VIEW_CMD_INVOICES_NAME,

            Permission::CAN_EDIT_CMD_ORDERS_NAME,
            Permission::CAN_EDIT_CMD_ORDER_PRODUCTS_NAME,
            Permission::CAN_EDIT_CMD_INVOICES_NAME,

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

        $dds = [
            Permission::CAN_VIEW_DD_ORDER_PRODUCTS_NAME,
            Permission::CAN_EDIT_DD_ORDER_PRODUCTS_NAME,
        ];

        foreach ($dds as $dd) {
            Permission::create([
                'name' => $dd,
                'department_id' => $departmentID,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | PRD permissions
        |--------------------------------------------------------------------------
        */

        $departmentID = Department::findByName(Department::PRD_NAME)->id;

        $prds = [
            Permission::CAN_VIEW_PRD_ORDERS_NAME,
            Permission::CAN_VIEW_PRD_ORDER_PRODUCTS_NAME,
            Permission::CAN_VIEW_PRD_INVOICES_NAME,

            Permission::CAN_EDIT_PRD_INVOICES_NAME,
        ];

        foreach ($prds as $prd) {
            Permission::create([
                'name' => $prd,
                'department_id' => $departmentID,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | MSD permissions
        |--------------------------------------------------------------------------
        */

        $departmentID = Department::findByName(Department::MSD_NAME)->id;

        $msds = [
            Permission::CAN_VIEW_MSD_ORDER_PRODUCTS_NAME,
            Permission::CAN_EDIT_MSD_ORDER_PRODUCTS_NAME,
        ];

        foreach ($msds as $msd) {
            Permission::create([
                'name' => $msd,
                'department_id' => $departmentID,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | ELD permissions
        |--------------------------------------------------------------------------
        */

        $departmentID = Department::findByName(Department::ELD_NAME)->id;

        $elds = [
            Permission::CAN_VIEW_ELD_ORDER_PRODUCTS_NAME,
            Permission::CAN_VIEW_ELD_INVOICES_NAME,
            Permission::CAN_EDIT_ELD_ORDER_PRODUCTS_NAME,
            Permission::CAN_EDIT_ELD_INVOICES_NAME,
        ];

        foreach ($elds as $eld) {
            Permission::create([
                'name' => $eld,
                'department_id' => $departmentID,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Shared order-based permissions
        |--------------------------------------------------------------------------
        */

        $sharedPerms = [
            Permission::CAN_RECEIVE_NOTIFICATION_WHEN_CMD_ORDER_IS_SENT_TO_MANUFACTURER,
            Permission::CAN_RECEIVE_NOTIFICATION_WHEN_CMD_INVOICE_IS_SENT_FOR_PAYMENT,
            Permission::CAN_RECEIVE_NOTIFICATION_WHEN_PRD_INVOICE_PAYMENT_IS_COMPLETED,
        ];

        foreach ($sharedPerms as $shared) {
            Permission::create([
                'name' => $shared,
                'global' => true,
            ]);
        }
    }
}
