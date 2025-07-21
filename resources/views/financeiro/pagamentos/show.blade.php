{{-- resources/views/financeiro/pagamentos/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detalhes do Pagamento')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Detalhes do Pagamento</h1>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('financeiro.index') }}">Financeiro</a></li>
        <li class="breadcrumb-item"><a href="{{ route('financeiro.pagamentos.index') }}">Pagamentos</a></li>
        <li class="breadcrumb-item active">Detalhes</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-money-bill-wave me-1"></i>
            Informações do Pagamento
        </div>
        <div class="card-body">
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

            {{-- Verifica se a variável $pagamento foi passada para a view --}}
            @if(isset($pagamento))
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>ID do Pagamento:</strong> {{ $pagamento->id }}
                    </div>
                    <div class="col-md-6">
                        <strong>Aluno:</strong>
                        @if($pagamento->aluno)
                            {{-- Link para os detalhes do aluno --}}
                            <a href="{{ route('alunos.show', $pagamento->aluno->id) }}">{{ $pagamento->aluno->nome }}</a>
                        @else
                            N/A (Aluno não encontrado)
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Valor:</strong> R\$ {{ number_format($pagamento->valor, 2, ',', '.') }}
                    </div>
                    <div class="col-md-6">
                        <strong>Data do Pagamento:</strong> {{ \Carbon\Carbon::parse($pagamento->data_pagamento)->format('d/m/Y H:i') }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Método de Pagamento:</strong> {{ $pagamento->metodo_pagamento }}
                    </div>
                    <div class="col-md-6">
                        <strong>Status:</strong>
                        @switch($pagamento->status)
                            @case('pago')
                                <span class="badge bg-success">Pago</span>
                                @break
                            @case('pendente')
                                <span class="badge bg-warning">Pendente</span>
                                @break
                            @case('cancelado')
                                <span class="badge bg-danger">Cancelado</span>
                                @break
                            @default
                                <span class="badge bg-secondary">{{ ucfirst($pagamento->status) }}</span>
                        @endswitch
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <strong>Observações:</strong> {{ $pagamento->observacoes ?: 'Nenhuma observação.' }}
                    </div>
                </div>

                <div class="mt-4">
                    {{-- Botão Voltar --}}
                    <a href="{{ route('financeiro.pagamentos.index') }}" class="form-btn-icon back-button">
                        <i class="fas fa-arrow-left"></i> Voltar para Pagamentos
                    </a>
                    {{-- Botão Editar (se houver rota de edição) --}}
                    {{-- Certifique-se de que a rota 'financeiro.pagamentos.edit' existe --}}
                    <a href="{{ route('financeiro.pagamentos.edit', $pagamento->id) }}" class="form-btn-icon edit-button">
                        <i class="fas fa-edit"></i> Editar Pagamento
                    </a>
                </div>
            @else
                <p>Detalhes do pagamento não encontrados.</p>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Estilos para o botão de edição, se ainda não existirem no app.blade.php */
    /* Se você já tem um .action-icon-btn.edit ou similar, pode usar ele */
    .form-btn-icon.edit-button {
        background-color: #007bff; /* Azul */
        border-color: #007bff;
    }
    .form-btn-icon.edit-button:hover {
        background-color: #0069d9;
        border-color: #0062cc;
    }
</style>
@endpush
