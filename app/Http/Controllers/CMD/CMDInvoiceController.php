<?php

namespace App\Http\Controllers\CMD;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoicePaymentType;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\User;
use App\Support\Helpers\UrlHelper;
use App\Support\Traits\Controller\DestroysModelRecords;
use Illuminate\Http\Request;

class CMDInvoiceController extends Controller
{
    use DestroysModelRecords;

    // used in multiple destroy trait
    public static $model = Invoice::class;

    public function index(Request $request)
    {
        // Preapare request for valid model querying
        Invoice::addDefaultCMDQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = Invoice::withBasicRelations()->withBasicRelationCounts();
        $filteredQuery = Invoice::filterQueryForRequest($query, $request);
        $records = Invoice::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(Invoice::SETTINGS_CMD_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('CMD.invoices.index', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }

    public function create(Request $request)
    {
        $order = Order::findOrFail($request->input('order_id'));

        if (!$order->canAttachNewInvoice()) {
            abort(404);
        }

        // Get available orderProducts based on payment type
        $paymentType = InvoicePaymentType::findOrFail($request->input('payment_type_id'));

        switch ($paymentType->name) {
            case InvoicePaymentType::PREPAYMENT_NAME:
                // Display all orderProducts as 'readonly' for invoice of PREPAYMENT type
                $availabeOrderProducts = $order->products;
                break;
            case InvoicePaymentType::FINAL_PAYMENT_NAME:
                // Display toggleable orderProducts list for invoice of FINAL_PAYMENT type
                $availabeOrderProducts = $order->products->filter(fn($product) => $product->canAttachInvoiceOfFinalPaymentType());
                break;
            case InvoicePaymentType::FULL_PAYMENT_NAME:
                // Display toggleable orderProducts list for invoice of FULL_PAYMENT type
                $availabeOrderProducts = $order->products->filter(fn($product) => $product->canAttachInvoiceOfFullPaymentType());
                break;
        }

        return view('CMD.invoices.create', compact('order', 'availabeOrderProducts', 'paymentType'));
    }

    public function store(Request $request)
    {
        $order = Order::findOrFail($request->input('order_id'));

        if (!$order->canAttachNewInvoice()) {
            abort(404);
        }

        Invoice::createByCMDFromRequest($request);

        return to_route('cmd.invoices.index', ['order_id[]' => $request->input('order_id')]);
    }

    public function edit(Request $request, Invoice $record)
    {
        // Get available orderProducts for toggling based on payment type
        switch ($record->payment_type_id) {
            case InvoicePaymentType::PREPAYMENT_ID:
                // Display all orderProducts as 'readonly' for invoice of PREPAYMENT type
                $availableOrderProducts = $record->orderProducts;
                break;
            case InvoicePaymentType::FINAL_PAYMENT_ID:
                // Display toggleable orderProducts list for invoice of FINAL_PAYMENT type.
                // Concat attached invoice products and order products which can also be attached.
                $availableOrderProducts = $record->orderProducts->concat($record->order->products->filter(fn(OrderProduct $product) => $product->canAttachInvoiceOfFinalPaymentType()));
                break;
            case InvoicePaymentType::FULL_PAYMENT_NAME:
                // Display toggleable orderProducts list for invoice of FULL_PAYMENT type.
                // Concat attached invoice products and order products which can also be attached.
                $availableOrderProducts = $record->orderProducts->concat($record->order->products->filter(fn(OrderProduct $product) => $product->canAttachInvoiceOfFullPaymentType()));
                break;
        }

        return view('CMD.invoices.edit', compact('record', 'availableOrderProducts'));
    }

    public function update(Request $request, Invoice $record)
    {
        $record->updateByCMDFromRequest($request);

        return redirect($request->input('previous_url'));
    }

    /**
     * Ajax request
     */
    public function toggleIsSentForPaymentAttribute(Request $request)
    {
        $record = Invoice::findOrFail($request->input('record_id'));
        $record->toggleIsSentForPaymentAttribute($request);

        return response()->json([
            'isSentForPayment' => $record->is_sent_for_payment,
            'sentForPaymentDate' => $record->sent_for_payment_date?->isoFormat('DD MMM Y'),
        ]);
    }
}
