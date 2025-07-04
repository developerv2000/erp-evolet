<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default password
        $password = '12345';

        /*
        |--------------------------------------------------------------------------
        | Global users
        |--------------------------------------------------------------------------
        */

        $managmentDepartmentID = Department::findByName(Department::MGMT_NAME)->id;
        $globalAdminRoleID = Role::findByName(Role::GLOBAL_ADMINISTRATOR_NAME);

        $globalAdmins = [
            ['name' => 'Nuridinov Bobur', 'email' => 'developer@mail.com', 'photo' => 'developer.jpg'],
        ];

        // Create global admins
        foreach ($globalAdmins as $user) {
            $newUser = User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'photo' => $user['photo'],
                'department_id' => $managmentDepartmentID,
                'password' => bcrypt($password),
            ]);

            $newUser->roles()->attach($globalAdminRoleID);
        }

        /*
        |--------------------------------------------------------------------------
        | MAD users
        |--------------------------------------------------------------------------
        */

        $MadID = Department::findByName(Department::MAD_NAME)->id;

        $MADAdminRoleID = Role::findByName(Role::MAD_ADMINISTRATOR_NAME);
        $MADModeratorRoleID = Role::findByName(Role::MAD_MODERATOR_NAME);
        $MADGuestRoleID = Role::findByName(Role::MAD_GUEST_NAME);
        $MADAnalystRoleID = Role::findByName(Role::MAD_ANALYST_NAME);

        $MADAdmins = [
            ['name' => 'Firdavs Kilichbekov', 'email' => 'firdavs@mail.com', 'photo' => 'developer.jpg'],
        ];

        $MADModerators = [
            ['name' => 'Nuruloev Olimjon', 'email' => 'olim@mail.com', 'photo' => 'mad.png'],
            ['name' => 'Shahriyor Pirov', 'email' => 'shahriyor@mail.com', 'photo' => 'mad.png'],
            ['name' => 'Alim Munavarov', 'email' => 'alim@mail.com', 'photo' => 'mad.png'],
        ];

        $MADGuests = [
            ['name' => 'Mad guest', 'email' => 'madguest@mail.com', 'photo' => 'mad.png'],
        ];

        // Create MAD admins
        foreach ($MADAdmins as $user) {
            $newUser = User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'photo' => $user['photo'],
                'department_id' => $MadID,
                'password' => bcrypt($password),
            ]);

            $newUser->roles()->attach($MADAdminRoleID);
        }

        // Create MAD moderators
        foreach ($MADModerators as $user) {
            $newUser = User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'photo' => $user['photo'],
                'department_id' => $MadID,
                'password' => bcrypt($password),
            ]);

            $newUser->roles()->attach([$MADModeratorRoleID, $MADAnalystRoleID]);
        }

        // Create MAD guests
        foreach ($MADGuests as $user) {
            $newUser = User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'photo' => $user['photo'],
                'department_id' => $MadID,
                'password' => bcrypt($password),
            ]);

            $newUser->roles()->attach($MADGuestRoleID);
        }

        /*
        |--------------------------------------------------------------------------
        | PLPD users
        |--------------------------------------------------------------------------
        */

        $plpdID = Department::findByName(Department::PLPD_NAME)->id;
        $logisticianRoleID = Role::findByName(Role::PLPD_LOGISTICIAN_NAME);

        $logisticians = [
            ['name' => 'Logistic man', 'email' => 'logistician@mail.com', 'photo' => 'bdm.png'],
        ];

        // Create PLPD logisticians
        foreach ($logisticians as $user) {
            $newUser = User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'photo' => $user['photo'],
                'department_id' => $plpdID,
                'password' => bcrypt($password),
            ]);

            $newUser->roles()->attach($logisticianRoleID);
        }

        /*
        |--------------------------------------------------------------------------
        | CMD users
        |--------------------------------------------------------------------------
        */

        $cmdID = Department::findByName(Department::CMD_NAME)->id;
        $bdmRoleID = Role::findByName(Role::CMD_BDM_NAME);

        $bdms = [
            ['name' => 'Irini Kouimtzidou', 'email' => 'irini@mail.com', 'photo' => 'bdm.png'],
            ['name' => 'Darya Rassulova', 'email' => 'darya@mail.com', 'photo' => 'bdm.png'],
            ['name' => 'Nastya Karimova', 'email' => 'nastya@mail.com', 'photo' => 'bdm.png'],
        ];

        // Create CMD BDMs
        foreach ($bdms as $user) {
            $newUser = User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'photo' => $user['photo'],
                'department_id' => $cmdID,
                'password' => bcrypt($password),
            ]);

            $newUser->roles()->attach($bdmRoleID);
        }

        /*
        |--------------------------------------------------------------------------
        | DD users
        |--------------------------------------------------------------------------
        */

        $ddID = Department::findByName(Department::DD_NAME)->id;
        $designerRoleID = Role::findByName(Role::DD_DESIGNER_NAME);

        $designers = [
            ['name' => 'Designer man', 'email' => 'designer@mail.com', 'photo' => 'bdm.png'],
        ];

        // Create DD designers
        foreach ($designers as $user) {
            $newUser = User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'photo' => $user['photo'],
                'department_id' => $ddID,
                'password' => bcrypt($password),
            ]);

            $newUser->roles()->attach($designerRoleID);
        }

        /*
        |--------------------------------------------------------------------------
        | PRD users
        |--------------------------------------------------------------------------
        */

        $prdID = Department::findByName(Department::PRD_NAME)->id;
        $financierRoleID = Role::findByName(Role::PRD_FINANCIER_NAME);

        $financiers = [
            ['name' => 'Financier man', 'email' => 'financier@mail.com', 'photo' => 'bdm.png'],
        ];

        // Create PRD financiers
        foreach ($financiers as $user) {
            $newUser = User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'photo' => $user['photo'],
                'department_id' => $prdID,
                'password' => bcrypt($password),
            ]);

            $newUser->roles()->attach($financierRoleID);
        }

        /*
        |--------------------------------------------------------------------------
        | Reset all user settings to default
        |--------------------------------------------------------------------------
        */

        User::all()->each(function ($user) {
            $user->resetAllSettingsToDefault();
        });
    }
}
