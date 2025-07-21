@extends('layouts.app')

{{-- REMOVIDO: @push('styles') e todo o conteúdo de estilos --}}
{{-- REMOVIDO: @push('scripts') e todo o conteúdo de scripts (serão movidos para layouts/app.blade.php) --}}

@section('content')
{{-- Alterado de 'container' para 'container-fluid' para consistência com o layout principal --}}
<div class="container-fluid">
    <h1 class="mb-4">Dashboard Financeiro</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <h5 class="card-title">Pagamentos Hoje</h5>
                    {{-- Assumindo que $totalPagamentosHoje está sendo passado pelo controller --}}
                    <h2 class="card-text">R\$ {{ number_format($totalPagamentosHoje ?? 0, 2, ',', '.') }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-danger text-white h-100">
                <div class="card-body">
                    <h5 class="card-title">Mensalidades Vencidas</h5>
                    {{-- Assumindo que $mensalidadesVencidas está sendo passado pelo controller --}}
                    <h2 class="card-text">{{ $mensalidadesVencidas ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark h-100">
                <div class="card-body">
                    <h5 class="card-title">Alunos Inadimplentes</h5>
                    {{-- Assumindo que $alunosInadimplentes está sendo passado pelo controller --}}
                    <h2 class="card-text">{{ $alunosInadimplentes ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <h5 class="card-title">Ações</h5>
                    <div class="d-grid gap-2">
                        {{-- Rota corrigida: financeiro.pagamentos.create --}}
                        <a href="{{ route('financeiro.pagamentos.create') }}" class="btn btn-light mb-2">Novo Pagamento</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    Buscar Aluno
                </div>
                <div class="card-body">
                    {{-- Rota corrigida: financeiro.buscar-alunos --}}
                    <form action="{{ route('financeiro.buscar-alunos') }}" method="GET">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Nome, matrícula ou CPF" name="termo" required>
                            <button class="btn btn-primary" type="submit">Buscar</button>
                        </div>
                    </form>

                    <hr>
                    <h5 class="card-title">Ações Rápidas</h5>
                    <div class="list-group mt-3">
                        {{-- REMOVIDO: Rota para gerar mensalidades em lote --}}
                        <a href="{{ route('financeiro.relatorios', ['tipo' => 'inadimplencia']) }}" class="list-group-item list-group-item-action">
                             <i class="fas fa-exclamation-circle me-2"></i> Ver Alunos Inadimplentes
                        </a>
                        {{-- Rota corrigida: financeiro.pagamentos.index --}}
                        <a href="{{ route('financeiro.pagamentos.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-money-bill-wave me-2"></i> Histórico de Pagamentos
                        </a>
                         {{-- Rota corrigida: financeiro.mensalidades.index --}}
                        <a href="{{ route('financeiro.mensalidades.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-file-invoice me-2"></i> Gerenciar Mensalidades
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    Últimos Pagamentos
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Aluno</th>
                                    <th>Data</th>
                                    <th>Valor</th>
                                    <th>Método</th>
                                     <th>Ações</th> {{-- Adicionando coluna para ações na lista de últimos pagamentos --}}
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Assumindo que $ultimosPagamentos está sendo passado pelo controller --}}
                                @forelse($ultimosPagamentos ?? [] as $pagamento)
                                    <tr>
                                        <td>
                                            {{-- MODIFICADO: Agora aponta para a página de detalhes do aluno --}}
                                            <a href="{{ route('alunos.show', $pagamento->aluno->id) }}">
                                                {{ $pagamento->aluno->nome ?? 'Aluno Desconhecido' }}
                                            </a>
                                        </td>
                                        <td>{{ $pagamento->data_pagamento->format('d/m/Y') ?? 'N/A' }}</td>
                                        <td>R\$ {{ number_format($pagamento->valor ?? 0, 2, ',', '.') }}</td>
                                        <td>{{ $pagamento->getMetodoPagamentoFormatado() ?? 'N/A' }}</td>
                                         <td>
                                             <div class="btn-group btn-group-sm">
                                                 {{-- Rota corrigida: financeiro.pagamentos.show --}}
                                                <a href="{{ route('financeiro.pagamentos.show', $pagamento->id) }}" class="btn btn-info" title="Detalhes">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                 {{-- Modificado: Agora usa a rota de comprovante --}}
                                                <a href="{{ route('financeiro.pagamentos.comprovante', $pagamento->id) }}" class="btn btn-success" title="Baixar Comprovante">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                             </div>
                                         </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Nenhum pagamento registrado recentemente.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Rota corrigida: financeiro.pagamentos.index --}}
                    <a href="{{ route('financeiro.pagamentos.index') }}" class="btn btn-outline-primary mt-2">
                        Ver Todos os Pagamentos
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    Receitas dos Últimos 6 Meses
                </div>
                <div class="card-body">
                    {{-- A tag canvas precisa de uma altura/largura ou ser responsiva via CSS --}}
                    <canvas id="graficoReceitas" width="400" height="150"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Relatórios
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                             {{-- Rota corrigida: financeiro.relatorios com parâmetro --}}
                            <a href="{{ route('financeiro.relatorios', ['tipo' => 'pagamentos']) }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-chart-line me-2"></i> Relatório de Pagamentos
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                             {{-- Rota corrigida: financeiro.relatorios com parâmetro --}}
                            <a href="{{ route('financeiro.relatorios', ['tipo' => 'inadimplencia']) }}" class="btn btn-outline-danger w-100">
                                <i class="fas fa-exclamation-triangle me-2"></i> Relatório de Inadimplência
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                             {{-- Rota corrigida: financeiro.relatorios com parâmetro --}}
                            <a href="{{ route('financeiro.relatorios', ['tipo' => 'mensalidades']) }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-file-invoice me-2"></i> Relatório de Mensalidades
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
