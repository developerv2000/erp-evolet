<?php

namespace Database\Seeders;

use App\Models\ProductSearch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSearchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductSearch::factory()->count(20)->create();
    }
}
