<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListarLivrosRequest;
use App\Http\Requests\StoreLivroRequest;
use App\Http\Requests\UpdateLivroRequest;
use App\Models\AuditLog;
use App\Models\Categoria;
use App\Models\Livro;
use App\Services\AuditLogService;
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

    public function store(StoreLivroRequest $request, LivroService $livros, AuditLogService $auditLog)
    {
        $livro = $livros->criar($request->validated());

        $auditLog->registrar(
            $request->user(),
            AuditLog::LIVRO_CREATED,
            $livro,
            $livro->titulo,
        );

        return redirect()
            ->route('livros.index')
            ->with('success', 'Livro cadastrado com sucesso.');
    }

    public function edit(Livro $livro): Response
    {
        $this->authorize('update', $livro);

        return Inertia::render('Livros/Edit', [
            'livro' => $livro,
            'categorias' => Categoria::query()->orderBy('nome')->get(['id', 'nome']),
        ]);
    }

    public function update(UpdateLivroRequest $request, Livro $livro, LivroService $livros, AuditLogService $auditLog)
    {
        $livro = $livros->atualizar($livro, $request->validated());

        $auditLog->registrar(
            $request->user(),
            AuditLog::LIVRO_UPDATED,
            $livro,
            $livro->titulo,
        );

        return redirect()
            ->route('livros.index')
            ->with('success', 'Livro atualizado com sucesso.');
    }

    public function destroy(Livro $livro, LivroService $livros, AuditLogService $auditLog)
    {
        $this->authorize('delete', $livro);

        $auditLog->registrar(
            request()->user(),
            AuditLog::LIVRO_DELETED,
            $livro,
            $livro->titulo,
        );

        $livros->apagar($livro);

        return redirect()
            ->route('livros.index')
            ->with('success', 'Livro excluído com sucesso.');
    }
}
