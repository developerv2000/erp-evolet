<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Process;
use App\Models\User;
use App\Support\Helpers\UrlHelper;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class DecisionHubController extends Controller
{
    public function index(Request $request)
    {
        // Preapare request for valid model querying
        Process::addDefaultQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records
        $query = Process::withBasicRelations();
        $filteredQuery = Process::filterQueryForRequest($query, $request);
        $records = Process::finalizeQueryForRequest($filteredQuery, $request, 'get');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(Process::SETTINGS_MAD_DH_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        // Return with errors, if too many records requested
        $errors = new MessageBag();

        if ($records->count() > 150) {
            $errors->add('too_many', __('Too many records to display. Please filter required products.'));
        }

        return view(
            'decision-hub.index',
            compact(
                'request',
                'records',
                'allTableColumns',
                'visibleTableColumns'
            )
        )->withErrors($errors);;
    }
}
