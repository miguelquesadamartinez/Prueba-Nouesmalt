<?php

namespace Database\Factories;

use Biblioteca\Infrastructure\Persistence\Eloquent\Models\LoanModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LoanModelFactory extends Factory
{
    protected $model = LoanModel::class;

    public function definition(): array
    {
        $loanDate = fake()->dateTimeBetween('-30 days', 'now');
        
        return [
            'id' => (string) Str::uuid(),
            'user_id' => (string) Str::uuid(),
            'book_id' => (string) Str::uuid(),
            'loan_date' => $loanDate,
            'return_date' => null,
        ];
    }

    public function returned(): static
    {
        return $this->state(fn (array $attributes) => [
            'return_date' => fake()->dateTimeBetween($attributes['loan_date'], 'now'),
        ]);
    }
}
