<?php

namespace App\Http\Controllers;

use App\Models\Turma;
use App\Models\Professor; // Mantenha este import se ainda usar Professor em outros métodos
use App\Models\Curso;
use App\Models\Aluno; // Adicionado para uso futuro
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Para transações de banco de dados
use Illuminate\Support\Facades\Auth; // Adicionado para consistência

class TurmaController extends Controller
{
    /**
     * Exibe a lista de turmas.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Autoriza a ação 'viewAny' na TurmaPolicy.
        // Isso verificará se o usuário logado tem permissão para listar turmas.
        // Administradores e secretárias podem ver todas. Professores podem ver suas turmas. Alunos podem ver suas turmas.
        $this->authorize('viewAny', Turma::class);

        // CORRIGIDO: Removido o carregamento do relacionamento 'professor'
        $turmas = Turma::with(['curso'])->orderBy('ano', 'desc')->orderBy('nome')->get();

        // NOTA: Se o usuário logado for um professor ou aluno, a TurmaPolicy::viewAny() pode permitir a listagem.
        // No entanto, este método 'index' atualmente busca *todas* as turmas.
        // Para um professor/aluno, você pode querer filtrar esta lista para mostrar apenas suas turmas.
        /*
        if (Auth::user()->isProfessor()) {
            $turmas = Auth::user()->turmas()->with('curso')->orderBy('ano', 'desc')->orderBy('nome')->get();
        } elseif (Auth::user()->isAluno()) {
            // Assumindo que o modelo User tem um relacionamento com Aluno, e Aluno com Turmas
            $turmas = Auth::user()->aluno->turmas()->with('curso')->orderBy('ano', 'desc')->orderBy('nome')->get();
        } else {
            $turmas = Turma::with(['curso'])->orderBy('ano', 'desc')->orderBy('nome')->get();
        }
        */

