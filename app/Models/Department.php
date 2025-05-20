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
    const MGMT_NAME = 'Руководство'; // Managment
    const MGMT_ABBREVIATION = 'Руководство';

    const MAD_NAME = 'Отдел анализа производителей'; // Manufacturer Analysis Department
    const MAD_ABBREVIATION = 'ОАП';

    const CMD_NAME = 'Отдел контрактного производства'; // Contract Manufacturing Department
    const CMD_ABBREVIATION = 'ОКП';

    const PLPD_NAME = 'Отдел планирования производства и логистики'; // Production & Logistics Planning Department
    const PLPD_ABBREVIATION = 'ОППЛ';

    const PRD_NAME = 'Отдел платёжной реконсиляции'; // Payment Reconciliation Department
    const PRD_ABBREVIATION = 'ОПР';

    const PPDD_NAME = 'Отдел развития продуктового портфеля'; // Product Portfolio Development Department
    const PPDD_ABBREVIATION = 'ОРПП';

    const ELD_NAME = 'Отдел логистики Европы'; // European Logistics Department
    const ELD_ABBREVIATION = 'ОЛЕ';

    const MSD_NAME = 'Отдел маркировки и сериализации'; // Marking & Serialization Department
    const MSD_ABBREVIATION = 'ОМС';

    const DD_NAME = 'Отдел дизайна'; // Design Department
    const DD_ABBREVIATION = 'ОД';

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
