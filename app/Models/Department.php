<?php

namespace App\Models;

use App\Support\Traits\Model\AddsDefaultQueryParamsToRequest;
use App\Support\Traits\Model\FinalizesQueryForRequest;
use App\Support\Traits\Model\FindsRecordByName;
use App\Support\Traits\Model\ScopesOrderingByName;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
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

    // Departments
    const MANAGMENT_NAME = 'Managment';
    const MANAGMENT_ABBREVIATION = 'Managment';

    const MAD_NAME = 'Manufacturer Analysis Department';
    const MAD_ABBREVIATION = 'MAD';

    const BDM_NAME = 'Business Development Manager';
    const BDM_ABBREVIATION = 'BDM';

    const PPL_NAME = 'Отдел планирование производство и логистики';
    const PPL_ABBREVIATION = 'ОППЛ';

    const PR_NAME = 'Отдел платежной реконсиляции';
    const PR_ABBREVIATION = 'ОПР';

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

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeWithBasicRelations($query)
    {
        return $query->with([
            'roles',
            'permissions',
        ]);
    }

    public function scopeWithBasicRelationCounts($query)
    {
        return $query->withCount([
            'users',
        ]);
    }
}
