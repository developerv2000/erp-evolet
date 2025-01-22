<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserPasswordUpdateRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Support\Helpers\UrlHelper;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Preapare request for valid model querying
        User::addDefaultQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = User::withBasicRelations();
        $filteredQuery = User::filterQueryForRequest($query, $request);
        $records = User::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        return view('users.index', compact('request', 'records'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(UserStoreRequest $request)
    {
        User::createFromRequest($request);

        return to_route('users.index');
    }

    public function edit(User $record)
    {
        return view('users.edit', compact('record'));
    }

    public function update(UserUpdateRequest $request, User $record)
    {
        $record->updateFromRequest($request);

        return redirect()->back();
    }

    public function updatePassword(UserPasswordUpdateRequest $request, User $record)
    {
        $record->updatePassword($request);

        return redirect()->back();
    }
}
