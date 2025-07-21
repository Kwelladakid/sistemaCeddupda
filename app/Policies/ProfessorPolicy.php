<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Professor; // Importa o modelo Professor
use Illuminate\Auth\Access\HandlesAuthorization;

class ProfessorPolicy
{
    use HandlesAuthorization;

    /**
     * Permite que administradores e secretárias passem por todas as verificações.
     * Este método é executado ANTES de qualquer outro método da policy.
     *
     * @param  \App\Models\User  $user
     * @param  string  $ability
     * @return bool|void
     */
    public function before(User $user, string $ability)
    {
        if ($user->isAdmin() || $user->isSecretary()) {
            return true; // Administrador e Secretária podem tudo
        }
    }

    /**
     * Determine whether the user can view any models.
     * (Listar professores)
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        // Administrador e Secretária já passam pelo before().
        // Professores podem ver a lista de professores? Alunos?
        // Por padrão, apenas quem pode gerenciar (Admin/Secretaria)
        return false; // Se não for admin/secretaria, nega.
    }

    /**
     * Determine whether the user can view the model.
     * (Ver um professor específico)
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Professor  $professor
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Professor $professor)
    {
        // Administrador e Secretária já passam pelo before().
        // Um professor pode ver seu próprio perfil? (Se User e Professor estiverem relacionados)
        // return $user->id === $professor->user_id; // Exemplo se Professor tiver user_id
        return false; // Se não for admin/secretaria, nega.
    }

    /**
     * Determine whether the user can create models.
     * (Criar novos professores)
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        // Administrador e Secretária já passam pelo before().
        return false; // Se não for admin/secretaria, nega.
    }

    /**
     * Determine whether the user can update the model.
     * (Atualizar um professor específico)
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Professor  $professor
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Professor $professor)
    {
        // Administrador e Secretária já passam pelo before().
        // Um professor pode atualizar seu próprio perfil? (Se User e Professor estiverem relacionados)
        // return $user->id === $professor->user_id; // Exemplo se Professor tiver user_id
        return false; // Se não for admin/secretaria, nega.
    }

    /**
     * Determine whether the user can delete the model.
     * (Deletar um professor específico)
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Professor  $professor
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Professor $professor)
    {
        // Administrador e Secretária já passam pelo before().
        return false; // Por segurança, apenas administradores devem deletar.
    }
}
