{{-- resources/views/aluno/index.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Meu Perfil de Aluno</h1>

    {{-- Informações Pessoais do Aluno --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Dados Pessoais</h4>
        </div>
        <div class="card-body">
            <p><strong>Nome:</strong> {{ $aluno->nome }}</p>
            <p><strong>CPF:</strong> {{ $aluno->cpf }}</p>
            <p><strong>E-mail:</strong> {{ $aluno->email }}</p>
            <p><strong>Data de Nascimento:</strong> {{ \Carbon\Carbon::parse($aluno->data_nascimento)->format('d/m/Y') }}</p>
            <p><strong>Telefone:</strong> {{ $aluno->telefone }}</p>
            <p><strong>Endereço:</strong> {{ $aluno->endereco }}</p>
            <p><strong>Status:</strong> {{ ucfirst($aluno->status) }}</p>
        </div>
    </div>

    {{-- Quadro de Notas --}}
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0">Minhas Notas</h4>
        </div>
        <div class="card-body">
            @if ($aluno->notas->isEmpty())
                <p>Você ainda não possui notas registradas.</p>
            @else
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Disciplina</th>
                            <th>Nota</th>
                            <th>Data de Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($aluno->notas as $nota)
                            <tr>
                                <td>{{ $nota->disciplina->nome ?? 'Disciplina Não Encontrada' }}</td>
                                <td>{{ number_format($nota->nota, 2, ',', '.') }}</td>
                                <td>{{ \Carbon\Carbon::parse($nota->created_at)->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    {{-- Quadro de Histórico Financeiro (Pagamentos Confirmados) --}}
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0">Histórico de Pagamentos</h4>
        </div>
        <div class="card-body">
            @if ($aluno->pagamentos->isEmpty())
                <p>Você ainda não possui pagamentos registrados.</p>
            @else
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Data do Pagamento</th>
                            <th>Valor</th>
                            <th>Método</th>
                            <th>Status</th>
                            <th>Observação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($aluno->pagamentos as $pagamento)
                            {{-- Aqui você pode filtrar por status 'confirmado' se desejar --}}
                            {{-- Exemplo: @if ($pagamento->status === 'confirmado') --}}
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($pagamento->data_pagamento)->format('d/m/Y') }}</td>
                                <td>R$ {{ number_format($pagamento->valor, 2, ',', '.') }}</td>
                                <td>{{ $pagamento->metodo_pagamento }}</td>
                                <td>{{ ucfirst($pagamento->status) }}</td>
                                <td>{{ $pagamento->observacao ?? 'N/A' }}</td>
                            </tr>
                            {{-- @endif --}}
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    {{-- Link para voltar ou para outras seções --}}
    <div class="text-center">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Voltar ao Dashboard</a>
    </div>
</div>
@endsection
