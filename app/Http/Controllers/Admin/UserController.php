<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRoleRequest;
use App\Models\User;
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

    public function updateRole(UpdateUserRoleRequest $request, User $user, UserService $usuarios): RedirectResponse
    {
        $novoPapel = $request->validated('role');

        try {
            $usuarios->alterarPapel($user, $novoPapel, $request->user());
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors());
        }

        return redirect()
            ->back()
            ->with('success', 'Papel do usuário atualizado.');
    }
}
