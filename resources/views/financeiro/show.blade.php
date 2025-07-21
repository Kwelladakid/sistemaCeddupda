@extends('layouts.financeiro')

@section('content')
<div class="container">
    <h1 class="mb-4">Detalhes do Pagamento</h1>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('financeiro.pagamentos.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Voltar para Lista
        </a>
        <div class="btn-group">
            <a href="{{ route('financeiro.pagamentos.edit', $pagamento->id) }}" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i> Editar
            </a>
            <a href="{{ route('financeiro.pagamentos.recibo', $pagamento->id) }}" class="btn btn-success">
                <i class="fas fa-file-invoice-dollar me-1"></i> Gerar Recibo
            </a>
            <button type="button" class="btn btn-danger" onclick="confirmarExclusao()">
                <i class="fas fa-trash me-1"></i> Excluir
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Informações do Pagamento</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 40%">ID:</th>
                            <td>{{ $pagamento->id }}</td>
                        </tr>
                        <tr>
                            <th>Aluno:</th>
                            <td>
                                <a href="{{ route('financeiro.aluno', $pagamento->aluno->id) }}">
                                    {{ $pagamento->aluno->nome }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Valor:</th>
                            <td><strong class="text-primary">R$ {{ number_format($pagamento->valor, 2, ',', '.') }}</strong></td>
                        </tr>
                        <tr>
                            <th>Data do Pagamento:</th>
                            <td>{{ $pagamento->data_pagamento->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Método de Pagamento:</th>
                            <td>{{ $pagamento->getMetodoPagamentoFormatado() }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @if($pagamento->status == 'confirmado')
                                    <span class="badge bg-success">Confirmado</span>
                                @elseif($pagamento->status == 'pendente')
                                    <span class="badge bg-warning text-dark">Pendente</span>
                                @elseif($pagamento->status == 'cancelado')
                                    <span class="badge bg-danger">Cancelado</span>
                                @else
                                    <span class="badge bg-secondary">{{ $pagamento->status }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Registrado por:</th>
                            <td>{{ $pagamento->usuario->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Data do Registro:</th>
                            <td>{{ $pagamento->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @if($pagamento->observacao)
                            <tr>
                                <th>Observação:</th>
                                <td>{{ $pagamento->observacao }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Mensalidades Vinculadas</h5>
                </div>
                <div class="card-body">
                    @if($pagamento->mensalidades->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Mês/Ano</th>
                                        <th>Vencimento</th>
                                        <th>Valor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pagamento->mensalidades as $mensalidade)
                                        <tr>
                                            <td>{{ $mensalidade->getMesReferencia() }}</td>
                                            <td>{{ $mensalidade->data_vencimento->format('d/m/Y') }}</td>
                                            <td>R$ {{ number_format($mensalidade->valor_final, 2, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-info">
                                        <td colspan="2" class="text-end"><strong>Total:</strong></td>
                                        <td><strong>R$ {{ number_format($pagamento->mensalidades->sum('valor_final'), 2, ',', '.') }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Este pagamento não está vinculado a nenhuma mensalidade.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="btn-group">
    {{-- Link para editar (se existir) --}}
    @if (Route::has('financeiro.pagamentos.edit'))
        <a href="{{ route('financeiro.pagamentos.edit', $pagamento->id) }}" class="btn btn-primary">
            <i class="fas fa-edit me-1"></i> Editar
        </a>
    @endif
    {{-- CORRIGIDO: Link para gerar Recibo --}}
    @if (Route::has('financeiro.pagamentos.recibo'))
        <a href="{{ route('financeiro.pagamentos.recibo', $pagamento->id) }}" class="btn btn-success">
            <i class="fas fa-file-invoice-dollar me-1"></i> Gerar Recibo
        </a>
    @endif
    {{-- Botão para excluir (se existir) --}}
    @if (Route::has('financeiro.pagamentos.destroy'))
        <button type="button" class="btn btn-danger" onclick="confirmarExclusao()">
            <i class="fas fa-trash me-1"></i> Excluir
        </button>
    @endif
</div>

    <form id="form-delete" action="{{ route('financeiro.pagamentos.destroy', $pagamento->id) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>
@endsection

@push('scripts')
<script>
    function confirmarExclusao() {
        if (confirm('Tem certeza que deseja excluir este pagamento? Esta ação não pode ser desfeita.')) {
            document.getElementById('form-delete').submit();
        }
    }
</script>
@endpush
