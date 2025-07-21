{{-- resources/views/cursos/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Cadastrar Curso')

@section('styles')
    {{-- Removido: Estilos para os botões de formulário com ícones foram movidos para layouts/app.blade.php --}}
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Cadastrar Novo Curso</h1>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('cursos.index') }}">Cursos</a></li>
        <li class="breadcrumb-item active">Cadastrar</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-graduation-cap me-1"></i>
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

            <form action="{{ route('cursos.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="nome" class="form-label">Nome do Curso</label>
                        <input type="text" class="form-control @error('nome') is-invalid @enderror"
                               id="nome" name="nome" value="{{ old('nome') }}" required>
                        @error('nome')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control @error('descricao') is-invalid @enderror"
                                  id="descricao" name="descricao" rows="4">{{ old('descricao') }}</textarea>
                        @error('descricao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="carga_horaria" class="form-label">Carga Horária (horas)</label>
                        <input type="text" class="form-control @error('carga_horaria') is-invalid @enderror"
                               id="carga_horaria" name="carga_horaria" value="{{ old('carga_horaria') }}"
                               required inputmode="numeric" pattern="[0-9]*">
                        <small class="form-text text-muted">Digite apenas números inteiros positivos.</small>
                        @error('carga_horaria')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    {{-- Botão "Salvar Curso" --}}
                    <button type="submit" class="form-btn-icon save-course">
                        <i class="fas fa-save"></i> Salvar Curso
                    </button>
                    {{-- Botão "Voltar" --}}
                    <a href="{{ route('cursos.index') }}" class="form-btn-icon back-button">
                        <i class="fas fa-arrow-left"></i> Voltar
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
        // Validação para permitir apenas números no campo de carga horária
        $("#carga_horaria").on("input", function() {
            // Remove qualquer caractere que não seja número
            $(this).val($(this).val().replace(/[^0-9]/g, ''));

            // Se o valor começar com zero e tiver mais de um dígito, remove o zero à esquerda
            if ($(this).val().length > 1 && $(this).val().charAt(0) === '0') {
                $(this).val($(this).val().substring(1));
            }

            // Se o campo estiver vazio, não faz nada
            if ($(this).val() === '') {
                return;
            }

            // Converte para número para garantir que é um valor válido
            let value = parseInt($(this).val(), 10);

            // Se o valor for menor que 1, define como 1 (mínimo)
            if (value < 1) {
                $(this).val(1);
            }
        });

        // Impede que o usuário digite caracteres não numéricos
        $("#carga_horaria").on("keypress", function(e) {
            // Permite apenas teclas numéricas (0-9)
            if (e.which < 48 || e.which > 57) {
                e.preventDefault();
            }
        });

        // Validação adicional no envio do formulário
        $("form").on("submit", function(e) {
            let cargaHoraria = $("#carga_horaria").val();

            // Verifica se o campo está vazio
            if (cargaHoraria === '') {
                e.preventDefault();
                alert("Por favor, informe a carga horária do curso.");
                $("#carga_horaria").focus();
                return false;
            }

            // Verifica se o valor é um número válido
            let value = parseInt(cargaHoraria, 10);
            if (isNaN(value) || value < 1) {
                e.preventDefault();
                alert("A carga horária deve ser um número inteiro positivo.");
                $("#carga_horaria").focus();
                return false;
            }

            return true;
        });
    });
</script>
@endsection
