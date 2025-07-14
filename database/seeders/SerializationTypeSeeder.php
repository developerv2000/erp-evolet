<?php

namespace Database\Seeders;

use App\Models\SerializationType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SerializationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = [
            SerializationType::BY_MANUFACTURER_TYPE_NAME,
            SerializationType::BY_US_TYPE_NAME,
            SerializationType::NO_SERIALIZATION_TYPE_NAME,
        ];

        for ($i = 0; $i < count($name); $i++) {
            $item = new SerializationType();
            $item->name = $name[$i];
            $item->save();
        }
    }
}
