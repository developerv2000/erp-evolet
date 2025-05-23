<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Country;
use App\Models\ManufacturerBlacklist;
use App\Models\ManufacturerPresence;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Lottery;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Manufacturer>
 */
class ManufacturerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'website' => 'https://' . fake()->domainName(),
            'about' => fake()->sentences(3, true),
            'relationship' => fake()->sentences(2, true),
            'active' => fake()->boolean(),
            'important' => fake()->boolean(),
            'bdm_user_id' => User::onlyCMDBDMs()->inRandomOrder()->first()->id,
            'analyst_user_id' => User::onlyMADAnalysts()->inRandomOrder()->first()->id,
            'category_id' => rand(1, 2),
            'country_id' => rand(1, Country::count()),
            'created_at' => fake()->dateTimeBetween('-2 year', 'now'),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function ($record) {
            $record->presences()->saveMany([
                new ManufacturerPresence(['name' => fake()->country()]),
                new ManufacturerPresence(['name' => fake()->country()]),
            ]);

            Lottery::odds(1, 2)
                ->winner(function () use ($record) {
                    $record->blacklists()->attach(rand(1, ManufacturerBlacklist::count()));
                })
                ->choose();

            $record->zones()->attach(rand(1, Zone::count()));
            $record->productClasses()->attach(rand(1, 2));
            $record->productClasses()->attach(rand(3, 4));

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
