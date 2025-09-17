<?php

namespace Database\Seeders;

use App\Models\PayerCompany;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PayerCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = [
            'Orthos Pharma',
            'Dameliz',
            'Moraine Business',
            'Astra Logistic',
        ];

        for ($i = 0; $i < count($name); $i++) {
            $item = new PayerCompany();
            $item->name = $name[$i];
            $item->save();
        }
    }
}
