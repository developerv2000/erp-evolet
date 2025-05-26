<?php

namespace App\Http\Controllers\PLPD;

use App\Http\Controllers\Controller;
use App\Models\Process;
use App\Support\Helpers\UrlHelper;
use App\Support\Traits\Model\AddsDefaultQueryParamsToRequest;
use App\Support\Traits\Model\AddsRefererQueryParamsToRequest;
use App\Support\Traits\Model\FinalizesQueryForRequest;
use Illuminate\Http\Request;

class PLPDReadyForOrderProcessController extends Controller
{
    use AddsDefaultQueryParamsToRequest;
    use AddsRefererQueryParamsToRequest;
    use FinalizesQueryForRequest;

    const DEFAULT_ORDER_BY = 'readiness_for_order_date';
    const DEFAULT_ORDER_TYPE = 'desc';
    const DEFAULT_PAGINATION_LIMIT = 50;

    public function index(Request $request)
    {
        // Preapare request for valid model querying
        self::addDefaultQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = Process::onlyReadyForOrder()->withRelationsForOrder()->withRelationCountsForOrder()->withOnlyRequiredSelectsForOrder();
        $filteredQuery = Process::filterQueryForRequest($query, $request);
        $records = self::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        return view('PLPD.ready-for-order-processes.index', compact('request', 'records'));
    }
}
