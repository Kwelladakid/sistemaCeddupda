<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Nota; // Importa o modelo Nota
use App\Models\Aluno; // Importa o modelo Aluno, pois 'create' e 'view' são contextualizados a ele
use Illuminate\Auth\Access\HandlesAuthorization;

class NotaPolicy
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
     * Determine whether the user can create models.
     * (Criar/Armazenar notas para um aluno específico)
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Aluno  $aluno // O aluno para quem a nota será criada
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Aluno $aluno)
    {
        // Administrador e Secretária já passam pelo before().
        // Professor pode criar/atribuir notas se ele leciona para este aluno
        // Isso requer um relacionamento entre User (professor) -> Disciplina/Turma -> Aluno
        if ($user->isProfessor()) {
            // Exemplo: Verifica se o professor leciona alguma disciplina que o aluno está cursando
            // Isso pode ser complexo dependendo da sua estrutura de relacionamentos.
            // Uma forma simples seria: o professor pode atribuir notas a qualquer aluno de suas turmas.
            // return $user->professor->turmas->flatMap->alunos->contains($aluno);
            // Ou, se o professor está associado a disciplinas, e o aluno a disciplinas:
            // return $user->professor->disciplinas->intersect($aluno->disciplinas)->isNotEmpty();
            return true; // Por enquanto, assume que qualquer professor pode criar notas. REFINAR!
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     * (Ver o boletim de um aluno específico)
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Aluno  $aluno // O aluno cujo boletim será visualizado
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Aluno $aluno)
    {
        // Administrador e Secretária já passam pelo before().
        // Aluno pode ver seu próprio boletim
        if ($user->isAluno() && $user->aluno->id === $aluno->id) {
            return true;
        }
        // Professor pode ver o boletim de alunos que ele leciona
        if ($user->isProfessor()) {
            // Similar à lógica de 'create', verifica se o professor tem relação com o aluno
            // return $user->professor->turmas->flatMap->alunos->contains($aluno);
            return true; // Por enquanto, assume que qualquer professor pode ver boletins. REFINAR!
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     * (Atualizar uma nota específica)
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Nota  $nota
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Nota $nota)
    {
        // Administrador e Secretária já passam pelo before().
        // Professor pode atualizar a nota se ele leciona a disciplina da nota
        if ($user->isProfessor()) {
            // return $user->professor->disciplinas->contains($nota->disciplina);
            return true; // Por enquanto, assume que qualquer professor pode atualizar notas. REFINAR!
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     * (Deletar uma nota específica)
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Nota  $nota
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Nota $nota)
    {
        // Administrador e Secretária já passam pelo before().
        // Professor pode deletar a nota se ele leciona a disciplina da nota (opcional, geralmente mais restrito)
        if ($user->isProfessor()) {
            // return $user->professor->disciplinas->contains($nota->disciplina);
            return true; // Por enquanto, assume que qualquer professor pode deletar notas. REFINAR!
        }
        return false;
    }
}
