{{-- resources/views/disciplinas/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Cadastrar Nova Disciplina</h2>

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

    <form action="{{ route('disciplinas.store') }}" method="POST">
        @csrf

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

        <div class="form-group mb-3">
            <label for="curso_id">Curso (Opcional):</label>
            <select name="curso_id" id="curso_id" class="form-control @error('curso_id') is-invalid @enderror">
                <option value="">Nenhum Curso</option>
                @foreach($cursos as $curso)
                    <option value="{{ $curso->id }}" {{ old('curso_id') == $curso->id ? 'selected' : '' }}>
                        {{ $curso->nome }}
                    </option>
                @endforeach
            </select>
            @error('curso_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- NOVO CAMPO: Professor Responsável --}}
        <div class="form-group mb-3">
            <label for="professor_id">Professor Responsável:</label>
            <select name="professor_id" id="professor_id" class="form-control @error('professor_id') is-invalid @enderror" required>
                <option value="">Selecione um professor</option>
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
            <a href="{{ route('disciplinas.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
