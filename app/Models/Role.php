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
    const ANALYST_NAME = 'Analyst';                      // User is assosiated as 'Analyst'. Doesn`t attach any role related permissions.
    const BDM_NAME = 'BDM';                              // User is assosiated as 'BDM'. Doesn`t attach any role related permissions.

    // MAD department roles
    const MAD_ADMINISTRATOR_NAME = 'MAD administrator';   // Full access for MAD part. Attach role related permissions
    const MAD_MODERATOR_NAME = 'MAD moderator';           // Can view/create/edit/update/delete and export 'MAD part' and comments. Attachs role rel/prms.
    const MAD_GUEST_NAME = 'Mad Guest';                   // Can only view 'MAD part'. Can`t create/edit/update/delete and export. Attaches role rel/prms.
}
