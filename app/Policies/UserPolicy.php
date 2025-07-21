<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true; // Administradores podem tudo
        }
    }

    public function viewAny(User $user)
    {
        return $user->isAdmin(); // Apenas administradores podem visualizar usuários
    }

    public function create(User $user)
    {
        return $user->isAdmin(); // Apenas administradores podem criar usuários
    }

    public function update(User $user, User $model)
    {
        return $user->isAdmin(); // Apenas administradores podem editar usuários
    }

    public function delete(User $user, User $model)
    {
        return $user->isAdmin(); // Apenas administradores podem excluir usuários
    }
}
