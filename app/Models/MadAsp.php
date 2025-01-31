<?php

namespace App\Models;

use App\Support\Abstracts\BaseModel;
use App\Support\Contracts\Model\HasTitle;
use App\Support\Helpers\GeneralHelper;
use App\Support\Traits\Model\Commentable;

class MadAsp extends BaseModel implements HasTitle
{
    use Commentable;

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    const DEFAULT_ORDER_BY = 'year';
    const DEFAULT_ORDER_TYPE = 'desc';
    const DEFAULT_PAGINATION_LIMIT = 50;

    const STORAGE_PATH_OF_EXCEL_TEMPLATE_FILE_FOR_EXPORT = 'app/excel/export-templates/mad-asp.xlsx';
    const STORAGE_PATH_FOR_EXPORTING_EXCEL_FILES = 'app/excel/exports/mad-asp';

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    protected $guarded = ['id'];
    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function countries()
    {
        return $this->belongsToMany(Country::class, 'mad_asp_country');
    }

    public function MAHs()
    {
        return $this->belongsToMany(MarketingAuthorizationHolder::class, 'mad_asp_country_marketing_authorization_holder')
            ->withPivot(self::getPivotColumnNamesForMAHsRelation());
    }

    /**
     * Return marketing authorization holders for specific country
     */
    public function MAHsOfSpecificCountry($country)
    {
        return $this->MAHs()
            ->wherePivot('country_id', $country->id);
    }

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */
    protected static function booted(): void
    {
        static::deleting(function ($record) {
            // First remove MAHs
            $record->MAHs()->detach();

            // Then remove countries
            $record->countries()->detach();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeWithBasicRelations($query)
    {
        return $query->with([
            'lastComment',
        ]);
    }

    public function scopeWithBasicRelationCounts($query)
    {
        return $query->withCount([
            'comments',
            'countries',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Contracts
    |--------------------------------------------------------------------------
    */

    // Implement method defined in BaseModel abstract class
    public function generateBreadcrumbs(): array
    {
        return [
            ['link' => route('mad-asp.index'), 'text' => __('SPG')],
            ['link' => route('mad-asp.edit', $this->id), 'text' => $this->title],
        ];
    }

    // Implement method declared in HasTitle Interface
    public function getTitleAttribute(): string
    {
        return $this->year;
    }

    /*
    |--------------------------------------------------------------------------
    | Create & Update
    |--------------------------------------------------------------------------
    */

    public static function createFromRequest($request)
    {
        $record = self::create($request->all());
        $record->storeCommentFromRequest($request);

        // Attach countries
        $countryIDs = $request->input('country_ids', []);
        $defaultMAHs = self::getMAHIDsAttachedByDefaultOnCreate();

        foreach ($countryIDs as $country) {
            $record->countries()->attach($country);

            // Attach MAHs for each countries
            $record->MAHs()->attach($defaultMAHs, [
                'country_id' => $country,
            ]);
        }
    }

    public function updateFromRequest($request)
    {
        $this->update($request->all());
        $this->storeCommentFromRequest($request);
    }

    /*
    |--------------------------------------------------------------------------
    | Misc
    |--------------------------------------------------------------------------
    */

    public static function getPivotColumnNamesForMAHsRelation(): array
    {
        $pivots = [
            'mad_asp_id',
            'country_id',
            'marketing_authorization_holder_id',
        ];

        $months = GeneralHelper::collectCalendarMonths();

        foreach ($months as $month) {
            $pivots[] = $month['name'] . '_europe_contract_plan';
            $pivots[] = $month['name'] . '_india_contract_plan';
        }

        return $pivots;
    }

    /**
     * Return marketing authorization holder IDs attached by default on create.
     */
    public static function getMAHIDsAttachedByDefaultOnCreate()
    {
        $names = ['S', 'B', 'V', 'L', 'N'];

        return MarketingAuthorizationHolder::whereIn('name', $names)
            ->orderByRaw("FIELD(name, '" . implode("','", $names) . "')")
            ->pluck('id');
    }
}
