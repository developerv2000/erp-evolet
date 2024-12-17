<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    // Notes: Checkout RoleSeeder for better guide.

    // Global roles
    const GLOBAL_ADMINISTRATOR_NAME = 'Administrator';   // Full access. Doesn`t attach any role related permissions.
    const INACTIVE_NAME = 'Inactive';                    // No access, can`t login. Doesn`t attach any role related permissions.

    // MAD department roles
    const MAD_ADMINISTRATOR_NAME = 'MAD administrator';  // Full access to MAD part. Attaches role related permissions.
    const MAD_MODERATOR_NAME = 'MAD moderator';          // Can view/create/edit/update/delete and export 'MAD part' and comments. Attaches role rel/perms.
    const MAD_GUEST_NAME = 'Mad Guest';                  // Can only view 'MAD part'. Can`t create/edit/update/delete and export. Attaches role rel/perms.
    const MAD_ANALYST_NAME = 'MAD Analyst';              // User is assosiated as 'Analyst'. Doesn`t attach any role related permissions.

    // BDM department roles
    const BDM_NAME = 'BDM';                              // User is assosiated as 'BDM'. Doesn`t attach any role related permissions.

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
}
