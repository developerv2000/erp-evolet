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

        $countries = Country::has('processes')->get();

        $customOrderedCountries = collect([
            ['code' => 'TJ', 'order' => 1],
            ['code' => 'KG', 'order' => 2],
            ['code' => 'KZ', 'order' => 3],
            ['code' => 'UZ', 'order' => 5],
            ['code' => 'TM', 'order' => 6],
            ['code' => 'GE', 'order' => 7],
            ['code' => 'RU', 'order' => 8],
            ['code' => 'MN', 'order' => 9],
            ['code' => 'AM', 'order' => 10],
            ['code' => 'AZ', 'order' => 11],
            ['code' => 'MD', 'order' => 12],
            ['code' => 'UA', 'order' => 13],
            ['code' => 'AL', 'order' => 14],
            ['code' => 'SRB', 'order' => 15],
            ['code' => 'IQ', 'order' => 16],
            ['code' => 'MY', 'order' => 17],
            ['code' => 'KH', 'order' => 18],
            ['code' => 'KE', 'order' => 19],
            ['code' => 'DO', 'order' => 20],
        ]);

        $countries = $countries->sortBy(function ($country) use ($customOrderedCountries) {
            $rule = $customOrderedCountries->firstWhere('code', $country->code);

            return $rule['order'] ?? 9999;
        })->values();

        return view('MAD.oss.index', compact('records', 'countries'));
    }
}
