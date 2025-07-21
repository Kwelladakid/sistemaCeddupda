@extends('layouts.financeiro')

@section('content')
<div class="container">
    <h1 class="mb-4">Novo Pagamento</h1>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Voltar
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Registrar Pagamento</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('financeiro.pagamentos.store') }}" method="POST">
                @csrf

                @if(isset($aluno))
                    <input type="hidden" name="aluno_id" value="{{ $aluno->id }}">
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-user me-2"></i> Registrando pagamento para: <strong>{{ $aluno->nome }}</strong>
                    </div>
                @else
                    <div class="mb-3">
                        <label for="aluno_id" class="form-label">Aluno</label>
                        <select name="aluno_id" id="aluno_id" class="form-control @error('aluno_id') is-invalid @enderror" required>
                            <option value="">Selecione o aluno</option>
                            @foreach($alunos as $aluno)
                                <option value="{{ $aluno->id }}" {{ old('aluno_id') == $aluno->id ? 'selected' : '' }}>
                                    {{ $aluno->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('aluno_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="valor" class="form-label">Valor (R$)</label>
                            <input type="number" step="0.01" min="0.01" name="valor" id="valor" class="form-control @error('valor') is-invalid @enderror" value="{{ old('valor') }}" required>
                            @error('valor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="data_pagamento" class="form-label">Data do Pagamento</label>
                            <input type="date" name="data_pagamento" id="data_pagamento" class="form-control @error('data_pagamento') is-invalid @enderror" value="{{ old('data_pagamento', date('Y-m-d')) }}" required>
                            @error('data_pagamento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="metodo_pagamento" class="form-label">Método de Pagamento</label>
                    <select name="metodo_pagamento" id="metodo_pagamento" class="form-control @error('metodo_pagamento') is-invalid @enderror" required>
                        <option value="">Selecione o método</option>
                        @foreach($metodos_pagamento ?? ['dinheiro' => 'Dinheiro', 'cartao_credito' => 'Cartão de Crédito', 'cartao_debito' => 'Cartão de Débito', 'pix' => 'PIX', 'transferencia' => 'Transferência Bancária', 'boleto' => 'Boleto'] as $value => $label)
                            <option value="{{ $value }}" {{ old('metodo_pagamento') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('metodo_pagamento')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="observacao" class="form-label">Observação (opcional)</label>
                    <textarea name="observacao" id="observacao" class="form-control @error('observacao') is-invalid @enderror" rows="3">{{ old('observacao') }}</textarea>
                    @error('observacao')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @if(isset($aluno) && $mensalidades_pendentes->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Mensalidades Pendentes</h6>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info mb-3">
                                Selecione as mensalidades que deseja quitar com este pagamento.
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;"></th>
                                            <th>Mês/Ano</th>
                                            <th>Vencimento</th>
                                            <th>Valor</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($mensalidades_pendentes as $mensalidade)
                                            <tr class="{{ $mensalidade->estaAtrasada() ? 'table-danger' : '' }}">
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input mensalidade-check" type="checkbox" name="mensalidades[]" value="{{ $mensalidade->id }}" id="mensalidade-{{ $mensalidade->id }}" data-valor="{{ $mensalidade->valor_final }}">
                                                    </div>
                                                </td>
                                                <td>{{ $mensalidade->getMesReferencia() }}</td>
                                                <td>{{ $mensalidade->data_vencimento->format('d/m/Y') }}</td>
                                                <td>R$ {{ number_format($mensalidade->valor_final, 2, ',', '.') }}</td>
                                                <td>
                                                    @if($mensalidade->estaAtrasada())
                                                        <span class="badge bg-danger">Atrasada</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">Pendente</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-info">
                                            <td colspan="3" class="text-end"><strong>Total selecionado:</strong></td>
                                            <td colspan="2"><strong id="total-selecionado">R$ 0,00</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                @elseif(isset($aluno))
                    <div class="alert alert-success mb-4">
                        <i class="fas fa-check-circle me-2"></i> Este aluno não possui mensalidades pendentes.
                    </div>
                @endif

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Registrar Pagamento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Atualizar valor total das mensalidades selecionadas
        const checkboxes = document.querySelectorAll('.mensalidade-check');
        const totalSelecionado = document.getElementById('total-selecionado');

        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', atualizarTotal);
        });

        function atualizarTotal() {
            let total = 0;
            checkboxes.forEach(function(checkbox) {
                if (checkbox.checked) {
                    total += parseFloat(checkbox.getAttribute('data-valor'));
                }
            });

            // Formatar o valor total
            totalSelecionado.textContent = 'R$ ' + total.toFixed(2).replace('.', ',');

            // Atualizar automaticamente o campo de valor (opcional)
            const valorInput = document.getElementById('valor');
            if (valorInput && total > 0) {
                valorInput.value = total.toFixed(2);
            }
        }

        // Caso o aluno seja selecionado via dropdown, redirecionar para a página com o aluno selecionado
        const alunoSelect = document.getElementById('aluno_id');
        if (alunoSelect) {
            alunoSelect.addEventListener('change', function() {
                if (this.value) {
                    window.location.href = "{{ route('financeiro.pagamentos.create') }}?aluno_id=" + this.value;
                }
            });
        }
    });
</script>
@endpush
