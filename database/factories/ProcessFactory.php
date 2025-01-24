<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\ProcessResponsiblePerson;
use App\Models\ProcessStatus;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Process>
 */
class ProcessFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => rand(1, Product::count()),
            'status_id' => rand(1, ProcessStatus::count()),
            'country_id' => rand(1, Country::count()),
            'responsible_person_id' => rand(1, ProcessResponsiblePerson::count()),
        ];
    }
}
