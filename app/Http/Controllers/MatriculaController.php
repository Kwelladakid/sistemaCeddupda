<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Matricula;
use App\Models\Turma;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; // Adicionado para Hash::make()
use App\Models\User; // Adicionado para criar o usuário do aluno

class MatriculaController extends Controller
{
    /**
     * Exibe a lista de matrículas.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Autoriza a ação 'viewAny' na MatriculaPolicy.
        // Apenas administradores e secretárias podem visualizar todas as matrículas.
        $this->authorize('viewAny', Matricula::class);

        // Busca todas as matrículas do banco de dados
        $matriculas = Matricula::all();

        // Retorna a view 'matriculas.index' e passa as matrículas para ela
        return view('matriculas.index', compact('matriculas'));
    }

    /**
     * Mostra o formulário para adicionar um aluno existente a uma turma.
     *
     * @param  \App\Models\Turma  $turma
     * @return \Illuminate\View\View
     */
    public function adicionarAluno(Turma $turma)
    {
        // Autoriza a ação 'create' na MatriculaPolicy, contextualizada à turma.
        $this->authorize('create', [Matricula::class, $turma]);

        // Busca alunos que não estão matriculados nesta turma
        $alunosDisponiveis = Aluno::whereDoesntHave('turmas', function ($query) use ($turma) {
            $query->where('turmas.id', $turma->id);
        })->orderBy('nome')->get();

        return view('matriculas.adicionar_aluno', compact('turma', 'alunosDisponiveis'));
    }

    /**
     * Vincula um aluno existente a uma turma.
     * Retorna true em caso de sucesso, ou lança uma exceção em caso de erro.
     * Não faz redirecionamentos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Turma  $turma
     * @return bool
     * @throws \Exception
     */
    public function vincularAluno(Request $request, Turma $turma)
    {
        // A validação de 'aluno_id', 'data_matricula' e 'status' deve ser feita no método chamador
        // ou você pode adicioná-la aqui se este método for chamado diretamente.
        // Por simplicidade, assumimos que os dados necessários estão no Request.

        // Verifica se o aluno já está matriculado nesta turma
        $alunoJaMatriculado = $turma->alunos()->where('alunos.id', $request->aluno_id)->exists();
        if ($alunoJaMatriculado) {
            throw new \Exception('Este aluno já está matriculado nesta turma.');
        }

        // Verifica se o aluno já está matriculado em outra turma com status ativo
        if ($request->status === 'ativa') {
            $matriculasAtivas = DB::table('aluno_turma')
                ->where('aluno_id', $request->aluno_id)
                ->where('status', 'ativa')
                ->first();

            if ($matriculasAtivas) {
                $turmaAtual = Turma::find($matriculasAtivas->turma_id);
                $nomeTurmaAtual = $turmaAtual ? $turmaAtual->nome : 'desconhecida';
                $cursoAtual = $turmaAtual ? Curso::find($turmaAtual->curso_id) : null;
                $nomeCursoAtual = $cursoAtual ? $cursoAtual->nome : 'desconhecido';
                throw new \Exception("Este aluno já está matriculado ativamente na turma \"{$nomeTurmaAtual}\" do curso \"{$nomeCursoAtual}\". Cancele a matrícula atual antes de matriculá-lo em uma nova turma.");
            }
        }

        // Verifica se a turma está ativa
        if ($turma->status !== 'ativa') {
            throw new \Exception('Não é possível matricular alunos em uma turma que não está ativa.');
        }

        // Verifica se a turma tem limite de vagas e se ainda há vagas disponíveis
        if (isset($turma->vagas_maximas) && $request->status === 'ativa') {
            $matriculasAtivas = $turma->alunos()
                ->wherePivot('status', 'ativa')
                ->count();

            if ($matriculasAtivas >= $turma->vagas_maximas) {
                throw new \Exception("A turma \"{$turma->nome}\" atingiu o limite máximo de \"{$turma->vagas_maximas}\" alunos ativos.");
            }
        }

        // Matricula o aluno na turma
        $turma->alunos()->attach($request->aluno_id, [
            'data_matricula' => $request->data_matricula ?? now(),
            'status' => $request->status,
        ]);

        return true; // Indica sucesso
    }

