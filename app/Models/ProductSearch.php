<?php

namespace App\Models;

use App\Http\Requests\ProductSearchStoreRequest;
use App\Support\Abstracts\BaseModel;
use App\Support\Contracts\Model\CanExportRecordsAsExcel;
use App\Support\Contracts\Model\HasTitle;
use App\Support\Helpers\QueryFilterHelper;
use App\Support\Traits\Model\Commentable;
use App\Support\Traits\Model\ExportsRecordsAsExcel;
use App\Support\Traits\Model\HasAttachments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Gate;

class ProductSearch extends BaseModel implements HasTitle, CanExportRecordsAsExcel
{
    /** @use HasFactory<\Database\Factories\ProductSearchFactory> */
    use HasFactory;
    use SoftDeletes;
    use Commentable;
    use HasAttachments;
    use ExportsRecordsAsExcel;

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    const SETTINGS_MAD_TABLE_COLUMNS_KEY = 'MAD_KVPP_table_columns';

    const DEFAULT_ORDER_BY = 'updated_at';
    const DEFAULT_ORDER_TYPE = 'desc';
    const DEFAULT_PAGINATION_LIMIT = 50;

    const LIMITED_EXCEL_RECORDS_COUNT_FOR_EXPORT = 15;
    const STORAGE_PATH_OF_EXCEL_TEMPLATE_FILE_FOR_EXPORT = 'app/excel/export-templates/kvpp.xlsx';
    const STORAGE_PATH_FOR_EXPORTING_EXCEL_FILES = 'app/excel/exports/kvpp';

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

