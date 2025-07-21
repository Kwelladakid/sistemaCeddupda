<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Matricula;
use Illuminate\Auth\Access\HandlesAuthorization;

class MatriculaPolicy
{
    use HandlesAuthorization;

    /**
     * Permite que administradores tenham acesso total.
     *
     * @param  \App\Models\User  $user
     * @param  string  $ability
     * @return bool|void
     */
    public function before(User $user, $ability)
    {
        if ($user->role === 'administrador') {
            return true; // Administradores podem tudo
        }
    }

    /**
     * Determina se o usuário pode visualizar a lista de matrículas.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        return $user->role === 'administrador' || $user->role === 'secretaria';
    }

    /**
     * Determina se o usuário pode visualizar uma matrícula específica.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Matricula  $matricula
     * @return bool
     */
    public function view(User $user, Matricula $matricula)
    {
        return $user->role === 'administrador' || $user->role === 'secretaria';
    }

    /**
     * Determina se o usuário pode criar uma nova matrícula.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->role === 'administrador' || $user->role === 'secretaria';
    }

    /**
     * Determina se o usuário pode atualizar uma matrícula.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Matricula  $matricula
     * @return bool
     */
    public function update(User $user, Matricula $matricula)
    {
        return $user->role === 'administrador' || $user->role === 'secretaria';
    }

    /**
     * Determina se o usuário pode excluir uma matrícula.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Matricula  $matricula
     * @return bool
     */
    public function delete(User $user, Matricula $matricula)
    {
        return $user->role === 'administrador';
    }
}
