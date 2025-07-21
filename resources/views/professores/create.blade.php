{{-- resources/views/professores/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Cadastrar Professor')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Cadastrar Novo Professor</h1>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('professores.index') }}">Professores</a></li>
        <li class="breadcrumb-item active">Cadastrar</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user-plus me-1"></i>
            Formulário de Cadastro
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

            <form action="{{ route('professores.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nome" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control @error('nome') is-invalid @enderror"
                               id="nome" name="nome" value="{{ old('nome') }}" required>
                        @error('nome')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="cpf" class="form-label">CPF</label>
                        <input type="text" class="form-control @error('cpf') is-invalid @enderror"
                               id="cpf" name="cpf" value="{{ old('cpf') }}" required>
                        @error('cpf')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="text" class="form-control @error('telefone') is-invalid @enderror"
                               id="telefone" name="telefone" value="{{ old('telefone') }}">
                        @error('telefone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="especialidade" class="form-label">Especialidade</label>
                        <input type="text" class="form-control @error('especialidade') is-invalid @enderror"
                               id="especialidade" name="especialidade" value="{{ old('especialidade') }}" required>
                        @error('especialidade')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="formacao" class="form-label">Formação Acadêmica</label>
                        <input type="text" class="form-control @error('formacao') is-invalid @enderror"
                               id="formacao" name="formacao" value="{{ old('formacao') }}">
                        @error('formacao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- NOVO CAMPO: SELEÇÃO DE DISCIPLINAS --}}
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="disciplinas" class="form-label">Disciplinas:</label>
                        <select name="disciplinas[]" id="disciplinas" class="form-control @error('disciplinas') is-invalid @enderror @error('disciplinas.*') is-invalid @enderror" multiple>
                            {{-- Verifica se $disciplinas foi passado para a view --}}
                            @if(isset($disciplinas))
                                @foreach($disciplinas as $disciplina)
                                    <option value="{{ $disciplina->id }}"
                                        {{-- Mantém a seleção se o formulário foi submetido com erros --}}
                                        {{ in_array($disciplina->id, old('disciplinas', [])) ? 'selected' : '' }}>
                                        {{ $disciplina->nome }} ({{ $disciplina->codigo ?? 'N/A' }})
                                    </option>
                                @endforeach
                            @else
                                <option value="" disabled>Nenhuma disciplina disponível</option>
                            @endif
                        </select>
                        @error('disciplinas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @error('disciplinas.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                {{-- FIM DO NOVO CAMPO --}}

                <div class="row mb-3">
                    <div class="col-12">
                        <label for="observacoes" class="form-label">Observações</label>
                        <textarea class="form-control @error('observacoes') is-invalid @enderror"
                                  id="observacoes" name="observacoes" rows="3">{{ old('observacoes') }}</textarea>
                        @error('observacoes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Salvar
                    </button>
                    <a href="{{ route('professores.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Voltar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Máscara para CPF
        $('#cpf').mask('000.000.000-00');

        // Máscara para telefone
        $('#telefone').mask('(00) 00000-0000');

        $('form').submit(function() {
         // Remove todos os caracteres não numéricos do telefone antes de enviar
         var telefone = $('#telefone').val().replace(/\D/g, '');
        $('#telefone').val(telefone);
        });

        // Opcional: Adicionar um plugin de select2 para melhorar a experiência do multi-select
        // Se você tiver o Select2 instalado, descomente as linhas abaixo
        // $('#disciplinas').select2({
        //     placeholder: "Selecione as disciplinas",
        //     allowClear: true
        // });
    });
</script>
@endsection
