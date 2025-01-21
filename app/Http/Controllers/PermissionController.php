<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Support\Helpers\UrlHelper;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        // Preapare request for valid model querying
        Permission::addDefaultQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = Permission::withBasicRelations()->withBasicRelationCounts();
        $filteredQuery = Permission::filterQueryForRequest($query, $request);
        $records = Permission::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        return view('permissions.index', compact('request', 'records'));
    }
}
