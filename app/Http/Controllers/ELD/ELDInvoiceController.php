<?php

namespace App\Http\Controllers\ELD;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\OrderProduct;
use Illuminate\Http\Request;

class ELDInvoiceController extends Controller
{
    public function index(Request $request)
    {
        // Preapare request for valid model querying
        Invoice::addDefaultCMDQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = Invoice::onlyProductionType()->withBasicRelations()->withBasicRelationCounts();
        $filteredQuery = Invoice::filterQueryForRequest($query, $request);
        $records = Invoice::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(Invoice::SETTINGS_CMD_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('CMD.invoices.index', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }

    public function create(Request $request)
    {
        $orderProduct = OrderProduct::findOrFail($request->input('order_product_id'));

        if (!$orderProduct->canAddDeliveryToWarehouseInvoice()) {
            abort(404);
        }

        return view('ELD.invoices.create', compact('orderProduct'));
    }

    public function store(Request $request)
    {
        $orderProduct = OrderProduct::findOrFail($request->input('order_product_id'));

        if (!$orderProduct->canAddDeliveryToWarehouseInvoice()) {
            abort(404);
        }

        Invoice::createByELDFromRequest($request);

        return to_route('eld.invoices.index', ['order_product_id' => $request->input('order_product_id')]);
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
                $availableOrderProducts = $record->orderProducts->concat($record->order->products->filter(fn(OrderProduct $product) => $product->canAttachProductionInvoiceOfFinalPaymentType()));
                break;
            case InvoicePaymentType::FULL_PAYMENT_ID:
                // Display toggleable orderProducts list for invoice of FULL_PAYMENT type.
                // Concat attached invoice products and order products which can also be attached.
                $availableOrderProducts = $record->orderProducts->concat($record->order->products->filter(fn(OrderProduct $product) => $product->canAttachProductionInvoiceOfFullPaymentType()));
                break;
        }

        return view('CMD.invoices.edit', compact('record', 'availableOrderProducts'));
    }

    public function update(Request $request, Invoice $record)
    {
        $record->updateByCMDFromRequest($request);

        return redirect($request->input('previous_url'));
    }
}
