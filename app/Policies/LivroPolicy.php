<?php

namespace App\Policies;

use App\Models\Livro;
use App\Models\User;

class LivroPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('books.view');
    }

    public function view(User $user, Livro $livro): bool
    {
        return $user->can('books.view');
    }

    public function create(User $user): bool
    {
        return $user->can('books.create');
    }

    public function update(User $user, Livro $livro): bool
    {
        return $user->can('books.update');
    }

    public function delete(User $user, Livro $livro): bool
    {
        return $user->can('books.delete');
    }

    public function restore(User $user, Livro $livro): bool
    {
        return false;
    }

    public function forceDelete(User $user, Livro $livro): bool
    {
        return false;
    }
}
