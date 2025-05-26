<?php

namespace App\Models;

use App\Support\Abstracts\BaseModel;
use App\Support\Contracts\Model\CanExportRecordsAsExcel;
use App\Support\Contracts\Model\HasTitle;
use App\Support\Helpers\QueryFilterHelper;
use App\Support\Traits\Model\Commentable;
use App\Support\Traits\Model\ExportsRecordsAsExcel;
use App\Support\Traits\Model\GetsMinifiedRecordsWithName;
use App\Support\Traits\Model\HasAttachments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Gate;

class Manufacturer extends BaseModel implements HasTitle, CanExportRecordsAsExcel
{
    /** @use HasFactory<\Database\Factories\ManufacturerFactory> */
    use HasFactory;
    use SoftDeletes;
    use Commentable;
    use HasAttachments;
    use GetsMinifiedRecordsWithName;
    use ExportsRecordsAsExcel;

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    const SETTINGS_MAD_TABLE_COLUMNS_KEY = 'MAD_EPP_table_columns';

    const DEFAULT_ORDER_BY = 'updated_at';
    const DEFAULT_ORDER_TYPE = 'desc';
    const DEFAULT_PAGINATION_LIMIT = 50;

    const LIMITED_EXCEL_RECORDS_COUNT_FOR_EXPORT = 5;
    const STORAGE_PATH_OF_EXCEL_TEMPLATE_FILE_FOR_EXPORT = 'app/excel/export-templates/epp.xlsx';
    const STORAGE_PATH_FOR_EXPORTING_EXCEL_FILES = 'app/excel/exports/epp';

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

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function processes()
    {
        return $this->hasManyThrough(
            Process::class,
            Product::class,
            'manufacturer_id', // Foreign key on Products table
            'product_id',   // Foreign key on Processes table
            'id',         // Local key on Manufacturers table
            'id'          // Local key on Products table
        );
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }

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
    | Additional attributes
    |--------------------------------------------------------------------------
    */

    /**
     * Used on manufacturers.edit form
     */
    public function getPresenceNamesArrayAttribute(): array
    {
        return $this->presences->pluck('name')->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::saving(function ($record) {
            $record->name = mb_strtoupper($record->name);
        });

        static::deleting(function ($record) { // trashing
            foreach ($record->products as $product) {
                $product->delete();
            }

            foreach ($record->meetings as $meeting) {
                $meeting->delete();
            }
        });

        static::restored(function ($record) {
            foreach ($record->products()->onlyTrashed()->get() as $product) {
                $product->restore();
            }

            foreach ($record->meetings()->onlyTrashed()->get() as $meeting) {
                $meeting->restore();
            }
        });

        static::forceDeleting(function ($record) {
            $record->zones()->detach();
            $record->productClasses()->detach();
            $record->blacklists()->detach();

            foreach ($record->presences as $presence) {
                $presence->delete();
            }

            foreach ($record->products()->withTrashed()->get() as $product) {
                $product->forceDelete();
            }

            foreach ($record->meetings()->withTrashed()->get() as $meeting) {
                $meeting->forceDelete();
            }
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
            'country',
            'category',
            'presences',
            'blacklists',
            'productClasses',
            'zones',
            'attachments',
            'lastComment',

            'analyst:id,name,photo',
            'bdm:id,name,photo',
        ]);
    }

    public function scopeWithBasicRelationCounts($query)
    {
        return $query->withCount([
            'comments',
            'attachments',
            'products',
            'meetings',
        ]);
    }

