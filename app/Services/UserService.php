<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class UserService
{
    public function listarPaginado(int $porPagina): LengthAwarePaginator
    {
        return User::query()
            ->with('roles')
            ->orderBy('name')
            ->paginate($porPagina)
            ->withQueryString();
    }


    public function estatisticas(): array
    {
        return [
            'total' => User::query()->count(),
            'admins' => User::role('admin')->count(),
            'editores' => User::role('editor')->count(),
        ];
    }

    
    public function papeisOrdenados(): array
    {
        return Role::query()->orderBy('name')->pluck('name')->values()->all();
    }

    
    public function criarEditor(string $name, string $email, string $password): User
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        $user->assignRole('editor');

        return $user;
    }

    public function alterarPapel(User $usuario, string $novoPapel, User $autor): void
    {
        if ($this->NaoExisteOutroAdministrador($usuario, $novoPapel, $autor)) {
            throw ValidationException::withMessages([
                'role' => 'É necessário existir pelo menos um administrador.',
            ]);
        }

        $usuario->syncRoles([$novoPapel]);
    }

    
   
    private function NaoExisteOutroAdministrador(
        User $usuario,
        string $novoPapel,
        User $autor,
    ): bool {
        if ($usuario->id !== $autor->id || $novoPapel === 'admin') {
            return false;
        }

        return User::role('admin')
            ->where('id', '!=', $usuario->id)
            ->doesntExist();
    }
}
