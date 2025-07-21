{{-- resources/views/disciplinas/create_for_curso.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Cadastrar Nova Disciplina para o Curso: {{ $curso->nome }}</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Erro!</strong> Verifique os campos abaixo:<br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('cursos.disciplinas.store', $curso->id) }}" method="POST">
        @csrf

        <!-- Campo oculto para o ID do curso -->
        <input type="hidden" name="curso_id" value="{{ $curso->id }}">

        <div class="form-group mb-3">
            <label for="nome">Nome da Disciplina:</label>
            <input type="text" name="nome" id="nome" class="form-control @error('nome') is-invalid @enderror" value="{{ old('nome') }}" required>
            @error('nome')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="carga_horaria">Carga Horária (horas):</label>
            <input type="number" name="carga_horaria" id="carga_horaria" class="form-control @error('carga_horaria') is-invalid @enderror" value="{{ old('carga_horaria') }}" required min="1">
            @error('carga_horaria')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="modulo">Módulo:</label>
            <select name="modulo" id="modulo" class="form-control @error('modulo') is-invalid @enderror" required>
                <option value="">Selecione o Módulo</option>
                @foreach($modulos as $modulo)
                    <option value="{{ $modulo }}" {{ old('modulo') == $modulo ? 'selected' : '' }}>
                        Módulo {{ $modulo }}
                    </option>
                @endforeach
            </select>
            @error('modulo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- NOVO CAMPO: Professor Responsável pela Disciplina --}}
        <div class="form-group mb-3">
            <label for="professor_id">Professor Responsável:</label>
            <select name="professor_id" id="professor_id" class="form-control @error('professor_id') is-invalid @enderror" required>
                <option value="">Selecione um professor</option>
                {{-- O loop abaixo espera a variável $professores do controlador --}}
                @foreach($professores as $professor)
                    <option value="{{ $professor->id }}" {{ old('professor_id') == $professor->id ? 'selected' : '' }}>
                        {{ $professor->nome }}
                    </option>
                @endforeach
            </select>
            @error('professor_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-success">Cadastrar Disciplina</button>
            <a href="{{ route('cursos.show', $curso->id) }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
