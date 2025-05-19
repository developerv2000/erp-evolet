<?php

namespace App\Models;

use App\Support\Helpers\QueryFilterHelper;
use App\Support\Traits\Model\AddsDefaultQueryParamsToRequest;
use App\Support\Traits\Model\FinalizesQueryForRequest;
use App\Support\Traits\Model\FindsRecordByName;
use App\Support\Traits\Model\ScopesOrderingByName;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use FindsRecordByName;
    use ScopesOrderingByName;
    use AddsDefaultQueryParamsToRequest;
    use FinalizesQueryForRequest;

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    // Querying
    const DEFAULT_ORDER_BY = 'name';
    const DEFAULT_ORDER_TYPE = 'asc';
    const DEFAULT_PAGINATION_LIMIT = 50;

    // Notes: Checkout RoleSeeder for better guide.

    // Global roles
    const GLOBAL_ADMINISTRATOR_NAME = 'Global administrator';   // Full access. Doesn`t attach any role related permissions.
    const INACTIVE_NAME = 'Inactive';                           // No access, can`t login. Doesn`t attach any role related permissions.

    // MAD department roles
    const MAD_ADMINISTRATOR_NAME = 'MAD administrator';         // Full access to 'MAD part'. Attaches role related permissions.
    const MAD_MODERATOR_NAME = 'MAD moderator';                 // Can view/create/edit/update/delete/export all 'MAD part' and comments. Attaches role related permissions.
    const MAD_GUEST_NAME = 'MAD guest';                         // Can only view 'MAD EPP/IVP/KVPP'. Can`t create/edit/update/delete/export. Attaches role related permissions.
    const MAD_INTERN_NAME = 'MAD intern';                       // Can view/edit only 'EPP' and 'IVP' of 'MAD part'. Attaches role related permissions.
    const MAD_ANALYST_NAME = 'MAD analyst';                     // User is assosiated as 'Analyst'. Doesn`t attach any role related permissions.

    // CMD department roles
    const BDM_NAME = 'BDM';                                     // User is assosiated as 'BDM'. Doesn`t attach any role related permissions.

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

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeWithBasicRelations($query)
    {
        return $query->with([
            'department',
            'permissions',
        ]);
    }

    public function scopeWithBasicRelationCounts($query)
    {
        return $query->withCount([
            'users',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Filtering
    |--------------------------------------------------------------------------
    */

    public static function filterQueryForRequest($query, $request)
    {
        // Apply base filters using helper
        $query = QueryFilterHelper::applyFilters($query, $request, self::getFilterConfig());

        return $query;
    }

    private static function getFilterConfig(): array
    {
        return [
            'whereIn' => ['id', 'department_id'],
            'whereEqual' => ['global'],
            'belongsToMany' => ['permissions'],
        ];
    }
}