    public function status()
    {
        return $this->belongsTo(ProductSearchStatus::class, 'status_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function priority()
    {
        return $this->belongsTo(ProductSearchPriority::class, 'priority_id');
    }

    public function inn()
    {
        return $this->belongsTo(Inn::class);
    }

    public function form()
    {
        return $this->belongsTo(ProductForm::class, 'form_id');
    }

    public function MAH()
    {
        return $this->belongsTo(MarketingAuthorizationHolder::class, 'marketing_authorization_holder_id');
    }

    public function portfolioManager()
    {
        return $this->belongsTo(PortfolioManager::class);
    }

    public function analyst()
    {
        return $this->belongsTo(User::class, 'analyst_user_id');
    }

    public function additionalSearchCountries()
    {
        return $this->belongsToMany(Country::class, 'additional_search_country_product_search');
    }

    /*
    |--------------------------------------------------------------------------
    | Additional attributes
    |--------------------------------------------------------------------------
    */

    public function getMatchedProductsCountAttribute()
    {
        return Product::where([
            'inn_id' => $this->inn_id,
            'form_id' => $this->form_id,
        ])
            ->count();
    }

    public function getMatchedProcessesAttribute()
    {
        return Process::whereHas('product', function ($query) {
            $query->where([
                'inn_id' => $this->inn_id,
                'form_id' => $this->form_id,
                'dosage' => $this->dosage,
            ]);
        })
            ->where('country_id', $this->country_id)
            ->select('id', 'status_id')
            ->with('status')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::forceDeleting(function ($record) {
            $record->additionalSearchCountries()->detach();
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
            'status',
            'country',
            'priority',
            'inn',
            'MAH',
            'portfolioManager',
            'additionalSearchCountries',
            'lastComment',

            'analyst:id,name,photo',

            'form' => function ($formsQuery) {
                $formsQuery->with(['parent']);
            },
        ]);
    }

    public function scopeWithBasicRelationCounts($query)
    {
        return $query->withCount([
            'comments',
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
        $breadcrumbs = [
            ['link' => route('product-searches.index'), 'text' => __('KVPP')],
        ];

        if ($this->trashed()) {
            $breadcrumbs[] = ['link' => route('product-searches.trash'), 'text' => __('Trash')];
        }

        $breadcrumbs[] = ['link' => route('product-searches.edit', $this->id), 'text' => $this->title];

        return $breadcrumbs;
    }

    // Implement method declared in HasTitle Interface
    public function getTitleAttribute(): string
    {
        return __('KVPP') . ' #' . $this->id . ' / ' . $this->country->code;
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
            $this->source_eu ? 'EU' : '',
            $this->source_in ? 'IN' : '',
            $this->created_at,
            $this->portfolioManager?->name,
            $this->country->code,
            $this->status->name,
            $this->priority->name,
            $this->matched_processes->count(),
            $this->matched_products_count,
            $this->inn->name,
            $this->form->name,
            $this->form->parent_name,
            $this->dosage,
            $this->pack,
            $this->MAH->name,
            $this->additional_search_information,
            $this->additionalSearchCountries->pluck('code')->implode(' '),
            $this->comments->pluck('plain_text')->implode(' / '),
            $this->lastComment?->created_at,
            $this->forecast_year_1,
            $this->forecast_year_2,
            $this->forecast_year_3,
            $this->analyst?->name,
            $this->updated_at,
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

        return $query;
    }

    private static function getFilterConfig(): array
    {
        return [
            'whereEqual' => ['source_eu', 'source_in', 'priority_id', 'status_id', 'analyst_user_id'],
            'whereIn' => ['id', 'country_id', 'inn_id', 'form_id', 'marketing_authorization_holder_id', 'portfolio_manager_id'],
            'like' => ['dosage', 'pack'],
            'belongsToMany' => ['zones'],
            'dateRange' => ['created_at', 'updated_at'],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Create & Update
    |--------------------------------------------------------------------------
    */

    /**
     * Create multiple instances of the model from the request data.
     *
     * This method iterates over each marketing_authorization_holder_ids,
     * validates request for each of marketing_authorization_holder_id,
     * and creates new instances on validation success.
     *
     * @param \Illuminate\Http\Request $request The request containing data.
     * @return void
     */
    public static function createMultipleRecordsFromRequest($request)
    {
        // Extract marketing authorization holder IDs from the request
        $mahIDs = $request->input('marketing_authorization_holder_ids');

        // Iterate over each marketing authorization holder ID
        foreach ($mahIDs as $id) {
            // Merge the marketing authorization holder ID into the request
            $mergedRequest = $request->merge(['marketing_authorization_holder_id' => $id]);

            // Create a KvppStoreRequest instance from the merged request
            $formRequest = ProductSearchStoreRequest::createFrom($mergedRequest);

            // Create a validator instance
            $validator = app('validator')->make(
                $formRequest->all(),
                $formRequest->rules(),
                $formRequest->messages()
            );

            // Perform validation
            $validator->validate();

            // Create an instance using the merged request data
            $record = self::create($mergedRequest->all());

            // Store HasMany relations
            $record->storeCommentFromRequest($request->comment);

            // BelongsToMany relations
            $record->additionalSearchCountries()->attach($request->input('additionalSearchCountries'));
        }
    }

    public function updateFromRequest($request)
    {
        $this->update($request->all());

        // HasMany relations
        $this->storeCommentFromRequest($request);

        // BelongsToMany relations
        $this->additionalSearchCountries()->sync($request->input('additionalSearchCountries'));
    }

    /*
    |--------------------------------------------------------------------------
    | Misc
    |--------------------------------------------------------------------------
    */

    /**
     * Get similar records based on the provided request data.
     *
     * Used for AJAX requests to retrieve similar records, on the product searches create form.
     *
     * @param  \Illuminate\Http\Request  $request The request object containing form data.
     * @return \Illuminate\Database\Eloquent\Collection A collection of similar records.
     */
    public static function getSimilarRecordsForRequest($request)
    {
        // Get the family IDs of the selected form
        $formFamilyIDs = ProductForm::find($request->form_id)->getFamilyIDs();

        // Query similar records based on manufacturer, inn, and form family IDs
        $similarRecords = self::withTrashed()
            ->where([
                'inn_id' => $request->inn_id,
                'dosage' => $request->dosage,
                'pack' => $request->pack,
                'country_id' => $request->country_id,
            ])
            ->whereIn('form_id', $formFamilyIDs)
            ->with([
                'form',
                'country',
                'MAH',
            ])
            ->get();

        return $similarRecords;
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
        if (Gate::forUser($user)->denies('view-MAD-KVPP')) {
            return null;
        }

        $order = 1;
        $columns = array();

        if (Gate::forUser($user)->allows('edit-MAD-KVPP')) {
            array_push(
                $columns,
                ['name' => 'Edit', 'order' => $order++, 'width' => 40, 'visible' => 1],
            );
        }

        array_push(
            $columns,
            ['name' => 'ID', 'order' => $order++, 'width' => 60, 'visible' => 1],
            ['name' => 'Source EU', 'order' => $order++, 'width' => 118, 'visible' => 1],
            ['name' => 'Source IN', 'order' => $order++, 'width' => 118, 'visible' => 1],
            ['name' => 'Date of creation', 'order' => $order++, 'width' => 138, 'visible' => 1],
            ['name' => 'Portfolio manager', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'Country', 'order' => $order++, 'width' => 86, 'visible' => 1],
            ['name' => 'Status', 'order' => $order++, 'width' => 92, 'visible' => 1],
            ['name' => 'Priority', 'order' => $order++, 'width' => 106, 'visible' => 1],
        );

        if (Gate::forUser($user)->allows('view-MAD-KVPP-matching-processes')) {
            array_push(
                $columns,
                ['name' => 'Matched VPS', 'order' => $order++, 'width' => 138, 'visible' => 1],
            );
        }

        array_push(
            $columns,
            ['name' => 'Matched IVP', 'order' => $order++, 'width' => 138, 'visible' => 1],
            ['name' => 'Generic', 'order' => $order++, 'width' => 160, 'visible' => 1],
            ['name' => 'Form', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Basic form', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Dosage', 'order' => $order++, 'width' => 120, 'visible' => 1],
            ['name' => 'Pack', 'order' => $order++, 'width' => 110, 'visible' => 1],
            ['name' => 'MAH', 'order' => $order++, 'width' => 102, 'visible' => 1],
            ['name' => 'Additional search info', 'order' => $order++, 'width' => 160, 'visible' => 1],
            ['name' => 'Additional search countries', 'order' => $order++, 'width' => 192, 'visible' => 1],
            ['name' => 'Comments', 'order' => $order++, 'width' => 132, 'visible' => 1],
            ['name' => 'Last comment', 'order' => $order++, 'width' => 240, 'visible' => 1],
            ['name' => 'Comments date', 'order' => $order++, 'width' => 116, 'visible' => 1],
            ['name' => 'Forecast 1 year', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Forecast 2 year', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Forecast 3 year', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Analyst', 'order' => $order++, 'width' => 142, 'visible' => 1],
            ['name' => 'Update date', 'order' => $order++, 'width' => 150, 'visible' => 1],
        );

        return $columns;
    }
}
