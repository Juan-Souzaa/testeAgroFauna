<?php

namespace App\Services;

use App\Models\Livro;

class LivroService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function criar(array $data): Livro
    {
        return Livro::create($data);
    }
}
