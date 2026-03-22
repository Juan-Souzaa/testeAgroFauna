<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListarLivrosRequest;
use App\Http\Requests\StoreLivroRequest;
use App\Models\Categoria;
use App\Models\Livro;
use App\Services\LivroService;
use Inertia\Inertia;
use Inertia\Response;

class LivroController extends Controller
{
    public function index(ListarLivrosRequest $request, LivroService $livros): Response
    {
        return Inertia::render('Livros/Index', [
            'livros' => $livros->listarPaginado($request->itensPorPagina()),
            'porPaginaOpcoes' => config('livros.per_page.options'),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Livro::class);

        return Inertia::render('Livros/Create', [
            'categorias' => Categoria::query()->orderBy('nome')->get(['id', 'nome']),
        ]);
    }

    public function store(StoreLivroRequest $request, LivroService $livros)
    {
        $livros->criar($request->validated());

        return redirect()
            ->route('livros.index')
            ->with('success', 'Livro cadastrado com sucesso.');
    }
}
