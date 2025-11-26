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
            'atx',
            'class',
            'inn',
            'form',
            'shelfLife',
            'manufacturer',
            'processes' => function ($processesQuery) {
                $processesQuery->with([
                    'status',
                    'MAH',
                ]);
            },
        ])
            ->latest()
            ->paginate(50);

        $countries = Country::has('processes')
            ->orderBy('database_processes_count', 'desc')
            ->get();

        return view('MAD.oss.index', compact('records', 'countries'));
    }
}
