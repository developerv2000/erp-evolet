<?php

namespace App\Models;

use App\Support\Traits\Model\ScopesOrderingByName;
use Illuminate\Database\Eloquent\Model;

class ManufacturerCategory extends Model
{
    use ScopesOrderingByName;

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    public $timestamps = false;
    protected $guarded = ['id'];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function manufacturers()
    {
        return $this->hasMany(Manufacturer::class, 'category_id');
    }
}
