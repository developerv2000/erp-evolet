<?php

namespace App\Support\Definers\ViewComposerDefiners;

use App\Models\Country;
use App\Models\Department;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Support\Helpers\ModelHelper;
use App\Support\Traits\Misc\DefinesViewComposer;

class GlobalViewComposersDefiner
{
    use DefinesViewComposer;

    public static function defineAll()
    {
        self::definePaginationLimitComposer();
        self::defineRolesComposer();
        self::definePermissionsComposer();
        self::defineUsersComposer();
    }

    /*
    |--------------------------------------------------------------------------
    | Definers
    |--------------------------------------------------------------------------
    */

    private static function definePaginationLimitComposer()
    {
        self::defineViewComposer('components.filter.partials.pagination-limit-input', [
            'paginationLimitOptions' => ModelHelper::getPaginationLimitOptions(),
        ]);
    }

    private static function defineRolesComposer()
    {
        self::defineViewComposer('roles.partials.filter', [
            'roles' => Role::orderByName()->get(),
            'permissions' => Permission::orderByName()->get(),
            'departments' => Department::orderByName()->get(),
        ]);
    }

    private static function definePermissionsComposer()
    {
        self::defineViewComposer('permissions.partials.filter', [
            'permissions' => Permission::orderByName()->get(),
            'roles' => Role::orderByName()->get(),
            'departments' => Department::orderByName()->get(),
        ]);
    }

    private static function defineUsersComposer()
    {
        $defaultShareData = self::getDefaultUsersShareData();

        self::defineViewComposer('users.partials.filter', array_merge($defaultShareData, [
            'users' => User::getAllMinified(),
        ]));

        self::defineViewComposer([
            'users.partials.create-form',
            'users.partials.edit-form',
        ], $defaultShareData);
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
