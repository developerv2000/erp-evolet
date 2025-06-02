<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Global roles
        |--------------------------------------------------------------------------
        */

        // Global administrator
        $role = new Role();
        $role->name = Role::GLOBAL_ADMINISTRATOR_NAME;
        $role->description = "Full access. Doesn`t attach any role related permissions.";
        $role->department_id = Department::findByName(Department::MGMT_NAME)->id;
        $role->save();

        // Inactive
        $role = new Role();
        $role->name = Role::INACTIVE_NAME;
        $role->description = "No access, can`t login. Doesn`t attach any role related permissions.";
        $role->global = true;
        $role->save();

        /*
        |--------------------------------------------------------------------------
        | MAD roles
        |--------------------------------------------------------------------------
        */

        // MAD Administrator
        $role = new Role();
        $role->name = Role::MAD_ADMINISTRATOR_NAME;
        $role->description = "Full access to 'MAD part'. Attaches role related permissions.";
        $role->department_id = Department::findByName(Department::MAD_NAME)->id;
        $role->save();

        $permissionNames = Permission::getMADAdministratorPermissionNames();

        foreach ($permissionNames as $permissionName) {
            $role->permissions()->attach(Permission::findByName($permissionName)->id);
        }

        // MAD Moderator
        $role = new Role();
        $role->name = Role::MAD_MODERATOR_NAME;
        $role->description = "Can view/create/edit/update/delete/export all 'MAD part' and comments. Attaches role related permissions.";
        $role->department_id = Department::findByName(Department::MAD_NAME)->id;
        $role->save();

        $permissionNames = Permission::getMADModeratorPermissionNames();

        foreach ($permissionNames as $permissionName) {
            $role->permissions()->attach(Permission::findByName($permissionName)->id);
        }

        // MAD Guest
        $role = new Role();
        $role->name = Role::MAD_GUEST_NAME;
        $role->description = "Can only view 'MAD EPP/IVP/KVPP'. Can`t create/edit/update/delete/export. Attaches role related permissions.";
        $role->department_id = Department::findByName(Department::MAD_NAME)->id;
        $role->save();

        $permissionNames = Permission::getMADGuestPermissionNames();

        foreach ($permissionNames as $permissionName) {
            $role->permissions()->attach(Permission::findByName($permissionName)->id);
        }

        // MAD Intern
        $role = new Role();
        $role->name = Role::MAD_INTERN_NAME;
        $role->description = "Can view/edit only 'EPP' and 'IVP' of 'MAD part'. Attaches role related permissions.";
        $role->department_id = Department::findByName(Department::MAD_NAME)->id;
        $role->save();

        $permissionNames = Permission::getMADInternPermissionNames();

        foreach ($permissionNames as $permissionName) {
            $role->permissions()->attach(Permission::findByName($permissionName)->id);
        }

        // MAD Analyst
        $role = new Role();
        $role->name = Role::MAD_ANALYST_NAME;
        $role->description = "User is assosiated as 'Analyst'. Doesn`t attach any role related permissions.";
        $role->department_id = Department::findByName(Department::MAD_NAME)->id;
        $role->save();

        /*
        |--------------------------------------------------------------------------
        | PLPD roles
        |--------------------------------------------------------------------------
        */

        // PLPD Logistician
        $role = new Role();
        $role->name = Role::PLPD_LOGISTICIAN_NAME;
        $role->description = "Not fully implemented yet!";
        $role->department_id = Department::findByName(Department::PLPD_NAME)->id;
        $role->save();

        $permissionNames = Permission::getPLPDLogisticianPermissionNames();

        foreach ($permissionNames as $permissionName) {
            $role->permissions()->attach(Permission::findByName($permissionName)->id);
        }

        /*
        |--------------------------------------------------------------------------
        | CMD roles
        |--------------------------------------------------------------------------
        */

        // CMD BDM
        $role = new Role();
        $role->name = Role::CMD_BDM_NAME;
        $role->description = "User is assosiated as 'BDM'. Not fully implemented yet!";
        $role->department_id = Department::findByName(Department::CMD_NAME)->id;
        $role->save();

        $permissionNames = Permission::getCMDBDMPermissionNames();

        foreach ($permissionNames as $permissionName) {
            $role->permissions()->attach(Permission::findByName($permissionName)->id);
        }
    }
}
