<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Pagamento;
use App\Models\Mensalidade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PagamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pagamentos = Pagamento::with(['aluno', 'usuario'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('financeiro.pagamentos.index', compact('pagamentos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request) // MODIFICADO: Adicionado Request $request
    {
        $alunos = Aluno::orderBy('nome')->get();
        $metodosPagamento = [
            'pix' => 'PIX',
            'cartao_credito' => 'Cartão de Crédito',
            'cartao_debito' => 'Cartão de Débito',
            'dinheiro' => 'Dinheiro',
            'boleto' => 'Boleto Bancário',
            'transferencia' => 'Transferência Bancária'
        ];

        // NOVO: Valores pré-preenchidos da URL
        $alunoSelecionadoId = $request->query('aluno_id');
        $valorPreenchido = $request->query('valor');
        $dataPreenchida = $request->query('data_pagamento', Carbon::now()->format('Y-m-d')); // Data atual como padrão

        return view('financeiro.pagamentos.create', compact(
            'alunos',
            'metodosPagamento',
            'alunoSelecionadoId', // NOVO: Passando para a view
            'valorPreenchido',    // NOVO: Passando para a view
            'dataPreenchida'      // NOVO: Passando para a view
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'aluno_id' => 'required|exists:alunos,id',
            'valor' => 'required|numeric|min:0',
            'metodo_pagamento' => 'required|string',
            'data_pagamento' => 'required|date',
            'observacao' => 'nullable|string',
            'mensalidade_id' => 'nullable|exists:mensalidades,id',
        ]);

        // Adicione o status manualmente
        $validated['status'] = 'confirmado';

        // Verifica se existe um usuário padrão, se não, cria
        $userId = $this->getOrCreateDefaultUser();
        $validated['user_id'] = $userId;

        // Remove mensalidade_id do array validated para não causar erro ao criar o pagamento
        $mensalidadeId = $validated['mensalidade_id'] ?? null;
        unset($validated['mensalidade_id']);

        // Cria o pagamento
        $pagamento = Pagamento::create($validated);

        // Se uma mensalidade foi selecionada, vincula ela ao pagamento
        if ($mensalidadeId) {
            $mensalidade = Mensalidade::find($mensalidadeId);
            if ($mensalidade) {
                $mensalidade->pagamento_id = $pagamento->id;
                $mensalidade->status = 'pago';
                $mensalidade->data_pagamento = $validated['data_pagamento'];
                $mensalidade->save();
            }
        }

        // Adiciona mensagem de sucesso à sessão flash para ser exibida após o download
        session()->flash('success', 'Pagamento registrado com sucesso!');

        // Redireciona para a página de comprovante
        return redirect()->route('financeiro.pagamentos.comprovante', $pagamento->id);
    }

    /**
     * Generate and download the payment receipt
     * Renomeado de 'comprovante' para 'gerarComprovante' para corresponder à rota
     */
    public function gerarComprovante(Pagamento $pagamento)
    {
        // Recupera o aluno associado ao pagamento
        $aluno = $pagamento->aluno;

        // Recupera as mensalidades vinculadas a este pagamento (se houver)
        $mensalidadesVinculadas = Mensalidade::where('pagamento_id', $pagamento->id)->get();

        // Carrega a view do comprovante e passa os dados
        $pdf = Pdf::loadView('financeiro.comprovantes.pagamento', [
            'pagamento' => $pagamento,
            'aluno' => $aluno,
            'mensalidades' => $mensalidadesVinculadas
        ]);

        // Nome do arquivo para download
        $nomeArquivo = 'comprovante_pagamento_' . $pagamento->id . '_' . Carbon::now()->format('YmdHis') . '.pdf';

        // Retorna o PDF para download
        return $pdf->download($nomeArquivo);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pagamento $pagamento)
    {
        // Carrega o relacionamento 'aluno' para que possamos acessar os dados do aluno na view.
        // Isso é crucial para a sua necessidade de "pagamentos correspondentes apenas ao aluno correspondente".
        $pagamento->load('aluno'); // <--- LINHA ADICIONADA AQUI

        return view('financeiro.pagamentos.show', compact('pagamento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pagamento $pagamento)
    {
        $alunos = Aluno::orderBy('nome')->get();
        $metodosPagamento = [
            'pix' => 'PIX',
            'cartao_credito' => 'Cartão de Crédito',
            'cartao_debito' => 'Cartão de Débito',
            'dinheiro' => 'Dinheiro',
            'boleto' => 'Boleto Bancário',
            'transferencia' => 'Transferência Bancária'
        ];

        return view('financeiro.pagamentos.edit', compact('pagamento', 'alunos', 'metodosPagamento'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pagamento $pagamento)
    {
        $validated = $request->validate([
            'aluno_id' => 'required|exists:alunos,id',
            'valor' => 'required|numeric|min:0',
            'metodo_pagamento' => 'required|string',
            'data_pagamento' => 'required|date',
            'observacao' => 'nullable|string'
        ]);

        $pagamento->update($validated);

        return redirect()->route('financeiro.pagamentos.index')
            ->with('success', 'Pagamento atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pagamento $pagamento)
    {
        // Verificar se há mensalidades vinculadas a este pagamento
        $mensalidades = Mensalidade::where('pagamento_id', $pagamento->id)->get();

        // Atualizar mensalidades para não terem mais este pagamento
        foreach ($mensalidades as $mensalidade) {
            $mensalidade->status = 'pendente';
            $mensalidade->pagamento_id = null;
            $mensalidade->save();
        }

        $pagamento->delete();

        return redirect()->route('financeiro.pagamentos.index')
            ->with('success', 'Pagamento removido com sucesso!');
    }

    /**
     * Listar mensalidades pendentes de um aluno para vincular ao pagamento.
     */
    public function getMensalidadesPendentes(Request $request)
    {
        $alunoId = $request->input('aluno_id');

        if (!$alunoId) {
            return response()->json(['mensalidades' => []]);
        }

        $mensalidades = Mensalidade::where('aluno_id', $alunoId)
            ->where('status', 'pendente')
            ->orderBy('data_vencimento')
            ->get();

        return response()->json(['mensalidades' => $mensalidades]);
    }

    /**
     * Obtém ou cria um usuário padrão para operações do sistema
     */
    private function getOrCreateDefaultUser()
    {
        // Verifica se já existe um usuário com ID 1
        $user = User::find(1);

        if (!$user) {
            // Se não existir, cria um usuário padrão
            $user = User::create([
                'name' => 'Sistema',
                'email' => 'sistema@escolatecnica.local',
                'password' => bcrypt(Str::random(16)), // Usando Str::random() em vez de str_random()
            ]);
        }

        return $user->id;
    }
}
