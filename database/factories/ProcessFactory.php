<?php

namespace Database\Factories;

use App\Models\Country;
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
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($record) {
            $record->responsiblePeople()->attach(rand(1, 10));
            $record->responsiblePeople()->attach(rand(11, 20));
        });
    }
}