    /**
     * Remove um aluno de uma turma.
     *
     * @param  \App\Models\Turma  $turma
     * @param  \App\Models\Aluno  $aluno
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removerAluno(Turma $turma, Aluno $aluno)
    {
        // Autoriza a ação 'delete' na MatriculaPolicy, contextualizada à turma e ao aluno.
        $this->authorize('delete', [Matricula::class, $turma, $aluno]);

        try {
            // Verifica se o aluno está matriculado nesta turma
            $matriculado = $turma->alunos()->where('alunos.id', $aluno->id)->exists();

            if (!$matriculado) {
                return redirect()->route('turmas.show', $turma->id)
                    ->with('error', 'Este aluno não está matriculado nesta turma.');
            }

            // Remove o aluno da turma
            $turma->alunos()->detach($aluno->id);

            return redirect()->route('turmas.show', $turma->id)
                ->with('success', 'Aluno "' . $aluno->nome . '" removido com sucesso da turma "' . $turma->nome . '".');
        } catch (\Exception $e) {
            return redirect()->route('turmas.show', $turma->id)
                ->with('error', 'Erro ao remover aluno: ' . $e->getMessage());
        }
    }

    /**
     * Exibe o formulário para matricular um aluno existente em um curso.
     *
     * @param  \App\Models\Curso  $curso
     * @return \Illuminate\View\View
     */
    public function matricularEmCurso(Curso $curso)
    {
        // Autoriza a ação 'create' na MatriculaPolicy, contextualizada ao curso.
        $this->authorize('create', [Matricula::class, $curso]);

        $alunosDisponiveis = Aluno::orderBy('nome')->get();
        $turmasDoCurso = $curso->turmas()->where('status', 'ativa')->get();

        return view('matriculas.matricular_em_curso', compact('curso', 'alunosDisponiveis', 'turmasDoCurso'));
    }

    /**
     * Processa a matrícula de um aluno existente em um curso (e uma turma específica dentro dele).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Curso  $curso
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processarMatriculaEmCurso(Request $request, Curso $curso)
    {
        // Autoriza a ação 'create' na MatriculaPolicy, contextualizada ao curso.
        $this->authorize('create', [Matricula::class, $curso]);

        $request->validate([
            'aluno_id' => 'required|exists:alunos,id',
            'turma_id' => [
                'required',
                'exists:turmas,id',
                function ($attribute, $value, $fail) use ($curso) {
                    if (!$curso->turmas()->where('id', $value)->exists()) {
                        $fail("A turma selecionada não pertence a este curso.");
                    }
                },
            ],
            'data_matricula' => 'nullable|date',
            'status' => 'required|in:ativa,trancada,cancelada,concluida',
        ]);

        $turma = Turma::findOrFail($request->turma_id);

        DB::beginTransaction(); // Inicia a transação aqui

        try {
            // Prepara um novo Request para chamar o método vincularAluno
            $matriculaRequest = new Request([
                'aluno_id' => $request->aluno_id,
                'data_matricula' => $request->data_matricula,
                'status' => $request->status,
            ]);

            // Chama vincularAluno, que agora lança exceções em caso de erro
            $this->vincularAluno($matriculaRequest, $turma);

            DB::commit(); // Confirma a transação

            $aluno = Aluno::find($request->aluno_id); // Busca o aluno para a mensagem de sucesso
            return redirect()->route('turmas.show', $turma->id)
                ->with('success', 'Aluno "' . $aluno->nome . '" matriculado com sucesso na turma "' . $turma->nome . '".');

        } catch (\Exception $e) {
            DB::rollBack(); // Reverte a transação em caso de erro
            return redirect()->back()->withInput()->with('error', 'Erro ao matricular aluno: ' . $e->getMessage());
        }
    }

    /**
     * Exibe o formulário para criar um novo aluno e matriculá-lo em um curso.
     *
     * @param  \App\Models\Curso  $curso
     * @return \Illuminate\View\View
     */
    public function novoAlunoParaCurso(Curso $curso)
    {
        // Autoriza a ação 'create' na MatriculaPolicy, contextualizada ao curso.
        $this->authorize('create', [Matricula::class, $curso]);

        $turmasDoCurso = $curso->turmas()->where('status', 'ativa')->get();

        return view('matriculas.novo_aluno_para_curso', compact('curso', 'turmasDoCurso'));
    }

