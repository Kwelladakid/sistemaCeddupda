@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Registrar Novo Pagamento</span>
                    <a href="{{ route('financeiro.pagamentos.index') }}" class="btn btn-sm btn-outline-secondary">Voltar</a>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('financeiro.pagamentos.store') }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="aluno_id" class="form-label">Aluno</label>
                                <select name="aluno_id" id="aluno_id" class="form-select @error('aluno_id') is-invalid @enderror" required>
                                    <option value="">Selecione o aluno</option>
                                    @foreach ($alunos as $aluno)
                                        {{-- MODIFICADO: Pré-seleciona o aluno se houver um ID passado --}}
                                        <option value="{{ $aluno->id }}" {{ (old('aluno_id', $alunoSelecionadoId ?? '') == $aluno->id) ? 'selected' : '' }}>
                                            {{ $aluno->nome }} ({{ $aluno->getNumeroMatriculaAttribute() }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('aluno_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="mensalidade_id" class="form-label">Mensalidade (opcional)</label>
                                <select name="mensalidade_id" id="mensalidade_id" class="form-select @error('mensalidade_id') is-invalid @enderror">
                                    <option value="">Selecione a mensalidade ou deixe em branco</option>
                                    <!-- As mensalidades serão carregadas via JavaScript -->
                                </select>
                                @error('mensalidade_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="valor" class="form-label">Valor</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    {{-- MODIFICADO: Pré-preenche o valor se houver um valor passado --}}
                                    <input type="number" step="0.01" min="0" name="valor" id="valor"
                                        class="form-control @error('valor') is-invalid @enderror"
                                        value="{{ old('valor', $valorPreenchido ?? '') }}" required>
                                </div>
                                @error('valor')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="metodo_pagamento" class="form-label">Método de Pagamento</label>
                                <select name="metodo_pagamento" id="metodo_pagamento"
                                    class="form-select @error('metodo_pagamento') is-invalid @enderror" required>
                                    <option value="">Selecione o método</option>
                                    @foreach ($metodosPagamento as $value => $label)
                                        <option value="{{ $value }}" {{ old('metodo_pagamento') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('metodo_pagamento')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="data_pagamento" class="form-label">Data do Pagamento</label>
                                {{-- MODIFICADO: Pré-preenche a data se houver uma data passada --}}
                                <input type="date" name="data_pagamento" id="data_pagamento"
                                    class="form-control @error('data_pagamento') is-invalid @enderror"
                                    value="{{ old('data_pagamento', $dataPreenchida ?? date('Y-m-d')) }}" required>
                                @error('data_pagamento')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="observacao" class="form-label">Observação (opcional)</label>
                            <textarea name="observacao" id="observacao"
                                class="form-control @error('observacao') is-invalid @enderror"
                                rows="3">{{ old('observacao') }}</textarea>
                            @error('observacao')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                Registrar Pagamento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Função para carregar as mensalidades pendentes
    function loadMensalidadesPendentes(alunoId) {
        const mensalidadeSelect = document.getElementById('mensalidade_id');
        mensalidadeSelect.innerHTML = '<option value="">Selecione a mensalidade ou deixe em branco</option>'; // Limpa as opções

        if (!alunoId) return;

        fetch(`/financeiro/mensalidades-pendentes?aluno_id=${alunoId}`)
            .then(response => response.json())
            .then(data => {
                data.mensalidades.forEach(mensalidade => {
                    const option = document.createElement('option');
                    option.value = mensalidade.id;
                    option.textContent = `${mensalidade.mes_referencia} - R$ ${parseFloat(mensalidade.valor_final).toFixed(2).replace('.', ',')} (Vencimento: ${new Date(mensalidade.data_vencimento).toLocaleDateString('pt-BR')})`;
                    option.dataset.valor = mensalidade.valor_final;
                    mensalidadeSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Erro ao carregar mensalidades:', error));
    }

    // Event listener para quando o aluno muda
    document.getElementById('aluno_id').addEventListener('change', function() {
        const alunoId = this.value;
        loadMensalidadesPendentes(alunoId);
    });

    // Event listener para quando a mensalidade muda, atualiza o valor
    document.getElementById('mensalidade_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const valorInput = document.getElementById('valor');

        if (selectedOption && selectedOption.dataset.valor) {
            valorInput.value = selectedOption.dataset.valor;
        }
    });

    // NOVO: Carrega mensalidades pendentes ao carregar a página se um aluno já estiver selecionado
    document.addEventListener('DOMContentLoaded', function() {
        const alunoSelect = document.getElementById('aluno_id');
        if (alunoSelect.value) { // Verifica se já existe um aluno selecionado (pré-preenchido)
            loadMensalidadesPendentes(alunoSelect.value);
        }
    });
</script>
@endpush
@endsection
