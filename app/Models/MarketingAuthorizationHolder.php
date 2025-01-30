<?php

namespace App\Models;

use App\Support\Traits\Model\ScopesOrderingByName;
use Illuminate\Database\Eloquent\Model;

class MarketingAuthorizationHolder extends Model
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

    public function processes()
    {
        return $this->hasMany(Process::class);
    }

    public function productSearches()
    {
        return $this->hasMany(ProductSearch::class);
    }

    public function madAsps()
    {
        return $this->belongsToMany(MadAsp::class, 'mad_asp_country_marketing_authorization_holder');
    }

    public function madAspCountries()
    {
        return $this->belongsToMany(Country::class, 'mad_asp_country_marketing_authorization_holder')
            ->withPivot(MadAsp::getPivotColumnNamesForMAHRelation());
    }

    /*
    |--------------------------------------------------------------------------
    | Misc
    |--------------------------------------------------------------------------
    */

    public static function getDefaultSelectedIDValue()
    {
        return self::where('name', 'Обсуждается')->value('id');
    }
}