    /**
     * Cria um novo aluno e o matricula em um curso (e uma turma específica dentro dele).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Curso  $curso
     * @return \Illuminate\Http\RedirectResponse
     */
    public function criarEMatricularEmCurso(Request $request, Curso $curso)
    {
        // Autoriza a ação 'create' na MatriculaPolicy, contextualizada ao curso.
        $this->authorize('create', [Matricula::class, $curso]);

        // Valida os dados do novo aluno e da matrícula
        $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:alunos,cpf|unique:users,cpf', // CPF único para alunos e usuários
            'data_nascimento' => 'required|date',
            'endereco' => 'required|string|max:255',
            'telefone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:alunos,email|unique:users,email', // Email único para alunos e usuários
            'status_aluno' => 'required|string|in:ativo,inativo,trancada,formado', // Status para o registro do aluno
            'turma_id' => [
                'required',
                'exists:turmas,id',
                function ($attribute, $value, $fail) use ($curso) {
                    if (!$curso->turmas()->where('id', $value)->exists()) {
                        $fail("A turma selecionada não pertence a este curso.");
                    }
                },
            ],
            'data_matricula' => 'nullable|date',
            'status_matricula' => 'required|string|in:ativa,trancada,cancelada,concluida', // Status para a matrícula na turma
        ]);

        // Inicia uma transação de banco de dados para garantir atomicidade
        DB::beginTransaction();

        try {
            // Cria o novo Aluno
            $aluno = Aluno::create([
                'nome' => $request->nome,
                'cpf' => $request->cpf,
                'data_nascimento' => $request->data_nascimento,
                'endereco' => $request->endereco,
                'telefone' => $request->telefone,
                'email' => $request->email,
                'status' => $request->status_aluno,
            ]);

            // Cria o Usuário para o novo Aluno (CPF como login/senha)
            $user = User::create([
                'name' => $request->nome,
                'email' => $request->email, // Usando o email do aluno para o email do usuário
                'cpf' => $request->cpf, // Usando o CPF do aluno para o CPF do usuário (login)
                'password' => Hash::make($request->cpf), // CPF como senha padrão (hashed)
                'role' => 'aluno', // Atribui o papel 'aluno'
            ]);

            // Vincula o usuário ao aluno
            $aluno->user_id = $user->id;
            $aluno->save();

            // Prepara um novo Request para chamar o método vincularAluno
            // Isso é necessário porque vincularAluno espera um objeto Request com 'aluno_id'
            $matriculaRequest = new Request([
                'aluno_id' => $aluno->id,
                'data_matricula' => $request->data_matricula,
                'status' => $request->status_matricula,
            ]);

            $turma = Turma::findOrFail($request->turma_id);

            // Chama vincularAluno para anexar o novo aluno à turma selecionada
            // Este método agora lança exceções em caso de erro, permitindo o rollback
            $this->vincularAluno($matriculaRequest, $turma);

            DB::commit(); // Confirma a transação

            // Busca o nome do aluno e da turma para a mensagem de sucesso
            $alunoNome = $aluno->nome;
            $turmaNome = $turma->nome;

            return redirect()->route('turmas.show', $turma->id) // Redireciona para a página da turma
                             ->with('success', "Aluno \"{$alunoNome}\" criado e matriculado com sucesso na turma \"{$turmaNome}\".");

        } catch (\Exception $e) {
            DB::rollBack(); // Reverte a transação em caso de erro
            return redirect()->back()->withInput()->with('error', 'Erro ao criar aluno e matricular: ' . $e->getMessage());
        }
    }

    /**
     * Exibe o formulário para criar um novo aluno e matriculá-lo em uma turma específica.
     *
     * @param  \App\Models\Turma  $turma
     * @return \Illuminate\View\View
     */
    public function novoAluno(Turma $turma)
    {
        // Autoriza a ação 'create' na MatriculaPolicy, contextualizada à turma.
        $this->authorize('create', [Matricula::class, $turma]);

        // Obtém o curso associado à turma
        $curso = $turma->curso;

        // Cria um array com apenas esta turma para o select
        $turmasDoCurso = collect([$turma]);

        // Passa a turma, o curso e a coleção de turmas para a view existente
        return view('matriculas.novo_aluno_para_curso', compact('turma', 'curso', 'turmasDoCurso'));
    }

    /**
     * Cria um novo aluno e o matricula em uma turma específica.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Turma  $turma
     * @return \Illuminate\Http\RedirectResponse
     */
    public function criarEMatricular(Request $request, Turma $turma)
    {
        // Autoriza a ação 'create' na MatriculaPolicy, contextualizada à turma.
        $this->authorize('create', [Matricula::class, $turma]);

        // Valida os dados do novo aluno e da matrícula
        $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:alunos,cpf|unique:users,cpf', // CPF único para alunos e usuários
            'data_nascimento' => 'required|date',
            'endereco' => 'required|string|max:255',
            'telefone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:alunos,email|unique:users,email', // Email único para alunos e usuários
            'status_aluno' => 'required|string|in:ativo,inativo,trancada,formado', // Status para o registro do aluno
            'data_matricula' => 'nullable|date',
            'status_matricula' => 'required|string|in:ativa,trancada,cancelada,concluida', // Status para a matrícula na turma
        ]);

        // Inicia uma transação de banco de dados para garantir atomicidade
        DB::beginTransaction();

        try {
            // Cria o novo Aluno
            $aluno = Aluno::create([
                'nome' => $request->nome,
                'cpf' => $request->cpf,
                'data_nascimento' => $request->data_nascimento,
                'endereco' => $request->endereco,
                'telefone' => $request->telefone,
                'email' => $request->email,
                'status' => $request->status_aluno,
            ]);

            // Cria o Usuário para o novo Aluno (CPF como login/senha)
            $user = User::create([
                'name' => $request->nome,
                'email' => $request->email, // Usando o email do aluno para o email do usuário
                'cpf' => $request->cpf, // Usando o CPF do aluno para o CPF do usuário (login)
                'password' => Hash::make($request->cpf), // CPF como senha padrão (hashed)
                'role' => 'aluno', // Atribui o papel 'aluno'
            ]);

            // Vincula o usuário ao aluno
            $aluno->user_id = $user->id;
            $aluno->save();

            // Prepara um novo Request para chamar o método vincularAluno
            // Isso é necessário porque vincularAluno espera um objeto Request com 'aluno_id'
            $matriculaRequest = new Request([
                'aluno_id' => $aluno->id,
                'data_matricula' => $request->data_matricula,
                'status' => $request->status_matricula,
            ]);

            // Chama vincularAluno para anexar o novo aluno à turma selecionada
            // Este método agora lança exceções em caso de erro, permitindo o rollback
            $this->vincularAluno($matriculaRequest, $turma);

            DB::commit(); // Confirma a transação

            // Busca o nome do aluno e da turma para a mensagem de sucesso
            $alunoNome = $aluno->nome;
            $turmaNome = $turma->nome;

            return redirect()->route('turmas.show', $turma->id) // Redireciona para a página da turma
                             ->with('success', "Aluno \"{$alunoNome}\" criado e matriculado com sucesso na turma \"{$turmaNome}\".");

        } catch (\Exception $e) {
            DB::rollBack(); // Reverte a transação em caso de erro
            return redirect()->back()->withInput()->with('error', 'Erro ao criar aluno e matricular: ' . $e->getMessage());
        }
    }
}
