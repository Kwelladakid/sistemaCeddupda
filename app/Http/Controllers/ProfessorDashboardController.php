<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Professor;
use App\Models\Disciplina; // Importe o modelo Disciplina
use App\Models\Aluno;      // Importe o modelo Aluno
use App\Models\User;      // Importe o modelo User para usar as constantes de role

class ProfessorDashboardController extends Controller
{
    /**
     * Exibe o dashboard principal do professor com suas disciplinas
     * e, opcionalmente, a lista de alunos para uma disciplina selecionada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        // 1. Verificação de Autenticação e Papel
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Você precisa estar logado para acessar esta página.');
        }

        $user = Auth::user();

        if ($user->role !== User::ROLE_TEACHER) {
            return redirect()->route('home')->with('error', 'Acesso não autorizado para esta área.');
        }

        $professor = $user->professor;

        if (!$professor) {
            return redirect()->route('home')->with('error', 'Perfil de professor não encontrado para este usuário.');
        }

        // 2. Carrega as disciplinas vinculadas a este professor
        $disciplinas = $professor->disciplinas;

        // 3. Lógica para carregar alunos se uma disciplina for selecionada
        $selectedDisciplina = null;
        $alunos = collect(); // Inicializa como uma coleção vazia para evitar erros na view

        $disciplinaId = $request->query('disciplina_id'); // Pega o ID da disciplina da query string

        if ($disciplinaId) {
            // Tenta encontrar a disciplina pelo ID
            $disciplinaParaAlunos = Disciplina::find($disciplinaId);

            // Verifica se a disciplina existe E se o professor logado leciona essa disciplina
            if ($disciplinaParaAlunos && $professor->disciplinas->contains($disciplinaParaAlunos->id)) {
                $selectedDisciplina = $disciplinaParaAlunos;

                // Carrega o curso relacionado à disciplina (necessário para buscar turmas)
                $selectedDisciplina->load('curso');

                // Verifica se a disciplina está associada a um curso
                if ($selectedDisciplina->curso) {
                    // Busca os alunos matriculados nas turmas do curso ao qual a disciplina pertence
                    // Esta lógica é adaptada do seu DisciplinaController@showNotasForm
                    $turmaIds = $selectedDisciplina->curso->turmas()->pluck('id')->toArray();

                    $alunos = Aluno::whereHas('turmas', function($query) use ($turmaIds) {
                                  $query->whereIn('turmas.id', $turmaIds);
                              })
                              ->orderBy('nome')
                              ->get();
                } else {
                    // Mensagem de erro se a disciplina não tiver curso associado
                    session()->flash('error', 'Esta disciplina não está associada a nenhum curso. Não é possível listar alunos.');
                }
            } else {
                // Mensagem de erro se a disciplina não for encontrada ou o professor não a lecionar
                session()->flash('error', 'Disciplina selecionada inválida ou você não está autorizado a visualizá-la.');
            }
        }

        // Retorna a view do dashboard do professor, passando as disciplinas e, opcionalmente, os alunos
        return view('professor.index', compact('disciplinas', 'selectedDisciplina', 'alunos'));
    }

    // O método showDisciplineStudents não é mais necessário para este fluxo e pode ser removido.
    // public function showDisciplineStudents(Disciplina $disciplina) { ... }
}
