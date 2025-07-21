@extends('layouts.financeiro') {{-- Usando o layout simplificado que criamos --}}

@section('content')
<div class="container">
    <h1 class="mb-4">Pagamento</h1>

    <!-- Cards de resumo permanecem iguais -->
    <div class="row mb-4">
        <!-- ... Código dos cards permanece igual ... -->
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    Buscar Aluno por CPF
                </div>
                <div class="card-body">
                    {{-- MODIFICADO: Formulário de busca apenas por CPF com máscara --}}
                    <form action="{{ route('financeiro.buscar-alunos') }}" method="GET" id="form-busca-aluno">
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" id="cpf-busca"
                                   placeholder="Digite o CPF do aluno" name="termo" required>
                            <button class="btn btn-primary" type="submit">Buscar</button>
                        </div>
                        <small class="form-text text-muted mb-3 d-block">Digite o CPF no formato 000.000.000-00</small>
                    </form>
                    <hr>
                    <h5 class="card-title">Ações Rápidas</h5>
                    <div class="list-group mt-3">
                        {{-- REMOVIDO: Rota para gerar mensalidades em lote --}}
                        <a href="{{ route('financeiro.relatorios') }}?tipo=inadimplencia" class="list-group-item list-group-item-action">
                            <i class="fas fa-exclamation-circle me-2"></i> Ver Alunos Inadimplentes
                        </a>
                        <a href="{{ route('financeiro.pagamentos.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-money-bill-wave me-2"></i> Histórico de Pagamentos
                        </a>
                        {{-- ADICIONADO: Botão para gerar comprovante do último pagamento --}}
                        @if(isset($ultimoPagamento) && $ultimoPagamento)
                        <a href="{{ route('financeiro.pagamentos.comprovante', $ultimoPagamento->id) }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-file-pdf me-2"></i> Comprovante do Último Pagamento
                        </a>
                        @endif
                        {{-- ADICIONADO: Botão para registrar novo pagamento --}}
                        <a href="{{ route('financeiro.pagamentos.create') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-plus-circle me-2"></i> Registrar Novo Pagamento
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- O resto do código permanece igual -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Últimos Pagamentos</h5>
                    <a href="{{ route('financeiro.pagamentos.index') }}" class="btn btn-sm btn-outline-primary">Ver Todos</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Aluno</th>
                                    <th>Data</th>
                                    <th>Valor</th>
                                    <th>Método</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pagamentos as $pagamento)
                                    <tr>
                                        <td>
                                            <a href="{{ route('financeiro.buscar-alunos', ['termo' => $pagamento->aluno->cpf ?? '']) }}">
                                                {{ $pagamento->aluno->nome ?? 'Aluno não encontrado' }}
                                            </a>
                                        </td>
                                        <td>{{ $pagamento->data_pagamento->format('d/m/Y') }}</td>
                                        <td>R$ {{ number_format($pagamento->valor, 2, ',', '.') }}</td>
                                        <td>{{ $pagamento->metodo_pagamento }}</td>
                                        <td>
                                            <a href="{{ route('financeiro.pagamentos.comprovante', $pagamento->id) }}" class="btn btn-sm btn-primary" title="Gerar Comprovante">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Nenhum pagamento encontrado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- MODIFICADO: Usando o template de paginação bootstrap-4 --}}
                    @if($pagamentos instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="d-flex justify-content-center mt-3">
                            {{ $pagamentos->links('pagination::bootstrap-4') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- MODIFICADO: Adicionando paginação bootstrap-4 para alunos se existir --}}
    @if(isset($alunos) && $alunos instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-center mt-3 mb-4">
                    {{ $alunos->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    @endif
    <!-- O resto do código permanece igual -->
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
                            <a href="{{ route('financeiro.relatorios') }}?tipo=pagamentos" class="btn btn-outline-primary w-100">
                                <i class="fas fa-chart-line me-2"></i> Relatório de Pagamentos
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('financeiro.relatorios') }}?tipo=inadimplencia" class="btn btn-outline-danger w-100">
                                <i class="fas fa-exclamation-triangle me-2"></i> Relatório de Inadimplência
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('financeiro.relatorios') }}?tipo=mensalidades" class="btn btn-outline-success w-100">
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
@push('scripts')
{{-- Script para o gráfico Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('graficoReceitas').getContext('2d');
    // Verifica se as variáveis do gráfico existem antes de inicializar
    @if(!empty($ultimosMeses) && !empty($valoresMensais))
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($ultimosMeses) !!},
                datasets: [{
                    label: 'Receita Mensal (R$)',
                    data: {!! json_encode($valoresMensais) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'R$ ' + value.toFixed(2).replace('.', ',');
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'R$ ' + context.raw.toFixed(2).replace('.', ',');
                            }
                        }
                    }
                }
            }
        });
    @else
        // Opcional: Exibir uma mensagem ou placeholder se não houver dados para o gráfico
        console.log('Dados insuficientes para gerar o gráfico de receitas.');
    @endif
});
// ADICIONADO: Script para máscara de CPF e validação
$(document).ready(function() {
    // Aplicar máscara ao campo de CPF
    $('#cpf-busca').mask('000.000.000-00', {
        placeholder: "___.___.___-__"
    });

    // Validar o formulário antes de enviar
    $('#form-busca-aluno').on('submit', function(e) {
        var cpf = $('#cpf-busca').val();

        // Verifica se o CPF está no formato correto
        var cpfPattern = /^\d{3}\.\d{3}\.\d{3}-\d{2}$/;
        if (!cpfPattern.test(cpf)) {
            alert('Por favor, digite um CPF válido no formato 000.000.000-00');
            e.preventDefault();
            return false;
        }

        return true;
    });
});
</script>
@endpush
