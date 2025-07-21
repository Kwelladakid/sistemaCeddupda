<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use App\Models\Aluno;
use App\Models\Disciplina;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // Importação essencial da classe base Controller
use Barryvdh\DomPDF\Facade\Pdf; // Importar a Facade do DomPDF
use Illuminate\Support\Str; // <--- ADICIONE ESTA LINHA
use Illuminate\Support\Facades\Auth; // Adicionado para consistência

class NotaController extends Controller
{
    /**
     * Mostra o formulário para registrar uma nova nota para um aluno em uma disciplina.
     * @param Aluno $aluno
     * @return \Illuminate\View\View
     */
    public function create(Aluno $aluno)
    {
        // Autoriza a ação 'create' na NotaPolicy.
        // Passamos a classe Nota e a instância do Aluno para a Policy,
        // pois a permissão de criar uma nota é contextual ao aluno.
        $this->authorize('create', [Nota::class, $aluno]);

        $disciplinas = Disciplina::all();
        // CORRIGIDO: Aponta para a view correta dentro da pasta 'alunos/notas'
        return view('alunos.notas.create', compact('aluno', 'disciplinas'));
    }

    /**
     * Salva ou atualiza uma nota no banco de dados.
     * Usa updateOrCreate para evitar duplicidade e simplificar a lógica.
     * @param Request $request
     * @param Aluno $aluno
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Aluno $aluno)
    {
        // Autoriza a ação 'create' na NotaPolicy, contextualizada ao aluno.
        $this->authorize('create', [Nota::class, $aluno]);

        $request->validate([
            'disciplina_id' => 'required|exists:disciplinas,id',
            'nota' => 'required|numeric|min:0|max:10', // Notas de 0 a 10
        ]);

        Nota::updateOrCreate(
            [
                'aluno_id' => $aluno->id,
                'disciplina_id' => $request->disciplina_id,
            ],
            [
                'nota' => $request->nota,
            ]
        );

        return redirect()->route('alunos.boletim', $aluno->id)
            ->with('success', 'Nota registrada/atualizada com sucesso!');
    }

    /**
     * Mostra o formulário para editar uma nota existente.
     * @param Aluno $aluno
     * @param Nota $nota
     * @return \Illuminate\View\View
     */
    public function edit(Aluno $aluno, Nota $nota)
    {
        // Garante que a nota pertence ao aluno correto
        if ($nota->aluno_id !== $aluno->id) {
            abort(404); // Ou redirecione com erro
        }
        // Autoriza a ação 'update' na NotaPolicy, passando a instância da nota.
        $this->authorize('update', $nota);

        $disciplinas = Disciplina::all();
        // CORRIGIDO: Aponta para a view correta dentro da pasta 'alunos/notas'
        return view('alunos.notas.edit', compact('aluno', 'nota', 'disciplinas'));
    }

    /**
     * Atualiza uma nota existente no banco de dados.
     * @param Request $request
     * @param Aluno $aluno
     * @param Nota $nota
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Aluno $aluno, Nota $nota)
    {
        // Garante que a nota pertence ao aluno correto
        if ($nota->aluno_id !== $aluno->id) {
            abort(404); // Ou redirecione com erro
        }
        // Autoriza a ação 'update' na NotaPolicy, passando a instância da nota.
        $this->authorize('update', $nota);

        $request->validate([
            'disciplina_id' => 'required|exists:disciplinas,id',
            'nota' => 'required|numeric|min:0|max:10',
        ]);

        // Verifica se a disciplina da nota está sendo alterada para uma já existente para o aluno
        $existingNote = Nota::where('aluno_id', $aluno->id)
                            ->where('disciplina_id', $request->disciplina_id)
                            ->first();

        if ($existingNote && $existingNote->id !== $nota->id) {
            return redirect()->back()->withErrors(['disciplina_id' => 'Já existe uma nota para esta disciplina para este aluno.']);
        }

        $nota->update($request->all());

        return redirect()->route('alunos.boletim', $aluno->id)
            ->with('success', 'Nota atualizada com sucesso!');
    }

    /**
     * Remove uma nota do banco de dados.
     * @param Aluno $aluno
     * @param Nota $nota
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Aluno $aluno, Nota $nota)
    {
        // Garante que a nota pertence ao aluno correto
        if ($nota->aluno_id !== $aluno->id) {
            abort(404); // Ou redirecione com erro
        }
        // Autoriza a ação 'delete' na NotaPolicy, passando a instância da nota.
        $this->authorize('delete', $nota);

        $nota->delete();
        return redirect()->route('alunos.boletim', $aluno->id)
            ->with('success', 'Nota removida com sucesso!');
    }

    /**
     * Exibe o boletim do aluno.
     * @param Aluno $aluno
     * @return \Illuminate\View\View
     */
    public function boletim(Aluno $aluno)
    {
        // Autoriza a ação 'view' na NotaPolicy, contextualizada ao aluno.
        // Isso permite que o aluno veja seu próprio boletim, ou que professores/admins vejam.
        $this->authorize('view', $aluno);

        $notas = $aluno->notas()->with('disciplina')->get();
        $notaMinima = 7.0; // Nota mínima para aprovação
        $mediaGeral = $aluno->calcularMediaGeral();
        $disciplinasAprovadas = $aluno->contarDisciplinasAprovadas($notaMinima);
        $disciplinasReprovadas = $aluno->contarDisciplinasReprovadas($notaMinima);

        return view('alunos.boletim', compact(
            'aluno',
            'notas',
            'notaMinima',
            'mediaGeral',
            'disciplinasAprovadas',
            'disciplinasReprovadas'
        ));
    }

    public function gerarBoletimPdf(Aluno $aluno)
    {
        // Autoriza a ação 'view' na NotaPolicy, contextualizada ao aluno.
        $this->authorize('view', $aluno);

        // Reutiliza a lógica de busca de dados do método boletim
        $notas = $aluno->notas()->with('disciplina')->get();
        $notaMinima = 7.0;
        $mediaGeral = $aluno->calcularMediaGeral();
        $disciplinasAprovadas = $aluno->contarDisciplinasAprovadas($notaMinima);
        $disciplinasReprovadas = $aluno->contarDisciplinasReprovadas($notaMinima);

        // Carrega a view específica para o PDF e passa os dados
        $pdf = Pdf::loadView('alunos.boletim_pdf', compact(
            'aluno',
            'notas',
            'notaMinima',
            'mediaGeral',
            'disciplinasAprovadas',
            'disciplinasReprovadas'
        ));

        // Retorna o PDF para download com um nome de arquivo significativo
        return $pdf->download('boletim_' . Str::slug($aluno->nome) . '.pdf');
        // Ou, para visualizar no navegador: return $pdf->stream('boletim_' . Str::slug($aluno->nome) . '.pdf');
    }
}
