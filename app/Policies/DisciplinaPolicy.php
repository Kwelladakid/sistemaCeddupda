<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Disciplina; // Importa o modelo Disciplina
use Illuminate\Auth\Access\HandlesAuthorization;

class DisciplinaPolicy
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
     * (Listar disciplinas)
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        // Administrador e Secretária já passam pelo before().
        // Professores e Alunos podem ver a lista de disciplinas.
        return $user->isProfessor() || $user->isAluno();
    }

    /**
     * Determine whether the user can view the model.
     * (Ver uma disciplina específica)
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Disciplina  $disciplina
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Disciplina $disciplina)
    {
        // Administrador e Secretária já passam pelo before().
        // Professor pode ver a disciplina se ele for o professor responsável por ela.
        if ($user->isProfessor()) {
            // Assumindo que User tem relacionamento com Professor, e Professor tem id
            // E Disciplina tem professor_id
            return $user->professor && $user->professor->id === $disciplina->professor_id;
        }
        // Aluno pode ver a disciplina se ele estiver matriculado em alguma turma que oferece essa disciplina.
        if ($user->isAluno()) {
            // Assumindo que User tem relacionamento com Aluno, Aluno com Turmas, e Turmas com Disciplinas
            return $user->aluno && $user->aluno->turmas->flatMap->disciplinas->contains($disciplina);
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     * (Criar novas disciplinas)
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        // Apenas Administrador e Secretária podem criar disciplinas (já coberto pelo before)
        return false; // Se não for admin/secretaria, nega.
    }

    /**
     * Determine whether the user can update the model.
     * (Atualizar uma disciplina específica)
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Disciplina  $disciplina
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Disciplina $disciplina)
    {
        // Apenas Administrador e Secretária podem atualizar disciplinas (já coberto pelo before)
        return false; // Se não for admin/secretaria, nega.
    }

    /**
     * Determine whether the user can delete the model.
     * (Deletar uma disciplina específica)
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Disciplina  $disciplina
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Disciplina $disciplina)
    {
        // Apenas Administrador e Secretária podem deletar disciplinas (já coberto pelo before)
        return false; // Por segurança, apenas administradores devem deletar.
    }

    /**
     * Determine whether the user can assign notes and absences for the discipline.
     * (Atribuir notas e faltas para uma disciplina)
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Disciplina  $disciplina
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function assignNotes(User $user, Disciplina $disciplina)
    {
        // Administrador e Secretária já passam pelo before().
        // O professor responsável pela disciplina pode atribuir notas.
        if ($user->isProfessor()) {
            // Assumindo que User tem relacionamento com Professor, e Professor tem id
            // E Disciplina tem professor_id
            return $user->professor && $user->professor->id === $disciplina->professor_id;
        }
        return false;
    }
}
