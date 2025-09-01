<?php

namespace Database\Seeders;

use App\Models\ShipmentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShipmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = [
            ShipmentType::AUTO_TYPE_NAME,
            ShipmentType::AIR_TYPE_NAME,
            ShipmentType::SEA_TYPE_NAME,
        ];

        for ($i = 0; $i < count($name); $i++) {
            $item = new ShipmentType();
            $item->name = $name[$i];
            $item->save();
        }
    }
}
