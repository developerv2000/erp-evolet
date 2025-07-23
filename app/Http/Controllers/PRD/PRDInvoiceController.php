<?php

namespace App\Http\Controllers\PRD;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\User;
use App\Support\Helpers\UrlHelper;
use Illuminate\Http\Request;

class PRDInvoiceController extends Controller
{
    public function index(Request $request)
    {
        // Preapare request for valid model querying
        Invoice::addDefaultPRDQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = Invoice::onlySentForPayment()->withBasicRelations();
        $filteredQuery = Invoice::filterQueryForRequest($query, $request);
        $records = Invoice::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(Invoice::SETTINGS_PRD_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('PRD.invoices.index', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }

    public function edit(Request $request, Invoice $record)
    {
        return view('PRD.invoices.edit', compact('record'));
    }

    public function update(Request $request, Invoice $record)
    {
        $record->updateByPRDFromRequest($request);

        return redirect($request->input('previous_url'));
    }

    /**
     * Ajax request
     */
    public function toggleIsAcceptedByFinancierAttribute(Request $request)
    {
        $record = Invoice::findOrFail($request->input('record_id'));
        $record->toggleIsAcceptedByFinancierAttribute($request);

        return response()->json([
            'isAcceptedByFinancier' => $record->is_accepted_by_financier,
            'acceptedByFinancierDate' => $record->accepted_by_financier_date?->isoFormat('DD MMM Y'),
        ]);
    }

    /**
     * Ajax request
     */
    public function togglePaymentIsCompletedAttribute(Request $request)
    {
        $record = Invoice::findOrFail($request->input('record_id'));
        $record->togglePaymentIsCompletedAttribute($request);

        return response()->json([
            'paymentIsCompleted' => $record->payment_is_completed,
            'paymentCompletedDate' => $record->payment_completed_date?->isoFormat('DD MMM Y'),
        ]);
    }
}
