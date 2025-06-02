<?php

namespace App\Models;

use App\Support\Abstracts\BaseModel;
use App\Support\Contracts\Model\CanExportRecordsAsExcel;
use App\Support\Contracts\Model\HasTitle;
use App\Support\Helpers\QueryFilterHelper;
use App\Support\Traits\Model\Commentable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class Order extends BaseModel implements HasTitle, CanExportRecordsAsExcel
{
    /** @use HasFactory<\Database\Factories\ManufacturerFactory> */
    use HasFactory;
    use SoftDeletes;
    use Commentable;

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    // PLPD
    const SETTINGS_PLPD_TABLE_COLUMNS_KEY = 'PLPD_orders_table_columns';
    const DEFAULT_PLPD_ORDER_BY = 'id';
    const DEFAULT_PLPD_ORDER_TYPE = 'asc';
    const DEFAULT_PLPD_PAGINATION_LIMIT = 50;

    // CMD
    const SETTINGS_CMD_TABLE_COLUMNS_KEY = 'CMD_orders_table_columns';
    const DEFAULT_CMD_ORDER_BY = 'sent_to_bdm_date';
    const DEFAULT_CMD_ORDER_TYPE = 'desc';
    const DEFAULT_CMD_PAGINATION_LIMIT = 50;

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    protected $guarded = ['id'];

    protected $casts = [
        'receive_date' => 'date',
        'sent_to_bdm_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function process()
    {
        return $this->belongsTo(Process::class)->withTrashed();
    }

    public function product()
    {
        return $this->hasOneThrough(
            Product::class,
            Process::class,
            'id', // Foreign key on the Processes table
            'id', // Foreign key on the Products table
            'process_id', // Local key on the Orders table
            'product_id' // Local key on the Processes table
        )->withTrashedParents()->withTrashed();
    }

    /*
    |--------------------------------------------------------------------------
    | Additional attributes
    |--------------------------------------------------------------------------
    */

    public function getIsSentToBdmAttribute(): bool
    {
        return !is_null($this->sent_to_bdm_date);
    }

    public function getTotalPriceAttribute()
    {
        $total = $this->quantity * $this->price;

        return floor($total * 100) / 100;
    }

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::restoring(function ($record) {
            if ($record->process->trashed()) {
                $record->process->restore();
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
            'lastComment',

            'process' => function ($processQuery) {
                $processQuery->withRelationsForOrder()->withOnlyRequiredSelectsForOrder();
            },
        ]);
    }

    public function scopeWithBasicRelationCounts($query)
    {
        return $query->withCount([
            'comments',
        ]);
    }

    public function scopeOnlySentToBdm($query)
    {
        return $query->whereNotNull('sent_to_bdm_date');
    }

    /*
    |--------------------------------------------------------------------------
    | Contracts
    |--------------------------------------------------------------------------
    */

    /**
     * Implement method defined in BaseModel abstract class
     *
     * No trash page for 'CMD' orders
     */
    public function generateBreadcrumbs($department = null): array
    {
        $lowercasedDepartment = strtolower($department);

        $breadcrumbs = [
            ['link' => route($lowercasedDepartment . '.orders.index'), 'text' => __('Orders')],
        ];

        if ($this->trashed() && $department == 'PLPD') {
            $breadcrumbs[] = ['link' => route($lowercasedDepartment . '.orders.trash'), 'text' => __('Trash')];
        }

        $breadcrumbs[] = ['link' => route($lowercasedDepartment . '.orders.edit', $this->id), 'text' => $this->title];

        return $breadcrumbs;
    }

    // Implement method declared in HasTitle Interface
    public function getTitleAttribute(): string
    {
        return '#' . $this->id;
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
            'whereEqal' => ['process_id', 'quantity'],
            'whereIn' => ['id'],
            'dateRange' => ['receive_date', 'sent_to_bdm_date', 'created_at', 'updated_at'],

            'relationEqual' => [
                [
                    'name' => 'process.product.manufacturer',
                    'attribute' => 'bdm_user_id',
                ],
            ],

            'relationIn' => [
                [
                    'name' => 'process',
                    'attribute' => 'country_id',
                ],

                [
                    'name' => 'process',
                    'attribute' => 'trademark_en',
                ],

                [
                    'name' => 'process',
                    'attribute' => 'trademark_ru',
                ],

                [
                    'name' => 'process.product',
                    'attribute' => 'manufacturer_id',
                ],
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Adding default query params to request
    |--------------------------------------------------------------------------
    */

    public static function addDefaultPLPDQueryParamsToRequest(Request $request)
    {
        self::addDefaultQueryParamsToRequest(
            $request,
            'DEFAULT_PLPD_ORDER_BY',
            'DEFAULT_PLPD_ORDER_TYPE',
            'DEFAULT_PLPD_PAGINATION_LIMIT',
        );
    }

    public static function addDefaultCMDQueryParamsToRequest(Request $request)
    {
        self::addDefaultQueryParamsToRequest(
            $request,
            'DEFAULT_CMD_ORDER_BY',
            'DEFAULT_CMD_ORDER_TYPE',
            'DEFAULT_CMD_PAGINATION_LIMIT',
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Create & Update
    |--------------------------------------------------------------------------
    */

    // PLPD part

    public static function createFromRequest($request)
    {
        $process = self::determineProcessOnCreateOrEditFromRequest($request);

        $record = new Order($request->all());
        $record->process_id = $process->id;
        $record->save();

        // HasMany relations
        $record->storeCommentFromRequest($request);
    }

    public function updateByPLPDFromRequest($request)
    {
        $process = self::determineProcessOnCreateOrEditFromRequest($request);

        $this->fill($request->all());
        $this->process_id = $process->id;
        $this->save();

        // HasMany relations
        $this->storeCommentFromRequest($request);
    }

    /**
     * Detect valid 'process' of order, which is depended on
     * selected processes 'product_id' with 'country_id' and
     * also on selected 'marketing_authorization_holder_id'
     */
    private static function determineProcessOnCreateOrEditFromRequest($request)
    {
        $selectedProcess = Process::findOrFail($request->input('process_id'));
        $productID = $selectedProcess->product_id;
        $countryID = $selectedProcess->country_id;
        $mahID = $request->input('marketing_authorization_holder_id');

        return Process::onlyReadyForOrder()
            ->where('product_id', $productID)
            ->where('country_id', $countryID)
            ->where('marketing_authorization_holder_id', $mahID)
            ->first();
    }

    // CMD part

    public function updateByCMDFromRequest($request)
    {
        $this->update($request->all());

        // HasMany relations
        $this->storeCommentFromRequest($request);
    }

    /*
    |--------------------------------------------------------------------------
    | Misc
    |--------------------------------------------------------------------------
    */

    public function toggleIsSentToBDMAttribute(Request $request)
    {
        $action = $request->input('action');
        $this->sent_to_bdm_date = $action == 'send' ? now() : null;
        $this->save();
    }

    /**
     * Provides the default PLPD table columns along with their properties.
     *
     * These columns are typically used to display data in tables,
     * such as on index and trash pages, and are iterated over in a loop.
     *
     * @return array
     */
    public static function getDefaultPLPDTableColumnsForUser($user)
    {
        if (Gate::forUser($user)->denies('view-PLPD-orders')) {
            return null;
        }

        $order = 1;
        $columns = array();

        if (Gate::forUser($user)->allows('edit-PLPD-orders')) {
            array_push(
                $columns,
                ['name' => 'Edit', 'order' => $order++, 'width' => 40, 'visible' => 1],
            );
        }

        array_push(
            $columns,
            ['name' => 'ID', 'order' => $order++, 'width' => 62, 'visible' => 1],
            ['name' => 'BDM', 'order' => $order++, 'width' => 142, 'visible' => 1],
            ['name' => 'Brand Eng', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'Brand Rus', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'MAH', 'order' => $order++, 'width' => 102, 'visible' => 1],
            ['name' => 'Quantity', 'order' => $order++, 'width' => 112, 'visible' => 1],
            ['name' => 'Receive date', 'order' => $order++, 'width' => 138, 'visible' => 1],
            ['name' => 'Manufacturer', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'Country', 'order' => $order++, 'width' => 64, 'visible' => 1],
            ['name' => 'Sent to BDM', 'order' => $order++, 'width' => 160, 'visible' => 1],
            ['name' => 'Comments', 'order' => $order++, 'width' => 132, 'visible' => 1],
            ['name' => 'Last comment', 'order' => $order++, 'width' => 240, 'visible' => 1],
            ['name' => 'Comments date', 'order' => $order++, 'width' => 116, 'visible' => 1],
            ['name' => 'Date of creation', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Update date', 'order' => $order++, 'width' => 164, 'visible' => 1],
        );

        return $columns;
    }

    /**
     * Provides the default CMD table columns along with their properties.
     *
     * These columns are typically used to display data in tables,
     * such as on index and trash pages, and are iterated over in a loop.
     *
     * @return array
     */
    public static function getDefaultCMDTableColumnsForUser($user)
    {
        if (Gate::forUser($user)->denies('view-CMD-orders')) {
            return null;
        }

        $order = 1;
        $columns = array();

        if (Gate::forUser($user)->allows('edit-CMD-orders')) {
            array_push(
                $columns,
                ['name' => 'Edit', 'order' => $order++, 'width' => 40, 'visible' => 1],
            );
        }

        array_push(
            $columns,
            ['name' => 'ID', 'order' => $order++, 'width' => 62, 'visible' => 1],
            ['name' => 'BDM', 'order' => $order++, 'width' => 142, 'visible' => 1],
            ['name' => 'Brand Eng', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'Brand Rus', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'MAH', 'order' => $order++, 'width' => 102, 'visible' => 1],
            ['name' => 'Quantity', 'order' => $order++, 'width' => 112, 'visible' => 1],
            ['name' => 'Receive date', 'order' => $order++, 'width' => 138, 'visible' => 1],
            ['name' => 'Manufacturer', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'Country', 'order' => $order++, 'width' => 64, 'visible' => 1],
            ['name' => 'Sent to BDM', 'order' => $order++, 'width' => 160, 'visible' => 1],
            ['name' => 'Comments', 'order' => $order++, 'width' => 132, 'visible' => 1],
            ['name' => 'Last comment', 'order' => $order++, 'width' => 240, 'visible' => 1],
            ['name' => 'Comments date', 'order' => $order++, 'width' => 116, 'visible' => 1],
            ['name' => 'Date of creation', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Update date', 'order' => $order++, 'width' => 164, 'visible' => 1],
        );

        return $columns;
    }
}
