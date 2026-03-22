<?php

namespace App\Services;

use App\Models\Livro;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LivroService
{
    public function listarPaginado(int $porPagina): LengthAwarePaginator
    {
        return Livro::query()
            ->with('categoria:id,nome')
            ->orderByDesc('id')
            ->paginate($porPagina)
            ->withQueryString();
    }

    public function criar(array $data): Livro
    {
        return Livro::create($data);
    }

    public function atualizar(Livro $livro, array $data): Livro
    {
        $livro->update($data);

        return $livro->fresh();
    }

    public function apagar(Livro $livro): void
    {
        $livro->delete();
    }
}
