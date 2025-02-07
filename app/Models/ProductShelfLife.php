<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductShelfLife extends Model
{
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

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Misc
    |--------------------------------------------------------------------------
    */

    public static function getDefaultSelectedIDValue()
    {
        return self::where('name', 'TBC')->value('id');
    }
}
