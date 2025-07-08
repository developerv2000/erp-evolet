<?php

namespace App\Http\Controllers\PLPD;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\User;
use App\Support\Helpers\UrlHelper;
use Illuminate\Http\Request;

class PLPDInvoiceController extends Controller
{
    public function index(Request $request)
    {
        // Preapare request for valid model querying
        Invoice::addDefaultPLPDQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = Invoice::onlySentForPayment()->withBasicRelations();
        $filteredQuery = Invoice::filterQueryForRequest($query, $request);
        $records = Invoice::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(Invoice::SETTINGS_PLPD_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('PLPD.invoices.index', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }
}
