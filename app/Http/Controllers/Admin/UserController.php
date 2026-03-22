<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRoleRequest;
use App\Models\AuditLog;
use App\Models\User;
use App\Services\AuditLogService;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
   

    public function index(UserService $usuarios): Response
    {
        return Inertia::render('Admin/Users/Index', [
            'usuarios' => $usuarios->listarPaginado(12),
            'papeis' => $usuarios->papeisOrdenados(),
            'estatisticas' => $usuarios->estatisticas(),
        ]);
    }

    public function store(StoreUserRequest $request, UserService $usuarios, AuditLogService $auditLog): RedirectResponse
    {
        $dados = $request->validated();

        $novo = $usuarios->criarEditor(
            $dados['name'],
            $dados['email'],
            $dados['password'],
        );

        $auditLog->registrar(
            $request->user(),
            AuditLog::USUARIO_CREATED,
            $novo,
            $novo->name.' · '.$novo->email,
        );

        return redirect()
            ->back()
            ->with('success', 'Usuário criado como editor. Ele já pode entrar com o e-mail e a senha definidos.');
    }

    public function updateRole(UpdateUserRoleRequest $request, User $user, UserService $usuarios, AuditLogService $auditLog): RedirectResponse
    {
        $novoPapel = $request->validated('role');
        $papelAnterior = $user->roles->first()?->name;

        try {
            $usuarios->alterarPapel($user, $novoPapel, $request->user());
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors());
        }

        $atualizado = $user->fresh();

        $auditLog->registrar(
            $request->user(),
            AuditLog::USUARIO_ROLE_CHANGED,
            $atualizado,
            $atualizado->name.' · '.$atualizado->email,
            [
                'de' => $papelAnterior,
                'para' => $novoPapel,
            ],
        );

        return redirect()
            ->back()
            ->with('success', 'Papel do usuário atualizado.');
    }

    public function destroy(User $user, UserService $usuarios, AuditLogService $auditLog): RedirectResponse
    {
        $descricaoAlvo = $user->name.' · '.$user->email;
        $idAlvo = $user->id;

        try {
            $usuarios->excluir($user, request()->user());
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors());
        }

        $auditLog->registrar(
            request()->user(),
            AuditLog::USUARIO_DELETED,
            null,
            $descricaoAlvo,
            ['usuario_id' => $idAlvo],
        );

        return redirect()
            ->back()
            ->with('success', 'Usuário removido.');
    }
}
