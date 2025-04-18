<?php

namespace App\Models;

use App\Notifications\ProcessStageUpdatedToContract;
use App\Support\Abstracts\BaseModel;
use App\Support\Contracts\Model\CanExportRecordsAsExcel;
use App\Support\Contracts\Model\ExportsProductSelection;
use App\Support\Contracts\Model\HasTitle;
use App\Support\Contracts\Model\PreparesFetchedRecordsForExport;
use App\Support\Helpers\GeneralHelper;
use App\Support\Helpers\QueryFilterHelper;
use App\Support\Traits\Model\Commentable;
use App\Support\Traits\Model\ExportsRecordsAsExcel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class Process extends BaseModel implements HasTitle, CanExportRecordsAsExcel, PreparesFetchedRecordsForExport, ExportsProductSelection
{
    /** @use HasFactory<\Database\Factories\ProcessFactory> */
    use HasFactory;
    use SoftDeletes;
    use Commentable;
    use ExportsRecordsAsExcel;

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    const SETTINGS_MAD_TABLE_COLUMNS_KEY = 'MAD_VPS_table_columns';
    const SETTINGS_MAD_DH_TABLE_COLUMNS_KEY = 'MAD_DH_table_columns';

    const DEFAULT_ORDER_BY = 'updated_at';
    const DEFAULT_ORDER_TYPE = 'desc';
    const DEFAULT_PAGINATION_LIMIT = 50;

    const LIMITED_EXCEL_RECORDS_COUNT_FOR_EXPORT = 15;
    const STORAGE_PATH_OF_EXCEL_TEMPLATE_FILE_FOR_EXPORT = 'app/excel/export-templates/vps.xlsx';
    const STORAGE_PATH_FOR_EXPORTING_EXCEL_FILES = 'app/excel/exports/vps';

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    protected $guarded = ['id'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'forecast_year_1_update_date' => 'date',
            'increased_price_date' => 'date',
            'responsible_person_update_date' => 'date',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function manufacturer()
    {
        return $this->hasOneThrough(
            Manufacturer::class,
            Product::class,
            'id', // Foreign key on the Products table
            'id', // Foreign key on the Manufacturers table
            'product_id', // Local key on the Processes table
            'manufacturer_id' // Local key on the Products table
        )->withTrashedParents()->withTrashed();
    }

    public function status()
    {
        return $this->belongsTo(ProcessStatus::class, 'status_id');
    }

    public function statusHistory()
    {
        return $this->hasMany(ProcessStatusHistory::class)->orderBy('start_date', 'asc');
    }

    public function activeStatusHistory()
    {
        return $this->hasOne(ProcessStatusHistory::class)
            ->whereNull('end_date');
    }

    public function searchCountry()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function MAH()
    {
        return $this->belongsTo(MarketingAuthorizationHolder::class, 'marketing_authorization_holder_id');
    }

    public function responsiblePerson()
    {
        return $this->belongsTo(ProcessResponsiblePerson::class, 'responsible_person_id');
    }

    public function clinicalTrialCountries()
    {
        return $this->belongsToMany(Country::class, 'clinical_trial_country_process');
    }

    /*
    |--------------------------------------------------------------------------
    | Additional attributes
    |--------------------------------------------------------------------------
    */

    /**
     * Also used while exporting product selection.
     */
    public function getMatchedProductSearchesAttribute()
    {
        return ProductSearch::where([
            'inn_id' => $this->product->inn_id,
            'form_id' => $this->product->form_id,
            'dosage' => $this->product->dosage,
            'country_id' => $this->country_id,
        ])
            ->select('id', 'country_id', 'status_id')
            ->get();
    }

    /**
     * Get the number of days past since the 'responsible_person_update_date'.
     *
     * @return int|null Number of days past since the update date, or null if the date is not set.
     */
    public function getDaysPastAttribute()
    {
        if ($this->responsible_person_update_date) {
            return round($this->responsible_person_update_date->diffInDays(now(), true));
        }

        return null;
    }

    /**
     * Get the manufacturer's offered price in USD.
     *
     * This accessor calculates the manufacturer's offered price in USD by
     * considering the followed offered price if available; otherwise,
     * it defaults to the first offered price. If a price exists, it
     * converts the value to USD using the associated currency.
     *
     * @return float|null The manufacturer's offered price in USD, or null if no price is available.
     */
    public function getManufacturerOfferedPriceInUsdAttribute()
    {
        // Determine the manufacturer's final offered price:
        // Use the followed price if it exists; otherwise, use the first price.
        $finalPrice = $this->manufacturer_followed_offered_price
            ?: $this->manufacturer_first_offered_price;

        return $finalPrice
            ? Currency::convertPriceToUSD($finalPrice, $this->currency)
            : null;
    }

    /**
     * Get the percentage increase of the price.
     *
     * @return float|null The percentage increase, rounded to two decimal places, or null if calculation is not possible.
     */
    public function getIncreasedPricePercentageAttribute()
    {
        // Retrieve the increased price and agreed price
        $increasedPrice = $this->increased_price;
        $agreedPrice = $this->agreed_price;

        // Calculate the percentage increase if both values are available
        return ($increasedPrice && $agreedPrice)
            ? round(GeneralHelper::calculatePercentageOfTotal($agreedPrice, $increasedPrice), 2)
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::creating(function ($record) {
            $record->responsible_person_update_date = now();
            $record->created_at = $record->created_at ?: now();
        });

        static::created(function ($record) {
            $record->addStatusHistoryForCurrentStatus($record->created_at);
        });

        static::updating(function ($record) {
            $record->updateStatusHistory();
            $record->notifyUsersOnContractStage();
            $record->handleResponsiblePersonUpdateDate();
        });

        static::saving(function ($record) {
            $record->trademark_en = mb_strtoupper($record->trademark_en ?: '');
            $record->trademark_ru = mb_strtoupper($record->trademark_ru ?: '');

            $record->syncRelatedProductUpdates();
            $record->handleForecastUpdateDate();
            $record->handleIncreasedPriceDate();
        });

        static::restoring(function ($record) {
            if ($record->product->trashed()) {
                $record->product->restore();
            }
        });

        static::forceDeleting(function ($record) {
            $record->clinicalTrialCountries()->detach();

            foreach ($record->statusHistory as $history) {
                $history->delete();
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
            'searchCountry',
            'currency',
            'MAH',
            'clinicalTrialCountries',
            'responsiblePerson',
            'lastComment',

            'statusHistory' => function ($historyQuery) {
                $historyQuery->with(['status']);
            },

            'status' => function ($statusQuery) {
                $statusQuery->with('generalStatus');
            },

            'product' => function ($productQuery) {
                $productQuery->select(
                    'id',
                    'manufacturer_id',
                    'inn_id',
                    'atx_id',
                    'class_id',
                    'form_id',
                    'dosage',
                    'pack',
                    'moq',
                    'shelf_life_id'
                )
                    ->with([
                        'inn',
                        'atx',
                        'shelfLife',
                        'class',
                        'form',
                        'zones',

                        'manufacturer' => function ($manufQuery) {
                            $manufQuery->select(
                                'id',
                                'name',
                                'category_id',
                                'country_id',
                                'bdm_user_id',
                                'analyst_user_id'
                            )
                                ->with([
                                    'category',
                                    'country',
                                    'analyst:id,name,photo',
                                    'bdm:id,name,photo',
                                ]);
                        },
                    ]);
            },
        ]);
    }

    /**
     * Add 'product_manufacturer_name' attribute.
     *
     * Used while ordering processes by 'product_manufacturer_name'
     */
    public function scopeWithProductsManufacturerNameAttribute($query)
    {
        return $query
            ->join('products', 'products.id', '=', 'processes.product_id')
            ->join('manufacturers', 'manufacturers.id', '=', 'products.manufacturer_id')
            ->selectRaw('manufacturers.name as product_manufacturer_name');
    }

    /**
     * Add 'product_form_name' attribute.
     *
     * Used while ordering processes by 'product_form_name'
     */
    public function scopeWithProductsFormNameAttribute($query)
    {
        return $query
            ->join('products', 'products.id', '=', 'processes.product_id')
            ->join('product_forms', 'product_forms.id', '=', 'products.form_id')
            ->selectRaw('product_forms.name as product_form_name');
    }

    /**
     * Add 'product_dosage' attribute.
     *
     * Used while ordering processes by 'product_dosage'
     */
    public function scopeWithProductsDosageAttribute($query)
    {
        return $query
            ->join('products', 'products.id', '=', 'processes.product_id')
            ->selectRaw('products.dosage as product_dosage');
    }

    /**
     * Scope the query to include basic relation for process status history pages.
     */
    public function scopeWitRelationsForHistoryPage($query)
    {
        return $query->with([
            'statusHistory' => function ($historyQuery) {
                $historyQuery->with([
                    'status' => function ($statusQuery) {
                        $statusQuery->with('generalStatus');
                    },
                ]);
            },

            'product' => function ($productQuery) {
                $productQuery->select(
                    'id',
                    'manufacturer_id',
                    'inn_id',
                    'form_id',
                    'dosage',
                    'pack',
                )
                    ->with([
                        'inn',
                        'form',

                        'manufacturer' => function ($manufQuery) {
                            $manufQuery->select(
                                'id',
                                'name',
                                'country_id',
                                'bdm_user_id',
                                'analyst_user_id'
                            )
                                ->with([
                                    'country',
                                    'analyst:id,name,photo',
                                    'bdm:id,name,photo',
                                ]);
                        },
                    ]);
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
            ['link' => route('processes.index'), 'text' => __('VPS')],
        ];

        if ($this->trashed()) {
            $breadcrumbs[] = ['link' => route('processes.trash'), 'text' => __('Trash')];
        }

        $breadcrumbs[] = ['link' => route('processes.edit', $this->id), 'text' => $this->title];

        return $breadcrumbs;
    }

    // Implement method declared in HasTitle Interface
    public function getTitleAttribute(): string
    {
        return __('Process') . ' #' . $this->id . ' / ' . $this->searchCountry->code;
    }

    // Implement method declared in CanExportRecordsAsExcel Interface
    public function scopeWithRelationsForExport($query)
    {
        return $query->withBasicRelations()
            ->withBasicRelationCounts()
            ->with(['comments']);
    }

    // Implement method declared in PreparesFetchedRecordsForExport Interface
    public static function prepareFetchedRecordsForExport($records)
    {
        self::addGeneralStatusPeriodsForRecords($records);
    }

    /**
     * Implement method declared in ExportsProductSelection Interface.
     *
     * No eager loads are required for the product selection export.
     */
    public function scopeWithRelationsForProductSelection($query)
    {
        return $query->with([
            'product' => function ($productQuery) {
                $productQuery->withRelationsForProductSelection();
            },
        ])
            ->select(
                'id',
                'product_id',
                'country_id',
            );
    }

    // Implement method declared in CanExportRecordsAsExcel Interface
    public function getExcelColumnValuesForExport(): array
    {
        return [
            $this->id,
            $this->statusHistory->last()->start_date,
            $this->searchCountry->code,
            $this->status->name,
            $this->status->generalStatus->name_for_analysts,
            $this->status->generalStatus->name,
            $this->product->manufacturer->category->name,
            $this->product->manufacturer->name,
            $this->product->manufacturer->country->name,
            $this->product->manufacturer->bdm->name,
            $this->product->manufacturer->analyst->name,
            $this->product->inn->name,
            $this->product->form->name,
            $this->product->dosage,
            $this->product->pack,
            $this->product->atx?->name,
            $this->product->atx?->short_name,
            $this->MAH?->name,
            $this->comments->pluck('plain_text')->implode(' / '),
            $this->lastComment?->created_at,
            $this->manufacturer_first_offered_price,
            $this->manufacturer_followed_offered_price,
            $this->currency?->name,
            $this->manufacturer_offered_price_in_usd,
            $this->agreed_price,
            $this->our_followed_offered_price,
            $this->our_first_offered_price,
            $this->increased_price,
            $this->increased_price_percentage,
            $this->increased_price_date,
            $this->product->shelfLife->name,
            $this->product->moq,
            $this->dossier_status,
            $this->clinical_trial_year,
            $this->clinicalTrialCountries->pluck('name')->implode(' '),
            $this->clinical_trial_ich_country,
            $this->product->zones->pluck('name')->implode(' '),
            $this->down_payment_1,
            $this->down_payment_2,
            $this->down_payment_condition,
            $this->forecast_year_1_update_date,
            $this->forecast_year_1,
            $this->forecast_year_2,
            $this->forecast_year_3,
            $this->responsiblePerson->name,
            $this->responsible_people_update_date,
            $this->days_past,
            $this->trademark_en,
            $this->trademark_ru,
            $this->created_at,
            $this->updated_at,
            $this->product->class->name,
            $this->general_statuses_with_periods[0]->start_date,
            $this->general_statuses_with_periods[1]->start_date,
            $this->general_statuses_with_periods[2]->start_date,
            $this->general_statuses_with_periods[3]->start_date,
            $this->general_statuses_with_periods[4]->start_date,
            $this->general_statuses_with_periods[5]->start_date,
            $this->general_statuses_with_periods[6]->start_date,
            $this->general_statuses_with_periods[7]->start_date,
            $this->general_statuses_with_periods[8]->start_date,
            $this->general_statuses_with_periods[9]->start_date,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Filtering
    |--------------------------------------------------------------------------
    */

    public static function filterQueryForRequest($query, $request, $applyPermissionsFilter = true)
    {
        // Apply base filters using helper
        $query = QueryFilterHelper::applyFilters($query, $request, self::getFilterConfig());

        // Additional filters
        if ($applyPermissionsFilter) {
            self::applyPermissionsFilter($query, $request);
        }

        self::applyManufacturerRegionFilter($query, $request);
        self::applyActiveStatusStartDateRangeFilter($query, $request);
        self::applyContractedOnSpecificMonthFilter($query, $request); // if redirected from KPI/ASP pages
        self::applyRegisteredOnSpecificMonthFilter($query, $request); // if redirected from KPI/ASP pages
        self::applyGeneralStatusHistoryFilter($query, $request); // if redirected from KPI page

        return $query;
    }

    /**
     * Filter the query based on user permissions and responsibilities.
     *
     * This static method applies filtering to the provided query based on the user's permissions,
     * their responsible countries, and whether they are the assigned analyst for a manufacturer.
     * If the user does not have the "view-MAD-VPS-of-all-analysts" permission, restrictions are applied.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query to be filtered.
     * @param \Illuminate\Http\Request $request The current request, used to access the authenticated user.
     * @return void
     */
    public static function applyPermissionsFilter($query, $request)
    {
        // Get the authenticated user
        $user = auth()->user();

        // Retrieve the list of country IDs the user is responsible for
        $responsibleCountryIDs = $user->responsibleCountries->pluck('id');

        // Apply filters only if the user is restricted from viewing all analysts' MAD VPS records
        if (Gate::denies('view-MAD-VPS-of-all-analysts')) {
            $query->where(function ($subquery) use ($user, $responsibleCountryIDs) {
                $subquery
                    // Filter by countries the user is responsible for
                    ->whereIn('country_id', $responsibleCountryIDs)
                    // Or filter by manufacturers where the user is the assigned analyst
                    ->orWhereHas('manufacturer', function ($manufacturersQuery) use ($user) {
                        $manufacturersQuery->where('analyst_user_id', $user->id);
                    });
            });
        }
    }

    /**
     * Apply filters to the query based on the manufacturer's country.
     *
     * This function filters the processes based on the manufacturer’s country,
     * delegating the filtering logic to the Manufacturer model.
     *
     * @param Illuminate\Http\Request $request The HTTP request object containing filter parameters.
     * @param Illuminate\Database\Eloquent\Builder $query The query builder instance to apply filters to.
     * @return Illuminate\Database\Eloquent\Builder The modified query builder instance.
     */
    public static function applyManufacturerRegionFilter($query, $request)
    {
        $query->whereHas('manufacturer', function ($manufacturersQuery) use ($request) {
            return Manufacturer::applyRegionFilter($manufacturersQuery, $request);
        });
    }

    /**
     * Apply date range filtering based on the active status start date.
     */
    public static function applyActiveStatusStartDateRangeFilter($query, $request)
    {
        $filterConfig = [
            'relationDateRangeAmbiguous' => [
                [
                    'name' => 'activeStatusHistory',
                    'attribute' => 'active_status_start_date_range',
                    'ambiguousAttribute' => 'start_date',
                ]
            ],
        ];

        $query = QueryFilterHelper::applyFilters($query, $request, $filterConfig);
    }

    /**
     * Filter only processes which have been contracted,
     * for the requested month and year.
     *
     * IMPORTANT: Only 'Kk' ignore 'SKk' and 'Nkk'.
     */
    public static function applyContractedOnSpecificMonthFilter($query, $request)
    {
        if ($request->filled('contracted_on_specific_month')) {
            return $query->whereHas('statusHistory', function ($historyQuery) use ($request) {
                $historyQuery
                    ->whereYear('start_date', $request->input('contracted_on_year'))
                    ->whereMonth('start_date', $request->input('contracted_on_month'))
                    ->where('status_id', ProcessStatus::CONTACTED_RECORD_ID);
            });
        }

        if ($request->filled('contracted_on_specific_months')) {
            return $query->whereHas('statusHistory', function ($historyQuery) use ($request) {
                $historyQuery
                    ->whereYear('start_date', $request->input('contracted_on_year'))
                    ->whereIn(DB::raw('MONTH(start_date)'), $request->input('contracted_on_months'))
                    ->where('status_id', ProcessStatus::CONTACTED_RECORD_ID);
            });
        }
    }

    /**
     * Filter only processes which have been registered,
     * for the requested month and year.
     *
     * IMPORTANT: Only 'Пцр' ignore 'SПцр'.
     */
    public static function applyRegisteredOnSpecificMonthFilter($query, $request)
    {
        if ($request->filled('registered_on_specific_month')) {
            return $query->whereHas('statusHistory', function ($historyQuery) use ($request) {
                $historyQuery
                    ->whereYear('start_date', $request->input('registered_on_year'))
                    ->whereMonth('start_date', $request->input('registered_on_month'))
                    ->where('status_id', ProcessStatus::REGISTERED_RECORD_ID);
            });
        }

        if ($request->filled('registered_on_specific_months')) {
            return $query->whereHas('statusHistory', function ($historyQuery) use ($request) {
                $historyQuery
                    ->whereYear('start_date', $request->input('registered_on_year'))
                    ->whereIn(DB::raw('MONTH(start_date)'), $request->input('registered_on_months'))
                    ->where('status_id', ProcessStatus::REGISTERED_RECORD_ID);
            });
        }
    }

    /**
     * Filter only processes which have specific general status history,
     * for the requested month and year.
     */
    public static function applyGeneralStatusHistoryFilter($query, $request)
    {
        if ($request->filled('has_general_status_history')) {
            return $query->whereHas('statusHistory', function ($historyQuery) use ($request) {
                $historyQuery
                    ->whereYear('start_date', $request->input('has_general_status_for_year'))
                    ->whereMonth('start_date', $request->input('has_general_status_for_month'))
                    ->whereHas('status.generalStatus', function ($statusQuery) use ($request) {
                        $statusQuery->where('id', $request->input('has_general_status_id'));
                    });
            });
        }
    }

    public static function getFilterConfig(): array
    {
        return [
            'whereEqual' => ['contracted_in_asp', 'registered_in_asp', 'responsible_person_id'],
            'whereIn' => ['id', 'country_id', 'status_id', 'marketing_authorization_holder_id'],
            'like' => ['trademark_en', 'trademark_ru'],
            'dateRange' => ['created_at', 'updated_at'],

            'relationEqual' => [
                [
                    'name' => 'product',
                    'attribute' => 'dosage',
                ],

                [
                    'name' => 'product',
                    'attribute' => 'pack',
                ],

                [
                    'name' => 'manufacturer',
                    'attribute' => 'analyst_user_id',
                ],

                [
                    'name' => 'manufacturer',
                    'attribute' => 'bdm_user_id',
                ],

                [
                    'name' => 'manufacturer',
                    'attribute' => 'category_id',
                ],
            ],

            'relationIn' => [
                [
                    'name' => 'status.generalStatus',
                    'attribute' => 'name_for_analysts',
                ],

                [
                    'name' => 'status',
                    'attribute' => 'general_status_id',
                ],

                [
                    'name' => 'product',
                    'attribute' => 'inn_id',
                ],

                [
                    'name' => 'product',
                    'attribute' => 'form_id',
                ],

                [
                    'name' => 'product',
                    'attribute' => 'class_id',
                ],

                [
                    'name' => 'product',
                    'attribute' => 'brand',
                ],
            ],

            'relationInAmbiguous' => [
                [
                    'name' => 'manufacturer',
                    'attribute' => 'manufacturer_id',
                    'ambiguousAttribute' => 'manufacturers.id',
                ],

                [
                    'name' => 'manufacturer',
                    'attribute' => 'manufacturer_country_id',
                    'ambiguousAttribute' => 'manufacturers.country_id',
                ],
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Create, Update and Duplicate
    |--------------------------------------------------------------------------
    */

    /**
     * Create multiple instances of the model from the request data.
     *
     * This method processes an array of country IDs from the request,
     * merges specific forecast year data for each country, and creates
     * model instances with the combined data. It also attaches related
     * clinical trial countries and responsible people, and stores comments.
     *
     * @param App\Http\Requests\ProcessStoreRequest $request The request containing the input data.
     * @return void
     */
    public static function createMultipleRecordsFromRequest($request)
    {
        $countryIDs = $request->input('country_ids');

        foreach ($countryIDs as $countryID) {
            $country = Country::find($countryID);

            // Merge additional forecast data for the specific country into the request array
            $mergedData = $request->merge([
                'country_id' => $countryID,
                'forecast_year_1' => $request->input('forecast_year_1_of_' . $country->code),
                'forecast_year_2' => $request->input('forecast_year_2_of_' . $country->code),
                'forecast_year_3' => $request->input('forecast_year_3_of_' . $country->code),
            ])->all();

            $record = self::create($mergedData);

            // BelongsToMany relations
            $record->clinicalTrialCountries()->attach($request->input('clinicalTrialCountries'));

            // HasMany relations
            $record->storeCommentFromRequest($request);
        }
    }

    public function updateFromRequest($request)
    {
        $this->update($request->all());

        // BelongsToMany relations
        $this->clinicalTrialCountries()->sync($request->input('clinicalTrialCountries'));

        // HasMany relations
        $this->storeCommentFromRequest($request);
    }

    public static function duplicateFromRequest($request)
    {
        $record = self::create($request->all());

        // BelongsToMany relations
        $record->clinicalTrialCountries()->attach($request->input('clinicalTrialCountries'));

        // HasMany relations
        $record->storeCommentFromRequest($request);
    }

    /*
    |--------------------------------------------------------------------------
    | Misc date validations
    |--------------------------------------------------------------------------
    */

    /**
     * Handle validation and automatic updates for the 'forecast_year_1_update_date' attribute.
     *
     * This method ensures that the 'forecast_year_1_update_date' is set appropriately
     * whenever the 'forecast_year_1' attribute is modified during the saving event.
     */
    private function handleForecastUpdateDate()
    {
        // forecast_year_1 is available from stage 2
        if ($this->isDirty('forecast_year_1') && $this->forecast_year_1) {
            $this->forecast_year_1_update_date = now();
        }
    }

    /**
     * Handle validation and automatic updates for the 'increased_price_date' attribute.
     *
     * This method ensures that the 'increased_price_date' is set appropriately
     * whenever the 'increased_price' attribute is modified during the saving event.
     */
    private function handleIncreasedPriceDate()
    {
        // The 'increased_price' attribute is relevant starting from stage 4
        // If 'increased_price' is not set, clear the 'increased_price_date' attribute
        if (!$this->increased_price) {
            $this->increased_price_date = null;
        }
        // If 'increased_price' is set and has been modified, update 'increased_price_date'
        elseif ($this->isDirty('increased_price')) {
            $this->increased_price_date = now();
        }
    }

    /**
     * Handle updates for the 'responsible_person_update_date' attribute.
     *
     * This method updates the 'responsible_person_update_date'
     * attribute whenever the 'responsible_person_id' attribute is modified during
     * the updating event.
     */
    private function handleResponsiblePersonUpdateDate()
    {
        // Set the timestamp to the current date when the model is created
        if ($this->isDirty('responsible_person_id')) {
            $this->responsible_person_update_date = now();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Status helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Handle status updates for the process model.
     *
     * This method ensures that any changes to the 'status_id' attribute
     * are properly recorded in the process status history. It closes the
     * current status history entry (if applicable) and creates a new one.
     *
     * Used during updating event of the model.
     */
    private function updateStatusHistory()
    {
        // Check if the 'status_id' attribute has been modified
        if ($this->isDirty('status_id')) {
            // Close the current status history entry
            $this->activeStatusHistory->close();

            // Create a new status history entry
            $this->addStatusHistoryForCurrentStatus();
        }
    }

    /**
     * Add a new status history record for the current process status.
     *
     * This method creates a new entry in the status history table, storing the
     * current 'status_id'. If no specific start date is provided, it defaults
     * to the current timestamp.
     *
     * This function is typically used during the creation or update of the model,
     * but it can be called elsewhere when needed.
     *
     * @param string|null $startDate Optional start date for the status history.
     */
    private function addStatusHistoryForCurrentStatus($startDate = null)
    {
        // If no start date is provided, use the current timestamp.
        $startDate = $startDate ?? now();

        // Create a new status history entry with the current status.
        $this->statusHistory()->create([
            'status_id' => $this->status_id,
            'start_date' => $startDate,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Misc
    |--------------------------------------------------------------------------
    */

    /**
     * Synchronize related product attributes of the process,
     *
     * Used during the saving event of the model.
     */
    private function syncRelatedProductUpdates()
    {
        $product = $this->product;

        // Global attributes
        $product->fill(request()->only([
            'form_id',
            'dosage',
            'pack',
            'shelf_life_id',
            'class_id',
        ]));

        // MOQ is available from stage 2
        if (request()->has('moq')) {
            $product->moq = request()->input('moq');
        }

        // Save only if any field is updated
        if ($product->isDirty()) {
            $product->save();
        }
    }

    /**
     * Notify users if status has been updated to contact stage.
     *
     * Used during the updating event of the model.
     */
    private function notifyUsersOnContractStage()
    {
        if ($this->isDirty('status_id')) {
            $status = ProcessStatus::find($this->status_id);

            if ($status->generalStatus->stage == 5) {
                $notification = new ProcessStageUpdatedToContract($this, $status->name);
                User::notifyProcessOnContractStageToAll($notification);
            }
        }
    }

    /**
     * CHeck whether process can be added to ASP (СПГ) as contracted
     */
    public function isReadyForASPContract()
    {
        return $this->status->generalStatus->stage >= 5;
    }

    /**
     * CHeck whether process can be added to ASP (СПГ) as registered
     */
    public function isReadyForASPRegistration()
    {
        return $this->status->generalStatus->stage >= 7;
    }

    /**
     * Check wether current status of process can be edited by authenticated user or not.
     *
     * Used in processes.edit & processes.duplicate pages.
     */
    public function ensureAuthUserHasAccessToProcess($request)
    {
        $userID = $request->user()->id;

        if ($this->product->manufacturer->analyst_user_id != $userID) {
            Gate::authorize('edit-MAD-VPS-of-all-analysts');
        }
    }

    /**
     * Check wether current status of process can be edited by authenticated user or not.
     *
     * Used in processes.edit page.
     */
    public function currentStatusCanBeEditedForAuthtUser()
    {
        return Gate::allows('upgrade-MAD-VPS-status-after-contract-stage')
            || !$this->status->generalStatus->requires_permission;
    }

    /**
     * Used on processes.index page for ordering.
     */
    public static function addJoinsForOrdering($query, $request)
    {
        if ($request->input('order_by') == 'product_manufacturer_name') {
            $query->withProductsManufacturerNameAttribute();
        }

        if ($request->input('order_by') == 'product_form_name') {
            $query->withProductsFormNameAttribute();
        }

        if ($request->input('order_by') == 'product_dosage') {
            $query->withProductsDosageAttribute();
        }

        return $query;
    }

    /**
     * Add general statuses with periods for a collection of records.
     *
     * This method processes a collection of records and adds general status periods
     * based on the status history of each record. It clones the general statuses to
     * avoid modifying the original collection, calculates the start and end dates,
     * duration days, and duration days ratio for each general status.
     *
     * @param \Illuminate\Database\Eloquent\Collection $records The collection of records to process.
     *
     * @return void
     */
    public static function addGeneralStatusPeriodsForRecords($records)
    {
        // Get all general statuses
        $generalStatuses = ProcessGeneralStatus::all();

        foreach ($records as $record) {
            // Clone general statuses to avoid modifying the original collection
            $clonedGeneralStatuses = $generalStatuses->map(function ($item) {
                return clone $item;
            });

            foreach ($clonedGeneralStatuses as $generalStatus) {
                // Filter status histories related to the current general status
                $histories = $record->statusHistory->filter(function ($history) use ($generalStatus) {
                    return $history->status->general_status_id === $generalStatus->id;
                });

                // Skip if no histories found
                if ($histories->isEmpty()) continue;

                // Sort histories by ID to find the first and last history
                $firstHistory = $histories->sortBy('id')->first();
                $lastHistory = $histories->sortByDesc('id')->first();

                // Set the start_date of the general status
                $generalStatus->start_date = $firstHistory->start_date;

                // Set the end_date of the general status
                $generalStatus->end_date = $lastHistory->end_date ?: Carbon::now();

                // Calculate duration_days for not closed status history
                // Process current status last history must not be closed logically
                $lastHistory->duration_days = $lastHistory->duration_days ?? round($lastHistory->start_date->diffInDays(now(), true));

                // Then calculate the total duration_days
                $generalStatus->duration_days = $histories->sum('duration_days');
            }

            // Calculate the highest duration_days among all general statuses
            $highestPeriod = $clonedGeneralStatuses->max('duration_days') ?: 1;

            // Calculate duration_days_ratio for each general status
            foreach ($clonedGeneralStatuses as $generalStatus) {
                $generalStatus->duration_days_ratio = $generalStatus->duration_days
                    ? round($generalStatus->duration_days * 100 / $highestPeriod)
                    : 0;
            }

            // Assign the cloned general statuses to the record
            $record->general_statuses_with_periods = $clonedGeneralStatuses;
        }
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
        if (Gate::forUser($user)->denies('view-MAD-VPS')) {
            return null;
        }

        $order = 1;
        $columns = array();

        if (Gate::forUser($user)->allows('edit-MAD-VPS')) {
            array_push(
                $columns,
                ['name' => 'Edit', 'order' => $order++, 'width' => 40, 'visible' => 1],
                ['name' => 'Duplicate', 'order' => $order++, 'width' => 40, 'visible' => 1],
            );
        }

        array_push(
            $columns,
            ['name' => 'ID', 'order' => $order++, 'width' => 62, 'visible' => 1],
            ['name' => 'Status date', 'order' => $order++, 'width' => 100, 'visible' => 1],
        );

        if (Gate::forUser($user)->allows('control-MAD-ASP-processes')) {
            array_push(
                $columns,
                ['name' => '5Кк', 'order' => $order++, 'width' => 40, 'visible' => 1],
                ['name' => '7НПР', 'order' => $order++, 'width' => 50, 'visible' => 1],
            );
        }

        array_push(
            $columns,
            ['name' => 'Status', 'order' => $order++, 'width' => 76, 'visible' => 1],
            ['name' => 'Status An*', 'order' => $order++, 'width' => 80, 'visible' => 1],
            ['name' => 'General status', 'order' => $order++, 'width' => 106, 'visible' => 1],

            ['name' => 'BDM', 'order' => $order++, 'width' => 142, 'visible' => 1],
            ['name' => 'Analyst', 'order' => $order++, 'width' => 142, 'visible' => 1],
            ['name' => 'Search country', 'order' => $order++, 'width' => 130, 'visible' => 1],

            ['name' => 'Manufacturer category', 'order' => $order++, 'width' => 110, 'visible' => 1],
            ['name' => 'Manufacturer country', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'Manufacturer', 'order' => $order++, 'width' => 140, 'visible' => 1],

            ['name' => 'Generic', 'order' => $order++, 'width' => 180, 'visible' => 1],
            ['name' => 'Form', 'order' => $order++, 'width' => 120, 'visible' => 1],
            ['name' => 'Dosage', 'order' => $order++, 'width' => 120, 'visible' => 1],
            ['name' => 'Pack', 'order' => $order++, 'width' => 120, 'visible' => 1],
            ['name' => 'MOQ', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'Shelf life', 'order' => $order++, 'width' => 112, 'visible' => 1],

            ['name' => 'Manufacturer price 1', 'order' => $order++, 'width' => 106, 'visible' => 1],
            ['name' => 'Manufacturer price 2', 'order' => $order++, 'width' => 106, 'visible' => 1],
            ['name' => 'Currency', 'order' => $order++, 'width' => 86, 'visible' => 1],
            ['name' => 'Price in USD', 'order' => $order++, 'width' => 94, 'visible' => 1],
            ['name' => 'Agreed price', 'order' => $order++, 'width' => 104, 'visible' => 1],
            ['name' => 'Our price 2', 'order' => $order++, 'width' => 118, 'visible' => 1],
            ['name' => 'Our price 1', 'order' => $order++, 'width' => 118, 'visible' => 1],
            ['name' => 'Increased price', 'order' => $order++, 'width' => 158, 'visible' => 1],
            ['name' => 'Increased price %', 'order' => $order++, 'width' => 154, 'visible' => 1],
            ['name' => 'Increased price date', 'order' => $order++, 'width' => 146, 'visible' => 1],

            ['name' => 'Product class', 'order' => $order++, 'width' => 80, 'visible' => 1],
            ['name' => 'ATX', 'order' => $order++, 'width' => 190, 'visible' => 1],
            ['name' => 'Our ATX', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'MAH', 'order' => $order++, 'width' => 102, 'visible' => 1],
            ['name' => 'Brand Eng', 'order' => $order++, 'width' => 110, 'visible' => 1],
            ['name' => 'Brand Rus', 'order' => $order++, 'width' => 110, 'visible' => 1],

            ['name' => 'Date of forecast', 'order' => $order++, 'width' => 96, 'visible' => 1],
            ['name' => 'Forecast 1 year', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Forecast 2 year', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Forecast 3 year', 'order' => $order++, 'width' => 130, 'visible' => 1],

            ['name' => 'Dossier status', 'order' => $order++, 'width' => 120, 'visible' => 1],
            ['name' => 'Year Cr/Be', 'order' => $order++, 'width' => 180, 'visible' => 1],
            ['name' => 'Countries Cr/Be', 'order' => $order++, 'width' => 116, 'visible' => 1],
            ['name' => 'Country ich', 'order' => $order++, 'width' => 108, 'visible' => 1],
            ['name' => 'Zones', 'order' => $order++, 'width' => 54, 'visible' => 1],
            ['name' => 'Down payment 1', 'order' => $order++, 'width' => 124, 'visible' => 1],
            ['name' => 'Down payment 2', 'order' => $order++, 'width' => 124, 'visible' => 1],
            ['name' => 'Down payment condition', 'order' => $order++, 'width' => 110, 'visible' => 1],

            ['name' => 'Responsible', 'order' => $order++, 'width' => 120, 'visible' => 1],
            ['name' => 'Responsible update date', 'order' => $order++, 'width' => 250, 'visible' => 1],
            ['name' => 'Days have passed', 'order' => $order++, 'width' => 110, 'visible' => 1],

            ['name' => 'Date of creation', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Update date', 'order' => $order++, 'width' => 150, 'visible' => 1],

            ['name' => 'Comments', 'order' => $order++, 'width' => 132, 'visible' => 1],
            ['name' => 'Last comment', 'order' => $order++, 'width' => 240, 'visible' => 1],
            ['name' => 'Comments date', 'order' => $order++, 'width' => 116, 'visible' => 1],
        );

        if (Gate::forUser($user)->allows('edit-MAD-VPS-status-history')) {
            array_push(
                $columns,
                ['name' => 'History', 'order' => $order++, 'width' => 72, 'visible' => 1]
            );
        }

        array_push(
            $columns,
            ['name' => 'ВП', 'order' => $order++, 'width' => 174, 'visible' => 1],
            ['name' => 'ПО', 'order' => $order++, 'width' => 174, 'visible' => 1],
            ['name' => 'АЦ', 'order' => $order++, 'width' => 174, 'visible' => 1],
            ['name' => 'СЦ', 'order' => $order++, 'width' => 174, 'visible' => 1],
            ['name' => 'Кк', 'order' => $order++, 'width' => 174, 'visible' => 1],
            ['name' => 'КД', 'order' => $order++, 'width' => 174, 'visible' => 1],
            ['name' => 'НПР', 'order' => $order++, 'width' => 174, 'visible' => 1],
            ['name' => 'Р', 'order' => $order++, 'width' => 174, 'visible' => 1],
            ['name' => 'Зя', 'order' => $order++, 'width' => 174, 'visible' => 1],
            ['name' => 'Отмена', 'order' => $order++, 'width' => 174, 'visible' => 1],
        );

        return $columns;
    }

    /**
     * Provides the default MAD DH & DSS table columns along with their properties.
     *
     * These columns are typically used to display data in tables,
     * such as on index and trash pages, and are iterated over in a loop.
     *
     * @return array
     */
    public static function getDefaultMADDHTableColumnsForUser($user)
    {
        if (Gate::forUser($user)->denies('view-MAD-Decision-hub')) {
            return null;
        }

        $order = 1;

        $columns = [
            ['name' => 'Status date', 'order' => $order++, 'width' => 116, 'visible' => 1],
            ['name' => 'Search country', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Status', 'order' => $order++, 'width' => 76, 'visible' => 1],
            ['name' => 'Status An*', 'order' => $order++, 'width' => 80, 'visible' => 1],

            ['name' => 'Manufacturer', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'Manufacturer country', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'BDM', 'order' => $order++, 'width' => 142, 'visible' => 1],
            ['name' => 'Analyst', 'order' => $order++, 'width' => 142, 'visible' => 1],

            ['name' => 'Generic', 'order' => $order++, 'width' => 180, 'visible' => 1],
            ['name' => 'Form', 'order' => $order++, 'width' => 120, 'visible' => 1],
            ['name' => 'Dosage', 'order' => $order++, 'width' => 120, 'visible' => 1],
            ['name' => 'Pack', 'order' => $order++, 'width' => 120, 'visible' => 1],
            ['name' => 'MOQ', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'Shelf life', 'order' => $order++, 'width' => 112, 'visible' => 1],
            ['name' => 'Last comment', 'order' => $order++, 'width' => 240, 'visible' => 1],

            ['name' => 'Manufacturer price 1', 'order' => $order++, 'width' => 106, 'visible' => 1],
            ['name' => 'Manufacturer price 2', 'order' => $order++, 'width' => 106, 'visible' => 1],
            ['name' => 'Currency', 'order' => $order++, 'width' => 86, 'visible' => 1],
            ['name' => 'Price in USD', 'order' => $order++, 'width' => 94, 'visible' => 1],
            ['name' => 'Agreed price', 'order' => $order++, 'width' => 104, 'visible' => 1],
            ['name' => 'Our price 2', 'order' => $order++, 'width' => 118, 'visible' => 1],
            ['name' => 'Our price 1', 'order' => $order++, 'width' => 118, 'visible' => 1],

            ['name' => 'MAH', 'order' => $order++, 'width' => 102, 'visible' => 1],
            ['name' => 'Brand Eng', 'order' => $order++, 'width' => 110, 'visible' => 1],
            ['name' => 'Brand Rus', 'order' => $order++, 'width' => 110, 'visible' => 1],

            ['name' => 'Forecast 1 year', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Forecast 2 year', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Forecast 3 year', 'order' => $order++, 'width' => 146, 'visible' => 1],
        ];

        return $columns;
    }
}
