<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Inn;
use App\Models\Manufacturer;
use App\Models\ProductClass;
use App\Models\ProductForm;
use App\Models\ProductShelfLife;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'manufacturer_id' => rand(1, Manufacturer::count()),
            'inn_id' => Inn::inRandomOrder()->first()->id,
            'brand' => fake()->name(),
            'form_id' => rand(1, ProductForm::count()),
            'class_id' => rand(1, ProductClass::count()),
            'dosage' => fake()->numberBetween(10, 100),
            'pack' => fake()->numberBetween(10, 100) . ' ML',
            'moq' => fake()->numberBetween(10, 1000),
            'shelf_life_id' => rand(1, ProductShelfLife::count()),
            'dossier' => fake()->sentence(),
            'bioequivalence' => fake()->name() . ' ' . fake()->numberBetween(1, 5000),
            'down_payment' => fake()->numberBetween(1, 10000) . ' $',
            'validity_period' => fake()->dateTimeBetween('-2 year', 'now'),
            'registered_in_eu' => fake()->boolean(),
            'sold_in_eu' => fake()->boolean(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($generic) {
            $generic->zones()->attach(rand(1, Zone::count()));

            $generic->comments()->saveMany([
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
