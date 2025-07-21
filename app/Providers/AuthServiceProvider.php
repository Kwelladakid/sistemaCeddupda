<?php

namespace App\Providers;


use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate; // Importa a facade Gate
use App\Http\Controllers\FinanceiroController;
use App\Models\User; // Importa o modelo User
use App\Models\Aluno; // Importa o modelo Aluno (para AlunoPolicy)
use App\Models\Matricula; // Importa o modelo Matricula (para MatriculaPolicy)
use App\Models\Nota; // Importa o modelo Nota (para NotaPolicy)
use App\Models\Professor; // Importa o modelo Professor (para ProfessorPolicy)
use App\Policies\FinanceiroPolicy;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\User' => 'App\Policies\UserPolicy',
        'App\Models\Aluno' => 'App\Policies\AlunoPolicy',
        'App\Models\Matricula' => 'App\Policies\MatriculaPolicy',
        'App\Models\Nota' => 'App\Policies\NotaPolicy',
        'App\Models\Disciplina' => 'App\Policies\DisciplinaPolicy',
        'FinanceiroController::class' => 'FinanceiroPolicy',
        'App\Models\Professor' => 'App\Policies\ProfessorPolicy', // <-- LINHA CORRIGIDA E ADICIONADA AQUI
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // --- Definição de Gates para Verificação de Papéis ---

        // Gate: 'is-admin'
        // Permite acesso total. Apenas o administrador pode passar.
        Gate::define('is-admin', function (User $user) {
            return $user->isAdmin(); // Utiliza o método auxiliar do modelo User
        });

        // Gate: 'is-secretary'
        // Permite acesso a funcionalidades de secretaria.
        Gate::define('is-secretary', function (User $user) {
            return $user->isSecretary();
        });

        // Gate: 'is-professor'
        // Permite acesso a funcionalidades de professor.
        Gate::define('is-professor', function (User $user) {
            return $user->isProfessor();
        });

        // Gate: 'is-aluno'
        // Permite acesso a funcionalidades de aluno.
        Gate::define('is-aluno', function (User $user) {
            return $user->isAluno();
        });

        // Gate: 'manage-users'
        // Permite gerenciar usuários (administrador e secretaria).
        Gate::define('manage-users', function (User $user) {
            return $user->isAdmin() || $user->isSecretary();
        });

        // Gate: 'access-financeiro'
        // Permite acesso ao módulo financeiro (administrador e secretaria).
        Gate::define('access-financeiro', function (User $user) {
            return $user->isAdmin() || $user->isSecretary();
        });

        // --- Fim da Definição de Gates ---
    }
}
