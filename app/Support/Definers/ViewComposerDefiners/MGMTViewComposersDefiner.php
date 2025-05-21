<?php

namespace App\Support\Definers\ViewComposerDefiners;

use App\Models\Country;
use App\Models\Department;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\View;

class MGMTViewComposersDefiner
{
    public static function defineAll()
    {
        self::defineRolesComposer();
        self::definePermissionsComposer();
        self::defineUsersComposer();
    }

    /*
    |--------------------------------------------------------------------------
    | Definers
    |--------------------------------------------------------------------------
    */

    private static function defineRolesComposer()
    {
        View::composer('MGMT.roles.partials.filter', function ($view) {
            $view->with([
                'roles' => Role::orderByName()->get(),
                'permissions' => Permission::orderByName()->get(),
                'departments' => Department::orderByName()->get(),
            ]);
        });
    }

    private static function definePermissionsComposer()
    {
        View::composer('MGMT.permissions.partials.filter', function ($view) {
            $view->with([
                'permissions' => Permission::orderByName()->get(),
                'roles' => Role::orderByName()->get(),
                'departments' => Department::orderByName()->get(),
            ]);
        });
    }

    private static function defineUsersComposer()
    {
        View::composer('MGMT.users.partials.filter', function ($view) {
            $view->with(array_merge(self::getDefaultUsersShareData(), [
                'users' => User::getAllMinified(),
            ]));
        });

        View::composer([
            'MGMT.users.partials.create-form',
            'MGMT.users.partials.edit-form',
        ], function ($view) {
            $view->with(self::getDefaultUsersShareData());
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Default shared datas
    |--------------------------------------------------------------------------
    */

    private static function getDefaultUsersShareData()
    {
        return [
            'permissions' => Permission::orderByName()->get(),
            'roles' => Role::orderByName()->get(),
            'departments' => Department::orderByName()->get(),
            'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
        ];
    }
}
