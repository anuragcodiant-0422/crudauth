<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(3),
            'amount' => $this->faker->randomFloat(2, 10, 500),
            'spent_at' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'description' => $this->faker->sentence(),
        ];
    }
}
