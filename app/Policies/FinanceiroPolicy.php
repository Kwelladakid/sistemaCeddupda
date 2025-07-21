<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FinanceiroPolicy
{
    use HandlesAuthorization;

    /**
     * Permite que administradores tenham acesso total a todas as ações.
     *
     * @param  \App\Models\User  $user
     * @param  string  $ability
     * @return bool|void
     */
    public function before(User $user, $ability)
    {
        if ($user->role === 'administrador') {
            return true; // Administradores podem acessar todas as funcionalidades financeiras
        }
    }

    /**
     * Determina se o usuário pode visualizar o dashboard financeiro.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewDashboard(User $user)
    {
        return $user->role === 'administrador' || $user->role === 'secretaria';
    }

    /**
     * Determina se o usuário pode visualizar a página de pagamentos.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewPayments(User $user)
    {
        return $user->role === 'administrador' || $user->role === 'secretaria';
    }

    /**
     * Determina se o usuário pode visualizar a página de mensalidades.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewMensalidades(User $user)
    {
        return $user->role === 'administrador' || $user->role === 'secretaria';
    }

    /**
     * Determina se o usuário pode buscar alunos no módulo financeiro.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function searchStudents(User $user)
    {
        return $user->role === 'administrador' || $user->role === 'secretaria';
    }

    /**
     * Determina se o usuário pode visualizar os relatórios financeiros.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewReports(User $user)
    {
        return $user->role === 'administrador' || $user->role === 'secretaria';
    }

    /**
     * Determina se o usuário pode visualizar o histórico financeiro de um aluno específico.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewStudentFinance(User $user)
    {
        return $user->role === 'administrador' || $user->role === 'secretaria';
    }
}
