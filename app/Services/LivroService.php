<?php

namespace App\Services;

use App\Models\Livro;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LivroService
{
    public function listarPaginado(int $porPagina, ?string $busca = null): LengthAwarePaginator
    {
        $query = Livro::query()
            ->with('categoria:id,nome');

        if ($busca !== null && $busca !== '') {
            $padrao = '%'.$busca.'%';
            $query->where(function ($q) use ($padrao): void {
                $q->whereLike('titulo', $padrao)
                    ->orWhereLike('autor', $padrao)
                    ->orWhereLike('isbn', $padrao);
            });
        }

        return $query
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
