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

        // Query finalized records
        $query = Process::withBasicRelations();
        $filteredQuery = Process::filterQueryForRequest($query, $request);
        $records = Process::finalizeQueryForRequest($filteredQuery, $request, 'query');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(Process::SETTINGS_MAD_DH_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        // Create new errors bag
        $errors = new MessageBag();

        // Return with errors, if too many records requested
        if ($records->count() > 150) {
            $records = collect();
            $errors->add('too_many', __('Too many records to display. Please filter required products.'));
            // Else get all records
        } else {
            $records = $records->get();
        }

        // List of table columns to be highlighted
        $highlighedBgColumns = [
            'Manufacturer price 1',
            'Manufacturer price 2',
            'Currency',
            'Price in USD',
            'Agreed price',
            'Our price 2',
            'Our price 1',
        ];

        $bolderWeightColumns = [
            'Price in USD',
            'Agreed price',
        ];

        return view('decision-hub.index', compact('request', 'records', 'allTableColumns', 'visibleTableColumns', 'highlighedBgColumns', 'bolderWeightColumns'))
            ->withErrors($errors);
    }
}
