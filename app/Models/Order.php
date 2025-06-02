<?php

namespace App\Models;

use App\Support\Abstracts\BaseModel;
use App\Support\Contracts\Model\CanExportRecordsAsExcel;
use App\Support\Contracts\Model\HasTitle;
use App\Support\Helpers\QueryFilterHelper;
use App\Support\Traits\Model\Commentable;
use App\Support\Traits\Model\ExportsRecordsAsExcel;
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
    use ExportsRecordsAsExcel;

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    const SETTINGS_PLPD_TABLE_COLUMNS_KEY = 'PLPD_orders_table_columns';

    const DEFAULT_ORDER_BY = 'id';
    const DEFAULT_ORDER_TYPE = 'asc';
    const DEFAULT_PAGINATION_LIMIT = 50;

    const LIMITED_EXCEL_RECORDS_COUNT_FOR_EXPORT = 20;
    const STORAGE_PATH_OF_EXCEL_TEMPLATE_FILE_FOR_EXPORT = 'app/excel/export-templates/plpd-orders.xlsx';
    const STORAGE_PATH_FOR_EXPORTING_EXCEL_FILES = 'app/excel/exports/plpd-orders';

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

    /*
    |--------------------------------------------------------------------------
    | Contracts
    |--------------------------------------------------------------------------
    */

    // Implement method defined in BaseModel abstract class
    public function generateBreadcrumbs(): array
    {
        $breadcrumbs = [
            ['link' => route('plpd.orders.index'), 'text' => __('Orders')],
        ];

        if ($this->trashed()) {
            $breadcrumbs[] = ['link' => route('plpd.orders.trash'), 'text' => __('Trash')];
        }

        $breadcrumbs[] = ['link' => route('plpd.orders.edit', $this->id), 'text' => $this->title];

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
    | Create & Update
    |--------------------------------------------------------------------------
    */

    public static function createFromRequest($request)
    {
        // Detect valid 'process_id' of order, which is depended on
        // selected processes 'product_id' and 'country_id' and also on selected 'marketing_authorization_holder_id'
        $selectedProcess = Process::findOrFail($request->input('process_id'));
        $productID = $selectedProcess->product_id;
        $countryID = $selectedProcess->country_id;
        $mahID = $request->input('marketing_authorization_holder_id');

        $process = Process::onlyReadyForOrder()
            ->where('product_id', $productID)
            ->where('country_id', $countryID)
            ->where('marketing_authorization_holder_id', $mahID)
            ->first();

        $record = new Order($request->all());
        $record->process_id = $process->id;
        $record->save();

        // HasMany relations
        $record->storeCommentFromRequest($request);
    }

    public function updateFromRequest($request)
    {
        // Detect valid 'process_id' of order, which is depended on
        // selected processes 'product_id' and 'country_id' and also on selected 'marketing_authorization_holder_id'
        $selectedProcess = Process::findOrFail($request->input('process_id'));
        $productID = $selectedProcess->product_id;
        $countryID = $selectedProcess->country_id;
        $mahID = $request->input('marketing_authorization_holder_id');

        $process = Process::onlyReadyForOrder()
            ->where('product_id', $productID)
            ->where('country_id', $countryID)
            ->where('marketing_authorization_holder_id', $mahID)
            ->first();

        $this->fill($request->all());
        $this->process_id = $process->id;
        $this->save();

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
}
