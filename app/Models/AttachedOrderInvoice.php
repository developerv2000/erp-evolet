<?php

namespace App\Models;

use App\Support\Abstracts\BaseModel;
use App\Support\Contracts\Model\HasTitle;
use App\Support\Helpers\QueryFilterHelper;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Gate;

class AttachedOrderInvoice extends BaseModel implements HasTitle
{
    use SoftDeletes; // No manual trashing/restoring. Trashed when order parent is trashed.

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    const SETTINGS_CMD_TABLE_COLUMNS_KEY = 'CMD_attached_order_invoices_table_columns';

    const DEFAULT_ORDER_BY = 'receive_date';
    const DEFAULT_ORDER_TYPE = 'desc';
    const DEFAULT_PAGINATION_LIMIT = 50;

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    protected $guarded = ['id'];

    protected $casts = [
        'receive_date' => 'date',
        'sent_for_payment_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function order()
    {
        return $this->belongsTo(Order::class)->withTrashed();
    }

    public function paymentType()
    {
        return $this->belongsTo(InvoicePaymentType::class, 'payment_type_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Additional attributes
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeWithBasicRelations($query)
    {
        return $query->with([
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Contracts
    |--------------------------------------------------------------------------
    */

    /**
     * Implement method defined in BaseModel abstract class.
     */
    public function generateBreadcrumbs($department = null): array
    {
        return [];
    }

    // Implement method declared in HasTitle Interface
    public function getTitleAttribute(): string
    {
        return ($this->order->name ? $this->order->name . ' ' : '') . '#' . $this->id;
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
            'whereEqual' => ['order_id'],
            'whereIn' => ['id'],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Create & Update
    |--------------------------------------------------------------------------
    */

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

    public static function getDefaultCMDTableColumnsForUser($user)
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
            ['name' => 'Status', 'order' => $order++, 'width' => 114, 'visible' => 1],
            ['name' => 'Order', 'order' => $order++, 'width' => 100, 'visible' => 1],
            ['name' => 'Receive date', 'order' => $order++, 'width' => 138, 'visible' => 1],
            ['name' => 'Manufacturer', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'Country', 'order' => $order++, 'width' => 64, 'visible' => 1],
            ['name' => 'Brand Eng', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'Brand Rus', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'MAH', 'order' => $order++, 'width' => 102, 'visible' => 1],
            ['name' => 'Quantity', 'order' => $order++, 'width' => 112, 'visible' => 1],
            ['name' => 'Comments', 'order' => $order++, 'width' => 132, 'visible' => 1],
            ['name' => 'Last comment', 'order' => $order++, 'width' => 240, 'visible' => 1],
            ['name' => 'Sent to BDM', 'order' => $order++, 'width' => 160, 'visible' => 1],

            ['name' => 'PO date', 'order' => $order++, 'width' => 116, 'visible' => 1],
            ['name' => 'PO №', 'order' => $order++, 'width' => 128, 'visible' => 1],
            ['name' => 'TM Eng', 'order' => $order++, 'width' => 110, 'visible' => 1],
            ['name' => 'TM Rus', 'order' => $order++, 'width' => 110, 'visible' => 1],
            ['name' => 'Generic', 'order' => $order++, 'width' => 180, 'visible' => 1],
            ['name' => 'Form', 'order' => $order++, 'width' => 120, 'visible' => 1],
            ['name' => 'Dosage', 'order' => $order++, 'width' => 120, 'visible' => 1],
            ['name' => 'Pack', 'order' => $order++, 'width' => 100, 'visible' => 1],
            ['name' => 'Price', 'order' => $order++, 'width' => 70, 'visible' => 1],
            ['name' => 'Total price', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Currency', 'order' => $order++, 'width' => 84, 'visible' => 1],
            ['name' => 'Sent to confirmation', 'order' => $order++, 'width' => 244, 'visible' => 1],
            ['name' => 'Confirmation date', 'order' => $order++, 'width' => 172, 'visible' => 1],

            ['name' => 'Sent to manufacturer', 'order' => $order++, 'width' => 164, 'visible' => 1],
            ['name' => 'Layout status', 'order' => $order++, 'width' => 126, 'visible' => 1],
            ['name' => 'Layout sent date', 'order' => $order++, 'width' => 178, 'visible' => 1],
            ['name' => 'Print proof receive date', 'order' => $order++, 'width' => 228, 'visible' => 1],
            ['name' => 'Box article', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'Layout approved date', 'order' => $order++, 'width' => 188, 'visible' => 1],

            ['name' => 'Date of creation', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Update date', 'order' => $order++, 'width' => 164, 'visible' => 1],
        );

        return $columns;
    }
}
