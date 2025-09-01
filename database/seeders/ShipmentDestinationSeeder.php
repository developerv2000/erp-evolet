<?php

namespace Database\Seeders;

use App\Models\ShipmentDestination;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShipmentDestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = [
            ShipmentDestination::RIGA_NAME,
            ShipmentDestination::DESTINATION_COUNTRY_NAME,
        ];

        for ($i = 0; $i < count($name); $i++) {
            $item = new ShipmentDestination();
            $item->name = $name[$i];
            $item->save();
        }
    }
}
