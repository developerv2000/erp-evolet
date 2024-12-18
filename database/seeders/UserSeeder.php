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

        $managmentDepartmentID = Department::findByName(Department::MANAGMENT_NAME)->id;
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

        $MADDepartmentID = Department::findByName(Department::MAD_NAME)->id;

        $MADAdminRoleID = Role::findByName(Role::MAD_ADMINISTRATOR_NAME);
        $MADModeratorRoleID = Role::findByName(Role::MAD_MODERATOR_NAME);
        $MADGuestRoleID = Role::findByName(Role::MAD_GUEST_NAME);
        $MADAnalystRoleID = Role::findByName(Role::MAD_ANALYST_NAME);

        $MADAdmins = [
            ['name' => 'Firdavs Kilichbekov', 'email' => 'firdavs@mail.com', 'photo' => 'developer.jpg'],
        ];

        $MADModerators = [
            ['name' => 'Nuruloev Olimjon', 'email' => 'olim@mail.com', 'photo' => 'mad.jpg'],
            ['name' => 'Shahriyor Pirov', 'email' => 'shahriyor@mail.com', 'photo' => 'mad.jpg'],
            ['name' => 'Alim Munavarov', 'email' => 'alim@mail.com', 'photo' => 'mad.jpg'],
        ];

        $MADGuests = [
            ['name' => 'Mad guest', 'email' => 'madguest@mail.com', 'photo' => 'mad.jpg'],
        ];

        // Create MAD admins
        foreach ($MADAdmins as $user) {
            $newUser = User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'photo' => $user['photo'],
                'department_id' => $MADDepartmentID,
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
                'department_id' => $MADDepartmentID,
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
                'department_id' => $MADDepartmentID,
                'password' => bcrypt($password),
            ]);

            $newUser->roles()->attach($MADGuestRoleID);
        }

        /*
        |--------------------------------------------------------------------------
        | BDM users
        |--------------------------------------------------------------------------
        */

        $BDMDepartmentID = Department::findByName(Department::BDM_NAME)->id;
        $bdmRoleID = Role::findByName(Role::BDM_NAME);

        $bdms = [
            ['name' => 'Irini Kouimtzidou', 'email' => 'irini@mail.com', 'photo' => 'bdm.jpg'],
            ['name' => 'Darya Rassulova', 'email' => 'darya@mail.com', 'photo' => 'bdm.jpg'],
            ['name' => 'Nastya Karimova', 'email' => 'nastya@mail.com', 'photo' => 'bdm.jpg'],
        ];

        // Create MAD guests
        foreach ($bdms as $user) {
            $newUser = User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'photo' => $user['photo'],
                'department_id' => $BDMDepartmentID,
                'password' => bcrypt($password),
            ]);

            $newUser->roles()->attach($bdmRoleID);
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
