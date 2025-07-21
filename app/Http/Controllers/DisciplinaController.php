<?php

namespace App\Http\Controllers;

use App\Models\Disciplina;
use App\Models\Curso;
use App\Models\Professor;
use App\Models\Aluno; // NOVO: Adicione esta linha para usar o modelo Aluno
use App\Models\Nota; // NOVO: Adicione esta linha para usar o modelo Nota
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Adicionado para consistência

class DisciplinaController extends Controller
{
    /**
     * Exibe a lista de disciplinas.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Autoriza a ação 'viewAny' na DisciplinaPolicy.
        // Administradores e secretárias podem ver todas. Professores podem ver as que lecionam. Alunos podem ver as que cursam.
        $this->authorize('viewAny', Disciplina::class);

        // MODIFICADO: Carrega o relacionamento com 'professor'
        $disciplinas = Disciplina::with(['curso', 'professor'])->orderBy('nome')->get();

        // NOTA: Similar a AlunoController e TurmaController, se a Policy permitir que professores/alunos vejam,
        // você pode querer filtrar esta lista para mostrar apenas as disciplinas relevantes para o usuário logado.
        /*
        if (Auth::user()->isProfessor()) {
            $disciplinas = Auth::user()->professor->disciplinas()->with(['curso', 'professor'])->orderBy('nome')->get();
        } elseif (Auth::user()->isAluno()) {
            // Assumindo que User tem um relacionamento com Aluno, e Aluno com Turmas, e Turmas com Disciplinas
            $disciplinas = Auth::user()->aluno->turmas->flatMap->disciplinas->unique('id')->with(['curso', 'professor'])->orderBy('nome')->get();
        } else {
            $disciplinas = Disciplina::with(['curso', 'professor'])->orderBy('nome')->get();
        }
        */

