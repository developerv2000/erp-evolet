<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = ['USD', 'EUR', 'RUB', 'INR'];
        $usd_ratio = [1, 1.076, 0.011, 0.012];

        for ($i = 0; $i < count($name); $i++) {
            $record = new Currency();
            $record->name = $name[$i];
            $record->usd_ratio = $usd_ratio[$i];
            $record->save();
        }
    }
}