    public function scopeOnlyRecordsWithProcessesReadyForOrder($query)
    {
        return $query->whereHas('processes', function ($processesQuery) {
            $processesQuery->whereNotNull('readiness_for_order_date');
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Contracts
    |--------------------------------------------------------------------------
    */

    // Implement method defined in BaseModel abstract class
    public function generateBreadcrumbs(): array
    {
        $breadcrumbs = [
            ['link' => route('mad.manufacturers.index'), 'text' => __('EPP')],
        ];

        if ($this->trashed()) {
            $breadcrumbs[] = ['link' => route('mad.manufacturers.trash'), 'text' => __('Trash')];
        }

        $breadcrumbs[] = ['link' => route('mad.manufacturers.edit', $this->id), 'text' => $this->title];

        return $breadcrumbs;
    }

    // Implement method declared in HasTitle Interface
    public function getTitleAttribute(): string
    {
        return $this->name;
    }

    // Implement method declared in CanExportRecordsAsExcel Interface
    public function scopeWithRelationsForExport($query)
    {
        return $query->withBasicRelations()
            ->withBasicRelationCounts()
            ->with(['comments']);
    }

    // Implement method declared in CanExportRecordsAsExcel Interface
    public function getExcelColumnValuesForExport(): array
    {
        return [
            $this->id,
            $this->bdm->name,
            $this->analyst->name,
            $this->country->name,
            $this->products_count,
            $this->name,
            $this->category->name,
            $this->active ? __('Active') : __('Stoped'),
            $this->important ? __('Important') : '',
            $this->productClasses->pluck('name')->implode(' '),
            $this->zones->pluck('name')->implode(' '),
            $this->blacklists->pluck('name')->implode(' '),
            $this->presences->pluck('name')->implode(' '),
            $this->website,
            $this->about,
            $this->relationship,
            $this->comments->pluck('plain_text')->implode(' / '),
            $this->lastComment?->created_at,
            $this->created_at,
            $this->updated_at,
            $this->meetings_count,
        ];
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

        // Additional filters
        self::applyRegionFilter($query, $request);
        self::applyProcessCountriesFilter($query, $request);
        self::applyHasActiveProcessesForMonthFilter($query, $request);

        return $query;
    }

    /**
     * Apply filters to the query based on the specific manufacturer countries.
     *
     * @param Illuminate\Database\Eloquent\Builder $query The query builder instance to apply filters to.
     * @param Illuminate\Http\Request $request The HTTP request object containing filter parameters.
     * @return Illuminate\Database\Eloquent\Builder The modified query builder instance.
     */
    public static function applyRegionFilter($query, $request)
    {
        $region = $request->input('region');

        if ($region) {
            // Get the ID of the country 'INDIA' for comparison
            $indiaCountryId = Country::getIndiaCountryID();

            // Apply conditions based on the region
            switch ($region) {
                case 'Europe':
                    // Exclude manufacturers from India
                    $query->where('country_id', '!=', $indiaCountryId);
                    break;

                case 'India':
                    // Include only manufacturers from India
                    $query->where('country_id', $indiaCountryId);
                    break;
            }
        }
    }

    /**
     * Apply filters to the query based on the country ID of related processes.
     *
     * This filter method returns records, which have related processes for selected countries.
     */
    public static function applyProcessCountriesFilter($query, $request)
    {
        $relationInAmbiguous = [
            [
                'name' => 'processes',
                'attribute' => 'process_country_id',
                'ambiguousAttribute' => 'processes.country_id',
            ]
        ];

        QueryFilterHelper::filterRelationInAmbiguous($request, $query, $relationInAmbiguous);
    }

    /**
     * Filter only manufacturers which have active processes fot specific month.
     *
     * This function filters the query to include only records that have
     * associated processes with status history starting in the specified month and year
     * and have stage <= 5.
     */
    public static function applyHasActiveProcessesForMonthFilter($query, $request)
    {
        if ($request->filled('has_active_processes_for_specific_month')) {
            return $query->whereHas('processes.statusHistory', function ($historyQuery) use ($request) {
                $historyQuery
                    ->whereYear('start_date', $request->input('has_active_processes_for_year'))
                    ->whereMonth('start_date', $request->input('has_active_processes_for_month'))
                    ->whereHas('status.generalStatus', function ($statusQuery) {
                        $statusQuery->where('stage', '<=', 5);
                    });
            });
        }
    }

    private static function getFilterConfig(): array
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
    | Create & Update
    |--------------------------------------------------------------------------
    */

    public static function createFromRequest($request)
    {
        $record = self::create($request->all());

        // BelongsToMany relations
        $record->zones()->attach($request->input('zones'));
        $record->productClasses()->attach($request->input('productClasses'));
        $record->blacklists()->attach($request->input('blacklists'));

        // HasMany relations
        $record->storePresencesOnCreate($request->input('presences'));
        $record->storeCommentFromRequest($request);
        $record->storeAttachmentsFromRequest($request);
    }

    private function storePresencesOnCreate($presences)
    {
        if (!$presences) return;

        foreach ($presences as $name) {
            $this->presences()->create(['name' => $name]);
        }
    }

    public function updateFromRequest($request)
    {
        $this->update($request->all());

        // BelongsToMany relations
        $this->zones()->sync($request->input('zones'));
        $this->productClasses()->sync($request->input('productClasses'));
        $this->blacklists()->sync($request->input('blacklists'));

        // HasMany relations
        $this->syncPresencesOnEdit($request);
        $this->storeCommentFromRequest($request);
        $this->storeAttachmentsFromRequest($request);
    }

    private function syncPresencesOnEdit($request)
    {
        $presences = $request->input('presences');

        // Remove existing presences if $presences is empty
        if (!$presences) {
            $this->presences()->delete();
            return;
        }

        // Add new presences
        foreach ($presences as $name) {
            if (!$this->presences->contains('name', $name)) {
                $this->presences()->create(['name' => $name]);
            }
        }

        // Delete removed presences
        $this->presences()->whereNotIn('name', $presences)->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | Order part
    |--------------------------------------------------------------------------
    */

    public static function getRecordsWithProcessesReadyForOrder()
    {
        return self::onlyRecordsWithProcessesReadyForOrder()->minifiedRecordsWithName()->get();
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
     * Return an array of status options
     *
     * Used on records create/update as radiogroups options
     *
     * @return array
     */
    public static function getStatusOptions()
    {
        return [
            (object) ['caption' => trans('Active'), 'value' => 1],
            (object) ['caption' => trans('Stopped'), 'value' => 0],
        ];
    }

    /**
     * Provides the default MAD table columns along with their properties.
     *
     * These columns are typically used to display data in tables,
     * such as on index and trash pages, and are iterated over in a loop.
     *
     * @return array
     */
    public static function getDefaultMADTableColumnsForUser($user)
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
            ['name' => 'IVP', 'order' => $order++, 'width' => 104, 'visible' => 1],
            ['name' => 'Manufacturer', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'Category', 'order' => $order++, 'width' => 104, 'visible' => 1],
            ['name' => 'Status', 'order' => $order++, 'width' => 106, 'visible' => 1],
            ['name' => 'Important', 'order' => $order++, 'width' => 100, 'visible' => 1],
            ['name' => 'Product class', 'order' => $order++, 'width' => 114, 'visible' => 1],
            ['name' => 'Zones', 'order' => $order++, 'width' => 54, 'visible' => 1],
            ['name' => 'Blacklist', 'order' => $order++, 'width' => 120, 'visible' => 1],
            ['name' => 'Presence', 'order' => $order++, 'width' => 128, 'visible' => 1],
            ['name' => 'Website', 'order' => $order++, 'width' => 180, 'visible' => 1],
            ['name' => 'About company', 'order' => $order++, 'width' => 240, 'visible' => 1],
            ['name' => 'Relationship', 'order' => $order++, 'width' => 200, 'visible' => 1],
            ['name' => 'Comments', 'order' => $order++, 'width' => 132, 'visible' => 1],
            ['name' => 'Last comment', 'order' => $order++, 'width' => 240, 'visible' => 1],
            ['name' => 'Comments date', 'order' => $order++, 'width' => 116, 'visible' => 1],
            ['name' => 'Date of creation', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Update date', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'Meetings', 'order' => $order++, 'width' => 86, 'visible' => 1],
            ['name' => 'ID', 'order' => $order++, 'width' => 62, 'visible' => 1],
            ['name' => 'Attachments', 'order' => $order++, 'width' => 260, 'visible' => 1],
        );

        return $columns;
    }
}
