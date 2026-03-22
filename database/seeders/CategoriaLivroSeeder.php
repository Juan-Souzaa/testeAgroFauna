<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Livro;
use Illuminate\Database\Seeder;

class CategoriaLivroSeeder extends Seeder
{
    public function run(): void
    {
        $ficcao = Categoria::query()->firstOrCreate(['nome' => 'Ficção']);
        $tecnico = Categoria::query()->firstOrCreate(['nome' => 'Técnico']);
        $biografia = Categoria::query()->firstOrCreate(['nome' => 'Biografia']);

        Livro::factory()->count(10)->create(['categoria_id' => $ficcao->id]);
        Livro::factory()->count(10)->create(['categoria_id' => $tecnico->id]);
        Livro::factory()->count(8)->create(['categoria_id' => $biografia->id]);
    }
}
