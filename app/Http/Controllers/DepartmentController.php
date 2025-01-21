<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Support\Helpers\UrlHelper;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        // Preapare request for valid model querying
        Department::addDefaultQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = Department::withBasicRelations()->withBasicRelationCounts();
        $records = Department::finalizeQueryForRequest($query, $request, 'paginate');

        return view('departments.index', compact('request', 'records'));
    }
}
