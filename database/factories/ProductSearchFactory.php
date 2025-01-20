<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Country;
use App\Models\Inn;
use App\Models\MarketingAuthorizationHolder;
use App\Models\PortfolioManager;
use App\Models\ProductForm;
use App\Models\ProductSearchPriority;
use App\Models\ProductSearchStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductSearch>
 */
class ProductSearchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status_id' => rand(1, ProductSearchStatus::count()),
            'country_id' => rand(1, Country::count()),
            'priority_id' => rand(1, ProductSearchPriority::count()),
            'source_eu' => fake()->boolean(),
            'source_in' => fake()->boolean(),
            'inn_id' => rand(1, Inn::count()),
            'form_id' => rand(1, ProductForm::count()),
            'marketing_authorization_holder_id' => rand(1, MarketingAuthorizationHolder::count()),
            'dosage' => fake()->numberBetween(10, 100),
            'pack' => fake()->numberBetween(10, 100) . ' ML',
            'additional_search_information' => fake()->sentences(2, true),
            'forecast_year_1' => fake()->numberBetween(10, 5000),
            'forecast_year_2' => fake()->numberBetween(10, 5000),
            'forecast_year_3' => fake()->numberBetween(10, 5000),
            'portfolio_manager_id' => rand(1, PortfolioManager::count()),
            'analyst_user_id' => User::onlyMADAnalysts()->inRandomOrder()->first()->id,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($record) {
            $record->additionalSearchCountries()->attach(rand(1, 10));
            $record->additionalSearchCountries()->attach(rand(11, 20));

            $record->comments()->saveMany([
                new Comment([
                    'body' => '<p>' . fake()->sentences(2, true) . '</p>',
                    'user_id' => User::onlyMADAnalysts()->inRandomOrder()->first()->id,
                    'created_at' => now()
                ]),

                new Comment([
                    'body' => '<p>' . fake()->sentences(2, true) . '</p>',
                    'user_id' => User::onlyMADAnalysts()->inRandomOrder()->first()->id,
                    'created_at' => now()
                ]),
            ]);
        });
    }
}
