<?php

namespace App\Http\Controllers\PRD;

use App\Models\OrderProduct;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\Helpers\UrlHelper;
use Illuminate\Http\Request;

class PRDOrderProductController extends Controller
{
    public function index(Request $request)
    {
        // Preapare request for valid model querying
        OrderProduct::addDefaultPRDQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = OrderProduct::onlyWithInvoicesSentForPayment()->withBasicRelations()->withBasicRelationCounts();
        $filteredQuery = OrderProduct::filterQueryForRequest($query, $request);
        $joinedQuery = OrderProduct::addJoinsForOrdering($filteredQuery, $request); // add joins if joined ordering requested
        $records = OrderProduct::finalizeQueryForRequest($joinedQuery, $request, 'paginate');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(OrderProduct::SETTINGS_PRD_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('PRD.order-products.index', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }
}
