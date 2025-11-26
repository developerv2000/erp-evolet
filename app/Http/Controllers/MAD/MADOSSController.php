<?php

namespace App\Http\Controllers\MAD;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Product;
use Illuminate\Http\Request;

class MADOSSController extends Controller
{
    public function index(Request $request)
    {
        $records = Product::with([
            'processes',
            'processes.status',
            'processes.MAH',
        ])
            ->latest()
            ->paginate(50);

        $countries = Country::has('processes')
            ->withCount('processes')
            ->orderBy('processes_count', 'desc')
            ->get();

        return view('MAD.oss.index', compact('records', 'countries'));
    }
}
