<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Aluno;
use Illuminate\Auth\Access\HandlesAuthorization;

class AlunoPolicy
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
     * Determina se o usuário pode visualizar a lista de alunos.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        return $user->role === 'administrador' || $user->role === 'secretaria';
    }

    /**
     * Determina se o usuário pode visualizar um aluno específico.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Aluno  $aluno
     * @return bool
     */
    public function view(User $user, Aluno $aluno)
        {
            // Administradores e secretárias podem visualizar qualquer aluno
            if ($user->role === 'administrador' || $user->role === 'secretaria') {
                return true;
            }

            // Alunos podem visualizar apenas o seu próprio registro
            // Verifica se o usuário logado é um aluno E se o ID do usuário logado
            // corresponde ao user_id do aluno que está sendo visualizado.
            return $user->role === 'aluno' && $user->id === $aluno->user_id;
        }
    /**
     * Determina se o usuário pode criar novos alunos.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->role === 'administrador' || $user->role === 'secretaria';
    }

    /**
     * Determina se o usuário pode atualizar um aluno.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Aluno  $aluno
     * @return bool
     */
    public function update(User $user, Aluno $aluno)
    {
        return $user->role === 'administrador' || $user->role === 'secretaria';
    }

    /**
     * Determina se o usuário pode excluir um aluno.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Aluno  $aluno
     * @return bool
     */
    public function delete(User $user, Aluno $aluno)
    {
        return $user->role === 'administrador';
    }
}
