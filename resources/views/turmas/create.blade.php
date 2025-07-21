@extends('layouts.app')

@section('title', 'Cadastrar Nova Turma')

@section('content')
<div class="container-fluid">
    <div class="main-content">
        <h2>Cadastrar Nova Turma</h2>

        {{-- Exibição de erros de validação --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('turmas.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="nome">Nome da Turma:</label>
                <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome') }}" required>
            </div>

            <div class="form-group">
                <label for="ano">Ano:</label>
                <input type="number" class="form-control" id="ano" name="ano" value="{{ old('ano', date('Y')) }}" required min="1900" max="{{ date('Y') + 5 }}">
            </div>

            <div class="form-group">
                <label for="periodo">Período (Ex: Manhã, Tarde, Noite):</label>
                <input type="text" class="form-control" id="periodo" name="periodo" value="{{ old('periodo') }}">
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="ativa" {{ old('status') == 'ativa' ? 'selected' : '' }}>Ativa</option>
                    <option value="inativa" {{ old('status') == 'inativa' ? 'selected' : '' }}>Inativa</option>
                    <option value="concluida" {{ old('status') == 'concluida' ? 'selected' : '' }}>Concluída</option>
                    <option value="cancelada" {{ old('status') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                </select>
            </div>

            <div class="form-group">
                <label for="professor_id">Professor Responsável:</label>
                <select class="form-control" id="professor_id" name="professor_id" required>
                    <option value="">Selecione um Professor</option>
                    @foreach($professores as $professor)
                        <option value="{{ $professor->id }}" {{ old('professor_id') == $professor->id ? 'selected' : '' }}>
                            {{ $professor->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="curso_id">Curso:</label>
                <select class="form-control" id="curso_id" name="curso_id" required>
                    <option value="">Selecione um Curso</option>
                    @foreach($cursos as $curso)
                        <option value="{{ $curso->id }}" {{ old('curso_id') == $curso->id ? 'selected' : '' }}>
                            {{ $curso->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Cadastrar Turma
            </button>
            <a href="{{ route('turmas.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </form>
    </div>
</div>
@endsection
