<?php

namespace App\Http\Controllers\ELD;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\OrderProduct;
use App\Models\User;
use App\Support\Helpers\UrlHelper;
use Illuminate\Http\Request;

class ELDInvoiceController extends Controller
{
    public function index(Request $request)
    {
        // Preapare request for valid model querying
        Invoice::addDefaultELDQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = Invoice::onlyDeliveryToWarehouseType()->withBasicRelations()->withBasicRelationCounts();
        $filteredQuery = Invoice::filterQueryForRequest($query, $request);
        $records = Invoice::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(Invoice::SETTINGS_ELD_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('ELD.invoices.index', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
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
        return view('ELD.invoices.edit', compact('record'));
    }

    public function update(Request $request, Invoice $record)
    {
        $record->updateByELDFromRequest($request);

        return redirect($request->input('previous_url'));
    }
}
