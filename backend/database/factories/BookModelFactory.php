<?php

namespace Database\Factories;

use Biblioteca\Infrastructure\Persistence\Eloquent\Models\BookModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BookModelFactory extends Factory
{
    protected $model = BookModel::class;

    public function definition(): array
    {
        // Ensure the model has an ID before saving
        if (!isset($this->model->id)) {
            $id = (string) Str::uuid();
        } else {
            $id = $this->model->id;
        }
        
        return [
            'id' => (string) Str::uuid(),
            'titulo' => fake()->sentence(3),
            'autor' => fake()->name(),
            'isbn' => fake()->unique()->isbn13(),
        ];
    }
}
