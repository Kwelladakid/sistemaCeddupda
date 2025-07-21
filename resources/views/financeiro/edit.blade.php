@extends('layouts.financeiro')

@section('content')
<div class="container">
    <h1 class="mb-4">Editar Pagamento</h1>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('financeiro.pagamentos.show', $pagamento->id) }}" class="btn btn-secondary">
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
            <h5 class="mb-0">Editar Pagamento #{{ $pagamento->id }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('financeiro.pagamentos.update', $pagamento->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="aluno_id" class="form-label">Aluno</label>
                    <select name="aluno_id" id="aluno_id" class="form-control @error('aluno_id') is-invalid @enderror" required>
                        <option value="">Selecione o aluno</option>
                        @foreach($alunos as $aluno)
                            <option value="{{ $aluno->id }}" {{ (old('aluno_id', $pagamento->aluno_id) == $aluno->id) ? 'selected' : '' }}>
                                {{ $aluno->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('aluno_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="valor" class="form-label">Valor (R$)</label>
                            <input type="number" step="0.01" min="0.01" name="valor" id="valor" class="form-control @error('valor') is-invalid @enderror" value="{{ old('valor', $pagamento->valor) }}" required>
                            @error('valor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="data_pagamento" class="form-label">Data do Pagamento</label>
                            <input type="date" name="data_pagamento" id="data_pagamento" class="form-control @error('data_pagamento') is-invalid @enderror" value="{{ old('data_pagamento', $pagamento->data_pagamento->format('Y-m-d')) }}" required>
                            @error('data_pagamento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                @foreach($status_options as $value => $label)
                                    <option value="{{ $value }}" {{ old('status', $pagamento->status) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="metodo_pagamento" class="form-label">Método de Pagamento</label>
                    <select name="metodo_pagamento" id="metodo_pagamento" class="form-control @error('metodo_pagamento') is-invalid @enderror" required>
                        <option value="">Selecione o método</option>
                        @foreach($metodos_pagamento as $value => $label)
                            <option value="{{ $value }}" {{ old('metodo_pagamento', $pagamento->metodo_pagamento) == $value ? 'selected' : '' }}>
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
                    <textarea name="observacao" id="observacao" class="form-control @error('observacao') is-invalid @enderror" rows="3">{{ old('observacao', $pagamento->observacao) }}</textarea>
                    @error('observacao')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @if($pagamento->mensalidades->count() > 0)
                    <div class="alert alert-warning mb-4">
                        <i class="fas fa-exclamation-triangle me-2"></i> Este pagamento está vinculado a {{ $pagamento->mensalidades->count() }} mensalidade(s).
                        Se alterar o status para "Cancelado", as mensalidades serão marcadas como pendentes novamente.
                    </div>
                @endif

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Atualizar Pagamento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
