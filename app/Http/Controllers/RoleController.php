<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Support\Helpers\UrlHelper;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        // Preapare request for valid model querying
        Role::addDefaultQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = Role::withBasicRelations()->withBasicRelationCounts();
        $filteredQuery = Role::filterQueryForRequest($query, $request);
        $records = Role::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        return view('roles.index', compact('request', 'records'));
    }
}
