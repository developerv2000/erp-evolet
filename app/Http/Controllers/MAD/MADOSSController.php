<?php

namespace App\Http\Controllers\MAD;

use App\Models\Country;
use App\Models\Process;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MADOSSController extends Controller
{
    public function index(Request $request)
    {
        $records = Product::latest()
            ->orderBy('id', 'desc')
            ->has('processes')
            ->with('processes')
            ->paginate(50);

        $records->each(function ($record) {
            $record->grouped_processes = $record->processes
                ->groupBy('marketing_authorization_holder_id');
        });

        $countries = Country::has('processes')->get();

        return view('MAD.oss.index', compact(
            'records',
            'countries',
        ));
    }
}