        return view('disciplinas.index', compact('disciplinas'));
    }

    /**
     * Mostra o formulário de criação de uma nova disciplina.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Autoriza a ação 'create' na DisciplinaPolicy.
        // Apenas administradores e secretárias podem criar disciplinas.
        $this->authorize('create', Disciplina::class);

        $cursos = Curso::orderBy('nome')->get();
        $modulos = [1, 2, 3]; // Módulos fixos
        // NOVO: Busca todos os professores para o dropdown
        $professores = Professor::orderBy('nome')->get();

        // MODIFICADO: Passa 'professores' para a view
        return view('disciplinas.create', compact('cursos', 'modulos', 'professores'));
    }

    /**
     * Salva uma nova disciplina no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Autoriza a ação 'create' na DisciplinaPolicy.
        // Apenas administradores e secretárias podem criar disciplinas.
        $this->authorize('create', Disciplina::class);

        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'carga_horaria' => 'required|integer|min:1',
            'modulo' => 'required|integer|in:1,2,3',
            'curso_id' => 'nullable|exists:cursos,id',
            'professor_id' => 'required|exists:professores,id', // NOVO: Validação para professor_id
        ]);

        $disciplina = Disciplina::create($validatedData);

        return redirect()->route('disciplinas.index')
            ->with('success', 'Disciplina "' . $disciplina->nome . '" cadastrada com sucesso!');
    }

    /**
     * Exibe os detalhes de uma disciplina específica.
     *
     * @param  \App\Models\Disciplina  $disciplina
     * @return \Illuminate\View\View
     */
    public function show(Disciplina $disciplina)
    {
        // Autoriza a ação 'view' na DisciplinaPolicy, passando a instância da disciplina.
        // Administradores e secretárias podem ver qualquer disciplina.
        // Professores podem ver as que lecionam. Alunos podem ver as que cursam.
        $this->authorize('view', $disciplina);

        // MODIFICADO: Carrega o relacionamento com 'professor'
        $disciplina->load(['curso', 'professor']);
        return view('disciplinas.show', compact('disciplina'));
    }

    /**
     * Mostra o formulário de edição de uma disciplina.
     *
     * @param  \App\Models\Disciplina  $disciplina
     * @return \Illuminate\View\View
     */
    public function edit(Disciplina $disciplina)
    {
        // Autoriza a ação 'update' na DisciplinaPolicy, passando a instância da disciplina.
        // Apenas administradores e secretárias podem atualizar disciplinas.
        $this->authorize('update', $disciplina);

        $cursos = Curso::orderBy('nome')->get();
        $modulos = [1, 2, 3]; // Módulos fixos
        // NOVO: Busca todos os professores para o dropdown
        $professores = Professor::orderBy('nome')->get();

        // MODIFICADO: Passa 'professores' para a view
        return view('disciplinas.edit', compact('disciplina', 'cursos', 'modulos', 'professores'));
    }

    /**
     * Atualiza uma disciplina no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Disciplina  $disciplina
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Disciplina $disciplina)
    {
        // Autoriza a ação 'update' na DisciplinaPolicy, passando a instância da disciplina.
        // Apenas administradores e secretárias podem atualizar disciplinas.
        $this->authorize('update', $disciplina);

        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'carga_horaria' => 'required|integer|min:1',
            'modulo' => 'required|integer|in:1,2,3',
            'curso_id' => 'nullable|exists:cursos,id',
            'professor_id' => 'required|exists:professores,id', // NOVO: Validação para professor_id
        ]);

        $disciplina->update($validatedData);

        return redirect()->route('disciplinas.index')
            ->with('success', 'Disciplina "' . $disciplina->nome . '" atualizada com sucesso!');
    }

    /**
     * Remove uma disciplina do banco de dados.
     *
     * @param  \App\Models\Disciplina  $disciplina
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Disciplina $disciplina)
    {
        // Autoriza a ação 'delete' na DisciplinaPolicy, passando a instância da disciplina.
        // Apenas administradores e secretárias podem deletar disciplinas.
        $this->authorize('delete', $disciplina);

        $nomeDisciplina = $disciplina->nome;
        $disciplina->delete();
        return redirect()->route('disciplinas.index')
            ->with('success', 'Disciplina "' . $nomeDisciplina . '" removida com sucesso!');
    }

    /**
     * Mostra o formulário para criar uma nova disciplina para um curso específico.
     *
     * @param  \App\Models\Curso  $curso
     * @return \Illuminate\View\View
     */
    public function createForCurso(Curso $curso)
    {
        // Autoriza a ação 'create' na DisciplinaPolicy.
        // Apenas administradores e secretárias podem criar disciplinas.
        $this->authorize('create', Disciplina::class);

        $modulos = [1, 2, 3]; // Módulos fixos
        // NOVO: Busca todos os professores para o dropdown
        $professores = Professor::orderBy('nome')->get();

        // MODIFICADO: Passa 'professores' para a view
        return view('disciplinas.create_for_curso', compact('curso', 'modulos', 'professores'));
    }

    /**
     * Armazena uma nova disciplina para um curso específico.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Curso  $curso
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeForCurso(Request $request, Curso $curso)
    {
        // Autoriza a ação 'create' na DisciplinaPolicy.
        // Apenas administradores e secretárias podem criar disciplinas.
        $this->authorize('create', Disciplina::class);

        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'carga_horaria' => 'required|integer|min:1',
            'modulo' => 'required|integer|in:1,2,3',
            'professor_id' => 'required|exists:professores,id', // NOVO: Validação para professor_id
        ]);

        $validatedData['curso_id'] = $curso->id;

        $disciplina = Disciplina::create($validatedData);

        return redirect()->route('cursos.show', $curso->id)
            ->with('success', 'Disciplina "' . $disciplina->nome . '" criada com sucesso para o curso "' . $curso->nome . '".');
    }

    /**
     * Mostra o formulário para atribuir notas e faltas aos alunos.
     *
     * @param  int  $disciplinaId
     * @return \Illuminate\View\View
     */
    public function showNotasForm($disciplinaId)
    {
        $disciplina = Disciplina::findOrFail($disciplinaId);

        // Autoriza a ação 'assignNotes' na DisciplinaPolicy, passando a instância da disciplina.
        // Apenas o professor responsável pela disciplina, administradores e secretárias podem atribuir notas.
        $this->authorize('assignNotes', $disciplina);

        // Carrega o curso e o professor relacionados à disciplina
        $disciplina->load(['curso', 'professor']);

        // Verifica se a disciplina está associada a um curso
        if (!$disciplina->curso) {
            return redirect()->route('disciplinas.show', $disciplina->id)
                ->with('error', 'Esta disciplina não está associada a nenhum curso. Não é possível atribuir notas.');
        }

        // MODIFICADO: Busca os alunos através das matrículas nas turmas do curso
        // 1. Obtém os IDs das turmas do curso
        $turmaIds = $disciplina->curso->turmas()->pluck('id')->toArray();

        // 2. Busca os alunos matriculados nessas turmas
        $alunos = Aluno::whereHas('turmas', function($query) use ($turmaIds) {
                      $query->whereIn('turmas.id', $turmaIds);
                  })
                  ->orderBy('nome')
                  ->get();

        // Busca as notas existentes para esta disciplina, indexadas pelo ID do aluno
        $notasExistentes = Nota::where('disciplina_id', $disciplina->id)->get()->keyBy('aluno_id');

        return view('disciplinas.notas', compact('disciplina', 'alunos', 'notasExistentes'));
    }

    /**
     * Salva as notas e faltas dos alunos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $disciplinaId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeNotas(Request $request, $disciplinaId)
    {
        $disciplina = Disciplina::findOrFail($disciplinaId);

        // Autoriza a ação 'assignNotes' na DisciplinaPolicy, passando a instância da disciplina.
        // Apenas o professor responsável pela disciplina, administradores e secretárias podem atribuir notas.
        $this->authorize('assignNotes', $disciplina);

        // Validação dos dados
        $request->validate([
            'notas' => 'required|array',
            'notas.*.aluno_id' => 'required|exists:alunos,id',
            'notas.*.nota' => 'nullable|numeric|min:0|max:10', // Nota pode ser nula se não for atribuída
            'notas.*.faltas' => 'nullable|integer|min:0', // Faltas podem ser nulas ou 0
        ]);

        // Processa cada nota enviada
        foreach ($request->notas as $dadosNota) {
            // Usa updateOrCreate para criar ou atualizar a nota/falta para o aluno na disciplina
            Nota::updateOrCreate(
                [
                    'disciplina_id' => $disciplina->id,
                    'aluno_id' => $dadosNota['aluno_id'],
                ],
                [
                    'nota' => $dadosNota['nota'],
                    'faltas' => $dadosNota['faltas'],
                ]
            );
        }

        return redirect()->route('disciplinas.show', $disciplina->id)
            ->with('success', 'Notas e faltas atribuídas com sucesso!');
    }
}
