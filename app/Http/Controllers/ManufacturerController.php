<?php

namespace App\Http\Controllers;

use App\Models\Manufacturer;
use App\Models\User;
use App\Support\Helpers\UrlHelper;
use App\Support\Traits\Controller\DestroysModelRecords;
use App\Support\Traits\Controller\RestoresModelRecords;
use Illuminate\Http\Request;

class ManufacturerController extends Controller
{
    use DestroysModelRecords;
    use RestoresModelRecords;

    // used in multiple destroy/restore traits
    public $model = Manufacturer::class;

    public function index(Request $request)
    {
        // Preapare request for valid model querying
        Manufacturer::addDefaultQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = Manufacturer::withBasicRelations();
        $filteredQuery = Manufacturer::filterQueryForRequest($query, $request);
        $records = Manufacturer::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey('manufacturers_table_columns');
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('manufacturers.index', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }
}
