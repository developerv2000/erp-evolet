<?php

namespace App\Http\Controllers\CMD;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\User;
use App\Support\Helpers\UrlHelper;
use App\Support\Traits\Controller\DestroysModelRecords;
use Illuminate\Http\Request;

class CMDInvoiceController extends Controller
{
    use DestroysModelRecords;

    // used in multiple destroy trait
    public static $model = Invoice::class;

    public function index(Request $request, Order $order)
    {
        // Preapare request for valid model querying
        Invoice::addDefaultCMDQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = Invoice::withBasicRelations();
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

        return view('CMD.invoices.create', compact('order'));
    }

    public function store(Request $request)
    {
        Invoice::createByCMDFromRequest($request);

        return to_route('cmd.invoices.index', $request->input('order_id'));
    }

    public function edit(Request $request, Invoice $record)
    {
        return view('CMD.invoices.edit', compact('record'));
    }

    public function update(Request $request, Invoice $record)
    {
        $record->updateByCMDFromRequest($request);

        return redirect($request->input('previous_url'));
    }
}
