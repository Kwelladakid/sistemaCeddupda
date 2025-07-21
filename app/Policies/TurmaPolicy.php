<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Turma; // Importa o modelo Turma
use Illuminate\Auth\Access\HandlesAuthorization;

class TurmaPolicy
{
    use HandlesAuthorization;

    /**
     * Permite que administradores e secretárias passem por todas as verificações.
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
     * (Listar turmas)
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        // Administrador e Secretária já passam pelo before().
        // Professores e Alunos podem ver turmas (as suas ou todas, dependendo da regra de negócio)
        return $user->isProfessor() || $user->isAluno();
    }

    /**
     * Determine whether the user can view the model.
     * (Ver uma turma específica)
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Turma  $turma
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Turma $turma)
    {
        // Administrador e Secretária já passam pelo before().
        // Professor pode ver a turma se ele for o professor responsável ou lecionar nela.
        if ($user->isProfessor()) {
            // Assumindo que User tem relacionamento com Professor, e Professor com Turmas
            // Ou que Turma tem um relacionamento com Professor
            // Exemplo: return $turma->professores->contains($user->professor);
            // Ou se o professor está vinculado à turma via relacionamento User->Turma
            return $user->turmas->contains($turma);
        }
        // Aluno pode ver a turma se ele estiver matriculado nela.
        if ($user->isAluno()) {
            // Assumindo que User tem relacionamento com Aluno, e Aluno com Turmas
            return $user->aluno->turmas->contains($turma);
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     * (Criar novas turmas)
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        // Apenas Administrador e Secretária podem criar turmas (já coberto pelo before)
        return false; // Se não for admin/secretaria, nega.
    }

    /**
     * Determine whether the user can update the model.
     * (Atualizar uma turma específica)
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Turma  $turma
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Turma $turma)
    {
        // Apenas Administrador e Secretária podem atualizar turmas (já coberto pelo before)
        return false; // Se não for admin/secretaria, nega.
    }

    /**
     * Determine whether the user can delete the model.
     * (Deletar uma turma específica)
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Turma  $turma
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Turma $turma)
    {
        // Apenas Administrador e Secretária podem deletar turmas (já coberto pelo before)
        return false; // Por segurança, apenas administradores devem deletar.
    }

    // Você pode adicionar uma ação específica para 'addAluno' se quiser um controle mais granular
    // public function addAluno(User $user, Turma $turma) { ... }
}
