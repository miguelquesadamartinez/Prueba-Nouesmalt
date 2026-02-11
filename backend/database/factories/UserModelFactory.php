<?php

namespace Database\Factories;

use Biblioteca\Infrastructure\Persistence\Eloquent\Models\UserModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserModelFactory extends Factory
{
    protected $model = UserModel::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'nombre' => fake()->firstName(),
            'apellidos' => fake()->lastName() . ' ' . fake()->lastName(),
            'dni' => fake()->unique()->regexify('[0-9]{8}[A-Z]'),
        ];
    }
}
