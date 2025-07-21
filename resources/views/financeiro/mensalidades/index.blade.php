@extends('layouts.app') {{-- Certifique-se de que 'layouts.app' é o layout correto para sua aplicação --}}

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-8">
            <h1>Gerenciar Mensalidades</h1>
            <p class="text-muted">Selecione um aluno para registrar o pagamento de mensalidade.</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('financeiro.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Voltar ao Dashboard
            </a>
        </div>
    </div>

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

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Lista de Alunos</h5>
            {{-- Formulário de busca --}}
            <form action="{{ route('financeiro.mensalidades.index') }}" method="GET" class="d-flex">
                <input type="text" name="busca" class="form-control form-control-sm me-2" placeholder="Buscar por nome ou CPF" value="{{ request('busca') }}">
                <button type="submit" class="btn btn-sm btn-outline-primary">Buscar</button>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>Matrícula</th>
                            <th>Valor Mensalidade</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alunos as $aluno)
                            <tr>
                                <td>{{ $aluno->nome }}</td>
                                <td>{{ $aluno->cpf }}</td>
                                <td>{{ $aluno->matricula }}</td>
                                <td>R$ {{ number_format($valorMensalidade, 2, ',', '.') }}</td>
                                <td>
                                    {{-- Botão para pagar mensalidade, passando os dados para a página de criação de pagamento --}}
                                    <a href="{{ route('financeiro.pagamentos.create', [
                                        'aluno_id' => $aluno->id,
                                        'valor' => $valorMensalidade,
                                        'data_pagamento' => $dataAtual
                                    ]) }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-money-bill-wave me-1"></i> Pagar Mensalidade
                                    </a>
                                    {{-- Botão para ver histórico do aluno --}}
                                    <a href="{{ route('financeiro.buscar-alunos', ['termo' => $aluno->cpf]) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-history me-1"></i> Histórico
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Nenhum aluno encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <div class="d-flex justify-content-center mt-4">
                {{ $alunos->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
