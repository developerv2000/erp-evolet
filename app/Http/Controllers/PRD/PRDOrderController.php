<?php

namespace App\Http\Controllers\PRD;

use App\Models\Order;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\Helpers\UrlHelper;
use Illuminate\Http\Request;

class PRDOrderController extends Controller
{
    public function index(Request $request)
    {
        // Preapare request for valid model querying
        Order::addDefaultPRDQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = Order::onlyWithInvoicesSentForPayment()->withBasicRelations()->withBasicRelationCounts();
        $filteredQuery = Order::filterQueryForRequest($query, $request);
        $records = Order::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(Order::SETTINGS_PRD_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('PRD.orders.index', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }
}
