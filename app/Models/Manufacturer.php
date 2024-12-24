<?php

namespace App\Models;

use App\Support\Helpers\QueryFilterHelper;
use App\Support\Traits\Model\AddsDefaultQueryParamsToRequest;
use App\Support\Traits\Model\Commentable;
use App\Support\Traits\Model\FinalizesQueryForRequest;
use App\Support\Traits\Model\HasAttachments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Gate;

class Manufacturer extends Model
{
    /** @use HasFactory<\Database\Factories\ManufacturerFactory> */
    use HasFactory;
    use SoftDeletes;
    use Commentable;
    use HasAttachments;
    use AddsDefaultQueryParamsToRequest;
    use FinalizesQueryForRequest;

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    const DEFAULT_ORDER_BY = 'updated_at';
    const DEFAULT_ORDER_TYPE = 'desc';
    const DEFAULT_PAGINATION_LIMIT = 50;

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    protected $guarded = ['id'];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo(ManufacturerCategory::class);
    }

    public function blacklists()
    {
        return $this->belongsToMany(ManufacturerBlacklist::class);
    }

    public function presences()
    {
        return $this->hasMany(ManufacturerPresence::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function productClasses()
    {
        return $this->belongsToMany(ProductClass::class);
    }

    public function zones()
    {
        return $this->belongsToMany(Zone::class);
    }

    public function analyst()
    {
        return $this->belongsTo(User::class, 'analyst_user_id');
    }

    public function bdm()
    {
        return $this->belongsTo(User::class, 'bdm_user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeWithBasicRelations($query)
    {
        return $query->with([
            'country',
            'category',
            'presences',
            'blacklists',
            'productClasses',
            'attachments',
            'zones',
            'lastComment',

            'analyst' => function ($query) {
                $query->select(['id', 'name', 'photo'])
                    ->withOnly([]);
            },

            'bdm' => function ($query) {
                $query->select(['id', 'name', 'photo'])
                    ->withOnly([]);
            },
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

    public static function getFilterConfig(): array
    {
        return [
            'whereEqual' => ['analyst_user_id', 'bdm_user_id', 'category_id', 'active', 'important'],
            'whereIn' => ['id', 'country_id'],
            'belongsToMany' => ['productClasses', 'zones', 'blacklists'],
            'dateRange' => ['created_at', 'updated_at'],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Misc
    |--------------------------------------------------------------------------
    */

    /**
     * Update self 'updated_at' field on comment store
     */
    public function updateSelfOnCommentCreate()
    {
        $this->updateQuietly(['updated_at' => now()]);
    }

    /**
     * Provides the default table columns along with their properties.
     *
     * These columns are typically used to display data in tables,
     * such as on index and trash pages, and are iterated over in a loop.
     *
     * @return array
     */
    public static function getDefaultTableColumnsForUser($user)
    {
        if (Gate::forUser($user)->denies('view-MAD-EPP')) {
            return null;
        }

        $order = 1;
        $columns = array();

        if (Gate::forUser($user)->allows('edit-MAD-EPP')) {
            array_push(
                $columns,
                ['name' => 'Edit', 'order' => $order++, 'width' => 40, 'visible' => 1],
            );
        }

        array_push(
            $columns,
            ['name' => 'BDM', 'order' => $order++, 'width' => 142, 'visible' => 1],
            ['name' => 'Analyst', 'order' => $order++, 'width' => 142, 'visible' => 1],
            ['name' => 'Country', 'order' => $order++, 'width' => 144, 'visible' => 1],
            ['name' => 'IVP', 'order' => $order++, 'width' => 120, 'visible' => 1],
            ['name' => 'Manufacturer', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'Category', 'order' => $order++, 'width' => 104, 'visible' => 1],
            ['name' => 'Status', 'order' => $order++, 'width' => 106, 'visible' => 1],
            ['name' => 'Important', 'order' => $order++, 'width' => 100, 'visible' => 1],
            ['name' => 'Product class', 'order' => $order++, 'width' => 126, 'visible' => 1],
            ['name' => 'Zones', 'order' => $order++, 'width' => 54, 'visible' => 1],
            ['name' => 'Black list', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'Presence', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'Website', 'order' => $order++, 'width' => 180, 'visible' => 1],
            ['name' => 'About company', 'order' => $order++, 'width' => 240, 'visible' => 1],
            ['name' => 'Relationship', 'order' => $order++, 'width' => 200, 'visible' => 1],
            ['name' => 'Comments', 'order' => $order++, 'width' => 132, 'visible' => 1],
            ['name' => 'Last comment', 'order' => $order++, 'width' => 240, 'visible' => 1],
            ['name' => 'Comments date', 'order' => $order++, 'width' => 116, 'visible' => 1],
            ['name' => 'Date of creation', 'order' => $order++, 'width' => 138, 'visible' => 1],
            ['name' => 'Update date', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'Meetings', 'order' => $order++, 'width' => 106, 'visible' => 1],
            ['name' => 'ID', 'order' => $order++, 'width' => 70, 'visible' => 1],
            ['name' => 'Attachments', 'order' => $order++, 'width' => 160, 'visible' => 1],
        );

        if (Gate::forUser($user)->allows('edit-MAD-EPP')) {
            array_push(
                $columns,
                ['name' => 'Edit attachments', 'order' => $order++, 'width' => 192, 'visible' => 1],
            );
        }

        return $columns;
    }
}
