<?php

namespace App\Http\Controllers\MSD;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderProductUpdateByMSDRequest;
use App\Models\OrderProduct;
use App\Models\User;
use App\Support\Helpers\UrlHelper;
use Illuminate\Http\Request;

class MSDSerializedByManufacturerController extends Controller
{
    public function index(Request $request)
    {
        // Preapare request for valid model querying
        OrderProduct::addDefaultMSDSerializedByUsQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = OrderProduct::onlyProductionIsFinished()
            ->onlySerializedByManufacturer()
            ->whereHas('invoices')
            ->withBasicRelations()
            ->withBasicRelationCounts();

        $filteredQuery = OrderProduct::filterQueryForRequest($query, $request);
        $joinedQuery = OrderProduct::addJoinsForOrdering($filteredQuery, $request); // add joins if joined ordering requested
        $records = OrderProduct::finalizeQueryForRequest($joinedQuery, $request, 'paginate');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(OrderProduct::SETTINGS_MSD_SERIALIZED_BY_MANUFACTURER_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('MSD.order-products.serialized-by-manufacturer.index', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }

    public function edit(Request $request, OrderProduct $record)
    {
        return view('MSD.order-products.serialized-by-manufacturer.edit', compact('record'));
    }

    public function update(OrderProductUpdateByMSDRequest $request, OrderProduct $record)
    {
        $record->updateByMSDFromRequest($request);

        return redirect($request->input('previous_url'));
    }
}
