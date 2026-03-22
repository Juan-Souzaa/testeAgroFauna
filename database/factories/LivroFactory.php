<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Livro;
use Illuminate\Database\Eloquent\Factories\Factory;

class LivroFactory extends Factory
{
    protected $model = Livro::class;

    public function definition(): array
    {
        return [
            'titulo' => fake()->sentence(3),
            'autor' => fake()->name(),
            'isbn' => fake()->unique()->numerify('978###########'),
            'preco' => fake()->randomFloat(2, 19.9, 299.99),
            'categoria_id' => Categoria::factory(),
            'publicado_em' => fake()->dateTimeBetween('-10 years', 'now'),
        ];
    }
}
