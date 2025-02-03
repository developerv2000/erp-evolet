<?php

namespace App\Http\Controllers;

use App\Http\Requests\MadAspStoreRequest;
use App\Http\Requests\MadAspUpdateRequest;
use App\Models\Country;
use App\Models\MadAsp;
use App\Models\MarketingAuthorizationHolder;
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
        $query = MadAsp::withBasicRelations()->withBasicRelationCounts();
        $records = MadAsp::finalizeQueryForRequest($query, $request, 'paginate');

        return view('mad-asp.index', compact('request', 'records'));
    }

    public function create()
    {
        return view('mad-asp.create');
    }

    public function store(MadAspStoreRequest $request)
    {
        MadAsp::createFromRequest($request);

        return to_route('mad-asp.index');
    }

    public function edit(Request $request, MadAsp $record)
    {
        return view('mad-asp.edit', compact('record'));
    }

    public function update(MadAspUpdateRequest $request, MadAsp $record)
    {
        $record->updateFromRequest($request);

        return redirect($request->input('previous_url'));
    }

    /*
    |--------------------------------------------------------------------------
    | Countries
    |--------------------------------------------------------------------------
    */

    public function countriesIndex(MadAsp $record)
    {
        $record->load(['countries', 'MAHs']);
        $record->attachAllCountryMAHs();


        return view('mad-asp.countries.index', compact('record'));
    }

    public function countriesCreate(MadAsp $record)
    {
        return view('mad-asp.countries.create', compact('record'));
    }

    public function countriesStore(Request $request, MadAsp $record)
    {
        $record->attachCountryOnCountryCreate($request);

        return to_route('mad-asp.countries.index', $record->year);
    }

    public function countriesDestroy(Request $request, MadAsp $record)
    {
        $record->detachCountriesByID($request->input('ids', []));

        return redirect()->back();
    }

    /*
    |--------------------------------------------------------------------------
    | MAH
    |--------------------------------------------------------------------------
    */

    public function MAHsIndex(MadAsp $record, Country $country)
    {
        $MAHs = $record->MAHsOfSpecificCountry($country)->get();

        return view('mad-asp.mahs.index', compact('record', 'country', 'MAHs'));
    }

    public function MAHsCreate(MadAsp $record, Country $country)
    {
        return view('mad-asp.mahs.create', compact('record', 'country'));
    }

    public function MAHsStore(Request $request, MadAsp $record, Country $country)
    {
        $record->attachMAHOnMAHCreate($request);

        return to_route('mad-asp.mahs.index', ['record' => $record->year, 'country' => $country->id]);
    }

    public function MAHsEdit(MadAsp $record, Country $country, MarketingAuthorizationHolder $mah)
    {
        $mah = $record->MAHsOfSpecificCountry($country)
            ->where('marketing_authorization_holders.id', $mah->id)->first();

        return view('mad-asp.mahs.edit', compact('record', 'country', 'mah'));
    }

    public function MAHsUpdate(Request $request, MadAsp $record, Country $country, MarketingAuthorizationHolder $mah)
    {
        $record->updateMAHFromRequest($mah, $country, $request);

        return to_route('mad-asp.mahs.index', ['record' => $record->year, 'country' => $country->id]);
    }

    public function MAHsDestroy(Request $request, MadAsp $record, Country $country)
    {
        $record->detachCountryMAHsByID($country, $request->input('ids', []));

        return redirect()->back();
    }
}
