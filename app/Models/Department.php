<?php

namespace App\Models;

use App\Support\Traits\Model\FindsRecordByName;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use FindsRecordByName;

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

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
}
