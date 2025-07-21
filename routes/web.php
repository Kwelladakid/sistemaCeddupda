<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlunoController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\DisciplinaController;
use App\Http\Controllers\TurmaController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\FinanceiroController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\MensalidadeController;
use App\Http\Controllers\MinicursoController;
use App\Http\Controllers\ParticipanteController;
use App\Http\Controllers\CertificadoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FinanceiroAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AlunoDashboardController;
use App\Http\Controllers\ProfessorDashboardController;
use App\Http\Controllers\FinanceiroDashboardController;
use App\Http\Controllers\AdminUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Redireciona a rota raiz para a página de login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rota de teste de view (se ainda estiver usando)
Route::get('/test-view', function () {
    return view('test');
});

// Rotas de Autenticação (Login, Registro e Logout) - ACESSÍVEIS SEM AUTENTICAÇÃO
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
// Rota pública para verificação de certificados (acessível sem autenticação)
Route::get('certificados/verificar', [MinicursoController::class, 'verificarCertificado'])->name('certificados.verificar');

// Todas as rotas protegidas por autenticação
Route::middleware(['auth'])->group(function () {
    // Rota para o Dashboard Principal (Padrão)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Rotas para Dashboards Personalizados
    Route::middleware(['auth', 'role:administrador'])->group(function () {
        Route::get('/dashboard/administrador', [AdminDashboardController::class, 'index'])->name('dashboard.administrador');
    });

    Route::middleware(['role:aluno'])->group(function () {
        // ALTERAÇÃO AQUI: Chamar o método meuPerfil do AlunoController
        Route::get('/dashboard/aluno', [AlunoController::class, 'meuPerfil'])->name('dashboard.aluno');
    });
    Route::middleware(['role:professor'])->group(function () {
        // Rota do dashboard do professor, agora lida com a exibição de alunos via parâmetro de query
        Route::get('/dashboard/professor', [ProfessorDashboardController::class, 'index'])->name('dashboard.professor');
        // A rota 'professor.disciplinas.show' foi removida, pois sua funcionalidade foi integrada ao index()
    });

    Route::middleware(['role:financeiro'])->group(function () {
        Route::get('/dashboard/financeiro', [FinanceiroDashboardController::class, 'index'])->name('dashboard.financeiro');
    });

    // Rotas para o gerenciamento de usuários
    Route::get('/usuarios/create', [AdminUserController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios/store', [AdminUserController::class, 'store'])->name('usuarios.store');

    // Rotas de recursos existentes (Nível Raiz)
    Route::resource('alunos', AlunoController::class);
    Route::resource('professores', ProfessorController::class);
    Route::resource('cursos', CursoController::class);
    Route::resource('disciplinas', DisciplinaController::class);
    Route::resource('turmas', TurmaController::class);
    // INÍCIO: Rotas para o módulo de Minicurso
    Route::resource('minicursos', MinicursoController::class);

    // Rotas para participantes de minicursos
    Route::get('minicursos/{minicurso}/participantes/adicionar', [MinicursoController::class, 'addParticipanteForm'])->name('minicursos.participantes.adicionar');
    Route::post('minicursos/{minicurso}/participantes', [MinicursoController::class, 'storeParticipante'])->name('minicursos.participantes.store');

    // Rotas para geração de certificados de minicursos
    Route::get('minicursos/{minicurso}/participantes/{participante}/certificado', [MinicursoController::class, 'gerarCertificado'])->name('minicursos.participantes.certificado');
    Route::get('minicursos/{minicurso}/participantes/{participante}/certificado/verso', [MinicursoController::class, 'gerarCertificadoVerso'])->name('minicursos.participantes.certificado.verso');
    // Adiciona as rotas que estão faltando
    Route::get('minicursos/{minicurso}/certificado/{participante}', [MinicursoController::class, 'gerarCertificado'])->name('minicursos.certificado.gerar');
    Route::get('minicursos/{minicurso}/certificado/{participante}/verso', [MinicursoController::class, 'gerarCertificadoVerso'])->name('minicursos.certificado.verso');
    // FIM: Rotas para o módulo de Minicurso

    // Redirecionamento para página de seleção de curso quando alguém tentar acessar matriculas/create
    Route::get('matriculas/create', function() {
        return redirect()->route('cursos.index')
            ->with('info', 'Selecione um curso para matricular um aluno.');
    })->name('matriculas.create');

    // Route::resource para 'matriculas'
    Route::resource('matriculas', MatriculaController::class)->except(['create', 'store']);
    // NOVO LOCAL: Rotas para atribuição de notas e faltas
    Route::get('/disciplinas/{disciplina}/notas', [DisciplinaController::class, 'showNotasForm'])->name('disciplinas.notas');
    Route::post('/disciplinas/{disciplina}/notas', [DisciplinaController::class, 'storeNotas'])->name('disciplinas.storeNotas');
    // Rotas para Notas (aninhadas sob Alunos)
    Route::prefix('alunos/{aluno}')->group(function () {
        Route::get('boletim', [NotaController::class, 'boletim'])->name('alunos.boletim');
        Route::get('notas/create', [NotaController::class, 'create'])->name('alunos.notas.create');
        Route::get('boletim/pdf', [NotaController::class, 'gerarBoletimPdf'])->name('alunos.boletim.pdf');
        Route::post('notas', [NotaController::class, 'store'])->name('alunos.notas.store');
        Route::get('notas/{nota}/edit', [NotaController::class, 'edit'])->name('alunos.notas.edit');
        Route::put('notas/{nota}', [NotaController::class, 'update'])->name('alunos.notas.update');
        Route::delete('notas/{nota}', [NotaController::class, 'destroy'])->name('alunos.notas.destroy');
    });
    // Rotas relacionadas a Cursos (Nível Raiz)
    Route::get('cursos/{curso}/turmas/create', [TurmaController::class, 'createForCurso'])->name('cursos.turmas.create');
    Route::post('cursos/{curso}/turmas', [TurmaController::class, 'storeForCurso'])->name('cursos.turmas.store');

    // Rotas para criar disciplinas a partir de um curso
    Route::get('cursos/{curso}/disciplinas/create', [DisciplinaController::class, 'createForCurso'])->name('cursos.disciplinas.create');
    Route::post('cursos/{curso}/disciplinas', [DisciplinaController::class, 'storeForCurso'])->name('cursos.disciplinas.store');
    // Rotas para matricular alunos diretamente em cursos
    Route::get('cursos/{curso}/matricular', [MatriculaController::class, 'matricularEmCurso'])->name('cursos.matricular');
    Route::post('cursos/{curso}/matricular', [MatriculaController::class, 'processarMatriculaEmCurso'])->name('cursos.processar-matricula');
    Route::get('cursos/{curso}/novo-aluno', [MatriculaController::class, 'novoAlunoParaCurso'])->name('cursos.novo-aluno');
    Route::post('cursos/{curso}/novo-aluno', [MatriculaController::class, 'criarEMatricularEmCurso'])->name('cursos.criar-e-matricular');
    Route::post('/alunos/{aluno}/registrar', [AlunoController::class, 'registrar'])->name('alunos.registrar');

    // Rota para confirmar a matrícula
    Route::post('alunos/{id}/confirmar-matricula', [AlunoController::class, 'confirmarMatricula'])->name('alunos.confirmarMatricula');
    // Rotas para gerenciar alunos em turmas
    Route::prefix('turmas/{turma}/alunos')->group(function () {
        Route::get('create', [AlunoController::class, 'createForTurma'])->name('turmas.alunos.create');
        Route::post('/', [AlunoController::class, 'storeForTurma'])->name('turmas.alunos.store');
        Route::get('adicionar', [MatriculaController::class, 'adicionarAluno'])->name('turmas.alunos.adicionar');
        Route::post('vincular', [MatriculaController::class, 'vincularAluno'])->name('turmas.alunos.vincular');
        Route::delete('{aluno}', [MatriculaController::class, 'removerAluno'])->name('turmas.alunos.remover');
        Route::get('novo', [MatriculaController::class, 'novoAluno'])->name('matriculas.novo_aluno');
        Route::post('criar-e-matricular', [MatriculaController::class, 'criarEMatricular'])->name('matriculas.criar_e_matricular');
    });
    // Módulo Financeiro
    Route::prefix('financeiro')->name('financeiro.')->group(function () {
        Route::get('/login', [FinanceiroAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [FinanceiroAuthController::class, 'login'])->name('login.post');
        Route::post('/logout', [FinanceiroAuthController::class, 'logout'])->name('logout');

        // ALTERAÇÃO AQUI: Usando o novo middleware personalizado
        Route::middleware('financeiro.access')->group(function () {
            Route::get('/', [FinanceiroController::class, 'index'])->name('index');
            Route::resource('pagamentos', PagamentoController::class);
            Route::get('mensalidades', [FinanceiroController::class, 'mensalidadesIndex'])->name('mensalidades.index');
            Route::get('/mensalidades-pendentes', [PagamentoController::class, 'getMensalidadesPendentes'])->name('mensalidades-pendentes');
            Route::get('/buscar-alunos', [FinanceiroController::class, 'buscarAlunos'])->name('buscar-alunos');
            Route::post('/buscar-alunos', [FinanceiroController::class, 'buscarAlunos']);
            Route::get('/aluno/{id}', [FinanceiroController::class, 'alunoFinanceiro'])->name('aluno');
            Route::get('/relatorios', [FinanceiroController::class, 'relatorios'])->name('relatorios');
            Route::post('/relatorios/gerar', [FinanceiroController::class, 'gerarRelatorio'])->name('relatorios.gerar');
            Route::get('/mensalidades/gerar-lote', [MensalidadeController::class, 'gerarLote'])->name('mensalidades.gerar-lote');
            Route::post('/mensalidades/store-lote', [MensalidadeController::class, 'storeLote'])->name('mensalidades.store-lote');
            Route::get('/pagamentos/{pagamento}/comprovante', [PagamentoController::class, 'gerarComprovante'])->name('pagamentos.comprovante');
            Route::get('/pagamentos/{pagamento}/recibo', [PagamentoController::class, 'recibo'])->name('pagamentos.recibo');
        });
    });
});