        return view('turmas.index', compact('turmas'));
    }

    /**
     * Mostra o formulário de criação de uma nova turma.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Autoriza a ação 'create' na TurmaPolicy.
        // Apenas administradores e secretárias podem criar turmas.
        $this->authorize('create', Turma::class);

        // Adicione estas duas linhas para carregar os professores
        $professores = Professor::orderBy('nome')->get();
        $cursos = Curso::orderBy('nome')->get();

        // Certifique-se de que 'professores' e 'cursos' estão no compact
        return view('turmas.create', compact('professores', 'cursos'));
    }

    /**
     * Salva uma nova turma no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Autoriza a ação 'create' na TurmaPolicy.
        // Apenas administradores e secretárias podem criar turmas.
        $this->authorize('create', Turma::class);

        // CORRIGIDO: Removida a validação para 'professor_id'
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'ano' => 'required|integer|min:1900|max:' . (date('Y') + 5), // Ano atual + 5
            'periodo' => 'nullable|string|max:50',
            'status' => 'required|string|in:ativa,inativa,concluida,cancelada',
            'curso_id' => 'required|exists:cursos,id',
        ]);

        // Cria a turma usando os dados validados
        $turma = Turma::create($validatedData);

        return redirect()->route('turmas.index')
            ->with('success', 'Turma "' . $turma->nome . '" cadastrada com sucesso!');
    }

    /**
     * Exibe os detalhes de uma turma específica.
     *
     * @param  \App\Models\Turma  $turma
     * @return \Illuminate\View\View
     */
    public function show(Turma $turma)
    {
        // Autoriza a ação 'view' na TurmaPolicy, passando a instância da turma.
        // Administradores e secretárias podem ver qualquer turma.
        // Professores podem ver suas turmas. Alunos podem ver suas turmas.
        $this->authorize('view', $turma);

        // CORRIGIDO: Removido o carregamento do relacionamento 'professor'
        $turma->load(['curso', 'alunos']);

        return view('turmas.show', compact('turma'));
    }

    /**
     * Mostra o formulário de edição de uma turma.
     *
     * @param  \App\Models\Turma  $turma
     * @return \Illuminate\View\View
     */
    public function edit(Turma $turma)
    {
        // Autoriza a ação 'update' na TurmaPolicy, passando a instância da turma.
        // Apenas administradores e secretárias podem atualizar turmas.
        $this->authorize('update', $turma);

        // CORRIGIDO: Removido o carregamento de professores, pois não é mais atribuído à turma
        $cursos = Curso::orderBy('nome')->get();

        // CORRIGIDO: Removido 'professores' do compact
        return view('turmas.edit', compact('turma', 'cursos'));
    }

    /**
     * Atualiza uma turma no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Turma  $turma
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Turma $turma)
    {
        // Autoriza a ação 'update' na TurmaPolicy, passando a instância da turma.
        // Apenas administradores e secretárias podem atualizar turmas.
        $this->authorize('update', $turma);

        // CORRIGIDO: Removida a validação para 'professor_id'
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'ano' => 'required|integer|min:1900|max:' . (date('Y') + 5),
            'periodo' => 'nullable|string|max:50',
            'status' => 'required|string|in:ativa,inativa,concluida,cancelada',
            'curso_id' => 'required|exists:cursos,id',
        ]);

        // Atualiza a turma usando os dados validados
        $turma->update($validatedData);

        return redirect()->route('turmas.index')
            ->with('success', 'Turma "' . $turma->nome . '" atualizada com sucesso!');
    }

    /**
     * Remove uma turma do banco de dados.
     *
     * @param  \App\Models\Turma  $turma
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Turma $turma)
    {
        // Autoriza a ação 'delete' na TurmaPolicy, passando a instância da turma.
        // Apenas administradores e secretárias podem deletar turmas.
        $this->authorize('delete', $turma);

        // Armazena o nome da turma antes de excluí-la
        $nomeTurma = $turma->nome;

        try {
            // Usa transação para garantir integridade dos dados
            DB::beginTransaction();

            // Verifica se há alunos matriculados
            if ($turma->alunos()->count() > 0) {
                // Desvincula os alunos da turma antes de excluí-la
                $turma->alunos()->detach();
            }

            // Exclui a turma
            $turma->delete();

            DB::commit();

            return redirect()->route('turmas.index')
                ->with('success', 'Turma "' . $turma->nome . '" removida com sucesso!'); // Corrigido para mensagem de sucesso
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('turmas.index')
                ->with('error', 'Erro ao remover a turma: ' . $e->getMessage());
        }
    }

    /**
     * Mostra o formulário para criar uma nova turma para um curso específico.
     *
     * @param  \App\Models\Curso  $curso
     * @return \Illuminate\View\View
     */
    public function createForCurso(Curso $curso)
    {
        // Autoriza a ação 'create' na TurmaPolicy.
        // Apenas administradores e secretárias podem criar turmas.
        $this->authorize('create', Turma::class); // A autorização é para criar uma Turma, não um Curso

        // REMOVIDO: $professores = Professor::orderBy('nome')->get();
        // Define valores padrão para a nova turma
        $anoAtual = date('Y');

        // REMOVIDO: 'professores' do compact
        return view('turmas.create_for_curso', compact('curso', 'anoAtual'));
    }

    /**
     * Armazena uma nova turma para um curso específico.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Curso  $curso
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeForCurso(Request $request, Curso $curso)
    {
        // Autoriza a ação 'create' na TurmaPolicy.
        // Apenas administradores e secretárias podem criar turmas.
        $this->authorize('create', Turma::class); // A autorização é para criar uma Turma

        // Validação dos dados do formulário
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'ano' => 'required|integer|min:1900|max:' . (date('Y') + 5),
            'periodo' => 'nullable|string|max:50',
            'status' => 'required|string|in:ativa,inativa,concluida,cancelada',
            // REMOVIDO: 'professor_id' => 'required|exists:professores,id',
        ]);

        // Adiciona o curso_id aos dados validados
        $validatedData['curso_id'] = $curso->id;

        // Cria a turma usando os dados validados
        $turma = Turma::create($validatedData);

        return redirect()->route('cursos.show', $curso->id)
            ->with('success', 'Turma "' . $turma->nome . '" criada com sucesso!');
    }

    /**
     * Exibe a lista de alunos que podem ser adicionados a uma turma.
     *
     * @param  \App\Models\Turma  $turma
     * @return \Illuminate\View\View
     */
    public function alunosDisponiveis(Turma $turma)
    {
        // Autoriza a ação 'update' na TurmaPolicy, pois adicionar alunos é uma forma de modificar a turma.
        // Ou você pode criar uma ação específica como 'addAluno' na Policy.
        $this->authorize('update', $turma); // Ou 'addAluno', se definido

        // Busca alunos que não estão matriculados nesta turma
        $alunosDisponiveis = Aluno::whereDoesntHave('turmas', function($query) use ($turma) {
            $query->where('turmas.id', $turma->id);
        })->orderBy('nome')->get();

        return view('turmas.alunos_disponiveis', compact('turma', 'alunosDisponiveis'));
    }
}
