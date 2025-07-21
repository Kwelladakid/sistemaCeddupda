{{-- resources/views/turmas/create_for_curso.blade.php --}}
@extends('layouts.app')

@section('title', 'Nova Turma')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Adicionar Nova Turma</h1>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('cursos.index') }}">Cursos</a></li>
        <li class="breadcrumb-item"><a href="{{ route('cursos.show', $curso->id) }}">{{ $curso->nome }}</a></li>
        <li class="breadcrumb-item active">Nova Turma</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-users-class me-1"></i>
            Formulário de Cadastro de Turma para o Curso: <strong>{{ $curso->nome }}</strong>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Erro!</strong> Verifique os campos abaixo:<br>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('cursos.turmas.store', $curso->id) }}" method="POST">
                @csrf

                <!-- Campo oculto para o ID do curso -->
                <input type="hidden" name="curso_id" value="{{ $curso->id }}">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nome" class="form-label">Número da Turma:</label>
                        <input type="text" name="nome" id="nome" class="form-control @error('nome') is-invalid @enderror"
                               value="{{ old('nome') }}" required inputmode="numeric" pattern="[0-9]*">
                        <small class="form-text text-muted">Digite apenas números para identificar a turma.</small>
                        @error('nome')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="ano" class="form-label">Ano:</label>
                        <input type="text" name="ano" id="ano" class="form-control @error('ano') is-invalid @enderror"
                               value="{{ old('ano', date('Y')) }}" required maxlength="4" inputmode="numeric" pattern="20[0-9]{2}">
                        <small class="form-text text-muted">O ano deve estar no formato 20XX.</small>
                        @error('ano')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="periodo" class="form-label">Período:</label>
                        <select name="periodo" id="periodo" class="form-select @error('periodo') is-invalid @enderror" required>
                            <option value="">Selecione</option>
                            <option value="Matutino" {{ old('periodo') == 'Matutino' ? 'selected' : '' }}>Matutino</option>
                            <option value="Vespertino" {{ old('periodo') == 'Vespertino' ? 'selected' : '' }}>Vespertino</option>
                            <option value="Noturno" {{ old('periodo') == 'Noturno' ? 'selected' : '' }}>Noturno</option>
                            <option value="Integral" {{ old('periodo') == 'Integral' ? 'selected' : '' }}>Integral</option>
                        </select>
                        @error('periodo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- REMOVIDO: O campo Professor Responsável foi removido completamente --}}
                    {{-- O código que estava aqui foi removido para evitar o erro "Undefined variable: professores" --}}
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="status" class="form-label">Status:</label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="ativa" {{ old('status') == 'ativa' ? 'selected' : '' }}>Ativa</option>
                            <option value="inativa" {{ old('status') == 'inativa' ? 'selected' : '' }}>Inativa</option>
                            <option value="concluida" {{ old('status') == 'concluida' ? 'selected' : '' }}>Concluída</option>
                            <option value="cancelada" {{ old('status') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Criar Turma
                    </button>
                    <a href="{{ route('cursos.show', $curso->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Cancelar
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
        // Validação para o campo "Número da Turma" (apenas números)
        $("#nome").on("input", function() {
            // Remove qualquer caractere que não seja número
            $(this).val($(this).val().replace(/[^0-9]/g, ''));
        });

        // Impede que o usuário digite caracteres não numéricos no campo "Número da Turma"
        $("#nome").on("keypress", function(e) {
            // Permite apenas teclas numéricas (0-9)
            if (e.which < 48 || e.which > 57) {
                e.preventDefault();
            }
        });

        // Configuração para o campo "Ano"
        $("#ano").on("focus", function() {
            // Se o campo estiver vazio, preenche com o ano atual
            if ($(this).val() === '') {
                $(this).val(new Date().getFullYear());
            }

            // Se o valor não começar com "20", força começar com "20"
            if (!$(this).val().startsWith('20')) {
                $(this).val('20' + $(this).val().substring(0, 2));
            }
        });

        $("#ano").on("input", function() {
            // Remove qualquer caractere que não seja número
            $(this).val($(this).val().replace(/[^0-9]/g, ''));

            // Limita a 4 dígitos
            if ($(this).val().length > 4) {
                $(this).val($(this).val().substring(0, 4));
            }

            // Garante que o valor comece com "20"
            if ($(this).val().length >= 2 && !$(this).val().startsWith('20')) {
                $(this).val('20' + $(this).val().substring(2));
            }
        });

        // Impede que o usuário digite caracteres não numéricos no campo "Ano"
        $("#ano").on("keypress", function(e) {
            // Permite apenas teclas numéricas (0-9)
            if (e.which < 48 || e.which > 57) {
                e.preventDefault();
            }
        });

        // Validação adicional no envio do formulário
        $("form").on("submit", function(e) {
            // Validação do Número da Turma
            let numeroTurma = $("#nome").val();
            if (numeroTurma === '' || !/^\d+$/.test(numeroTurma)) {
                e.preventDefault();
                alert("O número da turma deve conter apenas dígitos numéricos.");
                $("#nome").focus();
                return false;
            }

            // Validação do Ano
            let ano = $("#ano").val();
            if (ano === '' || !/^20\d{2}$/.test(ano)) {
                e.preventDefault();
                alert("O ano deve estar no formato 20XX (ex: 2023, 2024).");
                $("#ano").focus();
                return false;
            }

            return true;
        });
    });
</script>
@endsection
