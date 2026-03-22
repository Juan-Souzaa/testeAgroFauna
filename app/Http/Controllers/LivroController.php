<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLivroRequest;
use App\Models\Categoria;
use App\Models\Livro;
use Inertia\Inertia;
use Inertia\Response;

class LivroController extends Controller
{
    public function create(): Response
    {
        $this->authorize('create', Livro::class);

        return Inertia::render('Livros/Create', [
            'categorias' => Categoria::query()->orderBy('nome')->get(['id', 'nome']),
        ]);
    }

    public function store(StoreLivroRequest $request)
    {
        Livro::create($request->validated());

        return redirect()
            ->route('dashboard')
            ->with('success', 'Livro cadastrado com sucesso.');
    }
}
