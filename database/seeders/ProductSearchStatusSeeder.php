<?php

namespace Database\Seeders;

use App\Models\ProductSearchStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSearchStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = ['Поиск', 'Нашли от Е', 'Нашли от И', 'Отменено'];

        for ($i = 0; $i < count($name); $i++) {
            $instance = new ProductSearchStatus();
            $instance->name = $name[$i];
            $instance->save();
        }
    }
}
