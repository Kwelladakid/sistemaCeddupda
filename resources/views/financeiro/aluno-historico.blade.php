@extends('layouts.financeiro')

@section('content')
<div class="container">
    <h1 class="mb-4">Histórico Financeiro do Aluno</h1>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('financeiro.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Voltar ao Dashboard
        </a>

        <button onclick="window.print()" class="btn btn-primary no-print">
            <i class="fas fa-print me-1"></i> Imprimir Relatório
        </button>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Informações do Aluno</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Nome:</div>
                        <div class="col-md-8">{{ $aluno->nome }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Matrícula:</div>
                        <div class="col-md-8">{{ $aluno->matricula }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">CPF:</div>
                        <div class="col-md-8">{{ $aluno->cpf }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">E-mail:</div>
                        <div class="col-md-8">{{ $aluno->email }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Telefone:</div>
                        <div class="col-md-8">{{ $aluno->telefone }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Curso:</div>
                        <div class="col-md-8">{{ $aluno->curso->nome ?? 'Não informado' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Status:</div>
                        <div class="col-md-8">
                            @if(isset($aluno->status))
                                @if($aluno->status == 'ativo')
                                    <span class="badge bg-success">Ativo</span>
                                @elseif($aluno->status == 'inativo')
                                    <span class="badge bg-danger">Inativo</span>
                                @elseif($aluno->status == 'trancado')
                                    <span class="badge bg-warning text-dark">Trancado</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($aluno->status) }}</span>
                                @endif
                            @else
                                <span class="badge bg-secondary">Não informado</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">Resumo Financeiro</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-8 fw-bold">Total Pago:</div>
                        <div class="col-md-4 text-end">R$ {{ number_format($totalPago, 2, ',', '.') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-8 fw-bold">Mensalidades Pendentes:</div>
                        <div class="col-md-4 text-end">{{ $mensalidadesPendentes }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-8 fw-bold">Valor Pendente:</div>
                        <div class="col-md-4 text-end">R$ {{ number_format($valorPendente, 2, ',', '.') }}</div>
                    </div>

                    <hr>

                    <div class="mt-3">
                        @if($mensalidadesAtrasadas > 0)
                            <div class="alert alert-danger mb-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Este aluno possui {{ $mensalidadesAtrasadas }} mensalidade(s) atrasada(s)!
                            </div>
                        @else
                            <div class="alert alert-success mb-0">
                                <i class="fas fa-check-circle me-2"></i>
                                Situação regular. Não há mensalidades atrasadas.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center bg-success text-white">
            <h5 class="card-title mb-0">Histórico de Pagamentos</h5>
            @if(Route::has('financeiro.pagamentos.create'))
                <a href="{{ route('financeiro.pagamentos.create', ['aluno_id' => $aluno->id]) }}" class="btn btn-light btn-sm no-print">
                    <i class="fas fa-plus-circle me-1"></i> Registrar Novo Pagamento
                </a>
            @endif
        </div>
        <div class="card-body">
            @if($pagamentos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Descrição</th>
                                <th>Valor</th>
                                <th>Método</th>
                                <th>Registrado por</th>
                                <th class="text-center no-print">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pagamentos as $pagamento)
                                <tr>
                                    <td>{{ date('d/m/Y', strtotime($pagamento->data_pagamento)) }}</td>
                                    <td>{{ $pagamento->descricao ?? 'Pagamento de mensalidade' }}</td>
                                    <td>R$ {{ number_format($pagamento->valor, 2, ',', '.') }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $pagamento->metodo_pagamento)) }}</td>
                                    <td>{{ $pagamento->usuario->name ?? 'Sistema' }}</td>
                                    <td class="text-center no-print">
                                        <div class="btn-group btn-group-sm">
                                            @if(Route::has('financeiro.pagamentos.show'))
                                                <a href="{{ route('financeiro.pagamentos.show', $pagamento->id) }}"
                                                   class="btn btn-info" title="Detalhes">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif

                                            @if(Route::has('financeiro.pagamentos.recibo'))
                                                <a href="{{ route('financeiro.pagamentos.recibo', $pagamento->id) }}"
                                                   class="btn btn-success" title="Reimprimir Comprovante">
                                                    <i class="fas fa-file-invoice-dollar"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Nenhum pagamento registrado para este aluno.
                </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center bg-warning text-dark">
            <h5 class="card-title mb-0">Mensalidades</h5>
            @if(Route::has('financeiro.mensalidades.create'))
                <a href="{{ route('financeiro.mensalidades.create', ['aluno_id' => $aluno->id]) }}" class="btn btn-light btn-sm no-print">
                    <i class="fas fa-plus-circle me-1"></i> Gerar Nova Mensalidade
                </a>
            @endif
        </div>
        <div class="card-body">
            @if($mensalidades->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Mês/Ano</th>
                                <th>Vencimento</th>
                                <th>Valor</th>
                                <th>Status</th>
                                <th class="text-center no-print">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mensalidades as $mensalidade)
                                <tr class="{{ $mensalidade->status === 'pendente' && $mensalidade->data_vencimento < now() ? 'table-danger' : '' }}">
                                    <td>{{ date('m/Y', strtotime($mensalidade->data_vencimento)) }}</td>
                                    <td>{{ date('d/m/Y', strtotime($mensalidade->data_vencimento)) }}</td>
                                    <td>R$ {{ number_format($mensalidade->valor_final, 2, ',', '.') }}</td>
                                    <td>
                                        @if($mensalidade->status === 'paga')
                                            <span class="badge bg-success">Paga</span>
                                        @elseif($mensalidade->status === 'pendente' && $mensalidade->data_vencimento < now())
                                            <span class="badge bg-danger">Atrasada</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pendente</span>
                                        @endif
                                    </td>
                                    <td class="text-center no-print">
                                        @if($mensalidade->status === 'pendente')
                                            @if(Route::has('financeiro.pagamentos.create'))
                                                <a href="{{ route('financeiro.pagamentos.create', ['mensalidade_id' => $mensalidade->id]) }}"
                                                   class="btn btn-sm btn-success">
                                                    <i class="fas fa-money-bill-wave me-1"></i> Registrar Pagamento
                                                </a>
                                            @endif
                                        @else
                                            @if(Route::has('financeiro.pagamentos.show') && $mensalidade->pagamento_id)
                                                <a href="{{ route('financeiro.pagamentos.show', $mensalidade->pagamento_id) }}"
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye me-1"></i> Ver Pagamento
                                                </a>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Nenhuma mensalidade registrada para este aluno.
                </div>
            @endif
        </div>
    </div>
</div>

<style media="print">
    /* Estilos para impressão */
    .no-print {
        display: none !important;
    }

    .card {
        border: 1px solid #ddd !important;
        margin-bottom: 20px !important;
        break-inside: avoid;
    }

    .card-header {
        background-color: #f8f9fa !important;
        color: #000 !important;
        border-bottom: 1px solid #ddd !important;
        padding: 10px 15px !important;
    }

    .table {
        width: 100% !important;
        border-collapse: collapse !important;
    }

    .table th, .table td {
        border: 1px solid #ddd !important;
        padding: 8px !important;
    }

    .badge {
        border: 1px solid #000 !important;
        padding: 3px 6px !important;
        color: #000 !important;
        background-color: transparent !important;
    }

    .alert {
        border: 1px solid #000 !important;
        padding: 10px !important;
        margin-bottom: 15px !important;
    }

    /* Adiciona um cabeçalho na impressão */
    @page {
        margin: 1cm;
    }

    body::before {
        content: "Relatório Financeiro - {{ $aluno->nome }} - Gerado em {{ date('d/m/Y H:i:s') }}";
        display: block;
        text-align: center;
        font-weight: bold;
        margin-bottom: 20px;
    }
}
</style>
@endsection
