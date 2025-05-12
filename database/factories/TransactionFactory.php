<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => fake()->paragraph(),
            'ammount' => fake()->numberBetween(1, 5_000),
            'currency' => fake()->randomElement(['EUR', 'USD']),
            'category' => fake()->randomElement(['transfer', 'income']),
        ];
    }
}
