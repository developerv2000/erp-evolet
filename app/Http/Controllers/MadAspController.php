<?php

namespace App\Http\Controllers;

use App\Models\MadAsp;
use App\Support\Helpers\UrlHelper;
use App\Support\Traits\Controller\DestroysModelRecords;
use Illuminate\Http\Request;

class MadAspController extends Controller
{
    use DestroysModelRecords;

    // used in multiple destroy trait
    public static $model = MadAsp::class;

    public function index(Request $request)
    {
        // Preapare request for valid model querying
        MadAsp::addDefaultQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $records = MadAsp::finalizeQueryForRequest(MadAsp::query(), $request, 'paginate');

        return view('mad-asp.index', compact('request', 'records'));
    }
}
