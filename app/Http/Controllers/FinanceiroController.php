<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Mensalidade;
use App\Models\Pagamento;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class FinanceiroController extends Controller
{
    public function index()
    {
        // Autoriza a ação 'viewDashboard' na FinanceiroPolicy.
        // Apenas administradores e secretárias podem acessar o dashboard financeiro.
        //$this->authorize('viewDashboard', FinanceiroController::class);

        // Dados para dashboard financeiro
        $totalPagamentosHoje = Pagamento::whereDate('data_pagamento', Carbon::today())->sum('valor');

        $mensalidadesVencidas = Mensalidade::where('status', 'pendente')
            ->whereDate('data_vencimento', '<', Carbon::today())
            ->count();

        $ultimosPagamentos = Pagamento::with('aluno')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $alunosInadimplentes = Aluno::whereHas('mensalidades', function ($query) {
            $query->where('status', 'pendente')
                  ->where('data_vencimento', '<', Carbon::today());
        })->count();

        // Adicione essa linha para buscar os pagamentos recentes
        $pagamentos = Pagamento::with(['aluno', 'usuario'])
            ->orderBy('data_pagamento', 'desc')
            ->limit(10)
            ->get();

        // Gráfico de pagamentos mensais (placeholder)
        $mesesNomes = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        $mesAtual = (int)Carbon::now()->format('m');
        $ultimosMeses = [];
        $valoresMensais = [];

        // Preparar dados para últimos 6 meses
        for ($i = 5; $i >= 0; $i--) {
            $mes = ($mesAtual - $i) <= 0 ? ($mesAtual - $i + 12) : ($mesAtual - $i);
            $ano = ($mesAtual - $i) <= 0 ? Carbon::now()->year - 1 : Carbon::now()->year;
            $ultimosMeses[] = $mesesNomes[$mes - 1] . '/' . substr($ano, 2, 2);

            // Em uma implementação real, isso viria do banco de dados
            $valoresMensais[] = rand(3000, 8000);
        }

        return view('financeiro.index', [
            'totalPagamentosHoje' => $totalPagamentosHoje,
            'mensalidadesVencidas' => $mensalidadesVencidas,
            'ultimosPagamentos' => $ultimosPagamentos,
            'alunosInadimplentes' => $alunosInadimplentes,
            'ultimosMeses' => $ultimosMeses,
            'valoresMensais' => $valoresMensais,
            'pagamentos' => $pagamentos // Adicionando a variável pagamentos
        ]);
    }

    /**
     * Exibe a página de pagamentos com opção de busca por CPF.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function pagamentosIndex(Request $request)
    {
        // Autoriza a ação 'viewPayments' na FinanceiroPolicy.
        // Apenas administradores e secretárias podem visualizar os pagamentos.
        //$this->authorize('viewPayments', FinanceiroController::class);

        // Buscar pagamentos recentes
        $pagamentos = Pagamento::with(['aluno', 'usuario'])
            ->orderBy('data_pagamento', 'desc')
            ->paginate(10);

        // Dados para o gráfico de receitas
        $mesesNomes = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        $mesAtual = (int)Carbon::now()->format('m');
        $ultimosMeses = [];
        $valoresMensais = [];

        // Preparar dados para últimos 6 meses
        for ($i = 5; $i >= 0; $i--) {
            $mes = ($mesAtual - $i) <= 0 ? ($mesAtual - $i + 12) : ($mesAtual - $i);
            $ano = ($mesAtual - $i) <= 0 ? Carbon::now()->year - 1 : Carbon::now()->year;
            $ultimosMeses[] = $mesesNomes[$mes - 1] . '/' . substr($ano, 2, 2);

            // Em uma implementação real, isso viria do banco de dados
            $valoresMensais[] = rand(3000, 8000);
        }

        // Último pagamento para o botão de comprovante
        $ultimoPagamento = Pagamento::orderBy('created_at', 'desc')->first();

        return view('financeiro.pagamentos.index', [
            'pagamentos' => $pagamentos,
            'ultimosMeses' => $ultimosMeses,
            'valoresMensais' => $valoresMensais,
            'ultimoPagamento' => $ultimoPagamento
        ]);
    }

    /**
     * Exibe a lista de alunos para pagamento rápido de mensalidades.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function mensalidadesIndex(Request $request)
    {
        // Autoriza a ação 'viewMensalidades' na FinanceiroPolicy.
        // Apenas administradores e secretárias podem visualizar as mensalidades.
        $this->authorize('viewMensalidades', FinanceiroController::class);

        // Buscar todos os alunos ordenados por nome
        $alunos = Aluno::orderBy('nome')
            ->paginate(15); // Paginação para não sobrecarregar a página

        // Valor padrão da mensalidade
        $valorMensalidade = 170.00;

        // Data atual para pré-preencher o formulário
        $dataAtual = Carbon::now()->format('Y-m-d');

        return view('financeiro.mensalidades.index', [
            'alunos' => $alunos,
            'valorMensalidade' => $valorMensalidade,
            'dataAtual' => $dataAtual
        ]);
    }

    /**
     * Busca alunos pelo nome, matrícula ou CPF e exibe seu histórico financeiro.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function buscarAlunos(Request $request)
    {
        // Autoriza a ação 'searchStudents' na FinanceiroPolicy.
        //$this->authorize('searchStudents', FinanceiroController::class);

        $termo = $request->input('termo');

        if (empty($termo)) {
            return redirect()->route('financeiro.index')
                             ->with('error', 'Por favor, informe um termo de busca.');
        }

        // Verificar se o termo parece ser um CPF formatado
        $isCpfFormatado = preg_match('/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', $termo);

        // Verificar se o termo parece ser um CPF sem formatação
        $isCpfSemFormato = preg_match('/^\d{11}$/', $termo);

        // Buscar aluno com base no tipo de termo
        $aluno = null;

        if ($isCpfFormatado) {
            // Buscar por CPF formatado
            $aluno = Aluno::where('cpf', $termo)->first();
        } elseif ($isCpfSemFormato) {
            // Formatar o CPF para busca
            $cpfFormatado = substr($termo, 0, 3) . '.' .
                            substr($termo, 3, 3) . '.' .
                            substr($termo, 6, 3) . '-' .
                            substr($termo, 9, 2);
            $aluno = Aluno::where('cpf', $cpfFormatado)->first();
        } else {
            // Buscar por nome ou matrícula
            $aluno = Aluno::where('nome', 'like', "%{$termo}%")
                          ->orWhere('matricula', $termo)
                          ->first();
        }

        // Se não encontrar nenhum aluno, redirecionamos com mensagem
        if (!$aluno) {
            return redirect()->route('financeiro.index')
                             ->with('error', 'Nenhum aluno encontrado com o termo informado.');
        }

        // Buscar mensalidades e pagamentos do aluno
        $mensalidades = Mensalidade::where('aluno_id', $aluno->id)
                                  ->orderBy('data_vencimento', 'desc')
                                  ->get();

        $pagamentos = Pagamento::where('aluno_id', $aluno->id)
                              ->with('usuario') // Carrega o usuário que registrou o pagamento
                              ->orderBy('data_pagamento', 'desc')
                              ->get();

        // Calcular totais para o resumo financeiro
        $totalPago = $pagamentos->sum('valor');
        $mensalidadesPendentes = $mensalidades->where('status', 'pendente')->count();
        $valorPendente = $mensalidades->where('status', 'pendente')->sum('valor_final');
        $mensalidadesAtrasadas = $mensalidades->where('status', 'pendente')
                                             ->where('data_vencimento', '<', Carbon::today())
                                             ->count();

        return view('financeiro.aluno-historico', [
            'aluno' => $aluno,
            'mensalidades' => $mensalidades,
            'pagamentos' => $pagamentos,
            'totalPago' => $totalPago,
            'mensalidadesPendentes' => $mensalidadesPendentes,
            'valorPendente' => $valorPendente,
            'mensalidadesAtrasadas' => $mensalidadesAtrasadas
        ]);
    }

    public function relatorios()
    {
        // Autoriza a ação 'viewReports' na FinanceiroPolicy.
        //$this->authorize('viewReports', FinanceiroController::class);

        return view('financeiro.relatorios');
    }

    public function alunoFinanceiro($id)
    {
        // Autoriza a ação 'viewStudentFinance' na FinanceiroPolicy.
        //$this->authorize('viewStudentFinance', FinanceiroController::class);

        // Buscar o aluno pelo ID
        $aluno = Aluno::findOrFail($id);

        // Buscar mensalidades e pagamentos do aluno
        $mensalidades = Mensalidade::where('aluno_id', $id)
                                  ->orderBy('data_vencimento', 'desc')
                                  ->get();

        $pagamentos = Pagamento::where('aluno_id', $id)
                              ->with('usuario')
                              ->orderBy('data_pagamento', 'desc')
                              ->get();

        return view('financeiro.aluno', [
            'aluno' => $aluno,
            'mensalidades' => $mensalidades,
            'pagamentos' => $pagamentos
        ]);
    }
}
