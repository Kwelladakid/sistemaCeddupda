@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Cadastrar e Matricular Novo Aluno no Curso</h2>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">Curso: {{ $curso->nome }}</h3>
        </div>
        <div class="card-body">
            <p><strong>Descrição:</strong> {{ $curso->descricao }}</p>
            <p><strong>Carga Horária:</strong> {{ $curso->carga_horaria }} horas</p>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('cursos.criar-e-matricular', $curso->id) }}" method="POST">
        @csrf

        <h4>Dados do Aluno</h4>

        <div class="form-group mb-3">
            <label for="nome">Nome Completo:</label>
            <input type="text" name="nome" id="nome" class="form-control @error('nome') is-invalid @enderror" value="{{ old('nome') }}" required>
            @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-group mb-3">
            <label for="data_nascimento">Data de Nascimento:</label>
            <input type="date" name="data_nascimento" id="data_nascimento" class="form-control @error('data_nascimento') is-invalid @enderror" value="{{ old('data_nascimento') }}" required>
            @error('data_nascimento')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-group mb-3">
            <label for="cpf">CPF:</label>
            <input type="text" name="cpf" id="cpf" class="form-control @error('cpf') is-invalid @enderror" value="{{ old('cpf') }}" required>
            @error('cpf')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-group mb-3">
            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-group mb-3">
            <label for="telefone">Telefone:</label>
            <input type="text" name="telefone" id="telefone" class="form-control @error('telefone') is-invalid @enderror" value="{{ old('telefone') }}" required>
            @error('telefone')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-group mb-3">
            <label for="endereco">Endereço:</label>
            <textarea name="endereco" id="endereco" class="form-control @error('endereco') is-invalid @enderror" rows="3" required>{{ old('endereco') }}</textarea>
            @error('endereco')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- NOVO CAMPO: Status do Aluno --}}
        <div class="form-group mb-3">
            <label for="status_aluno">Status do Aluno:</label>
            <select name="status_aluno" id="status_aluno" class="form-control @error('status_aluno') is-invalid @enderror" required>
                <option value="ativo" {{ old('status_aluno', 'ativo') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                <option value="inativo" {{ old('status_aluno') == 'inativo' ? 'selected' : '' }}>Inativo</option>
                <option value="trancada" {{ old('status_aluno') == 'trancada' ? 'selected' : '' }}>Trancado</option>
                <option value="formado" {{ old('status_aluno') == 'formado' ? 'selected' : '' }}>Formado</option>
            </select>
            @error('status_aluno')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <h4>Dados da Matrícula</h4>

        <div class="form-group mb-3">
            <label for="turma_id">Selecione a Turma:</label>
            <select name="turma_id" id="turma_id" class="form-control @error('turma_id') is-invalid @enderror" required>
                <option value="">Selecione uma turma</option>
                @foreach($turmasDoCurso as $turma) {{-- Alterado $turmasDoCurso para $turma --}}
                    <option value="{{ $turma->id }}" {{ old('turma_id') == $turma->id ? 'selected' : '' }}>{{ $turma->nome }} ({{ $turma->ano }} - {{ $turma->periodo ?? 'Não definido' }})</option>
                @endforeach
            </select>
            @error('turma_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-group mb-3">
            <label for="status_matricula">Status da Matrícula:</label>
            <select name="status_matricula" id="status_matricula" class="form-control @error('status_matricula') is-invalid @enderror" required>
                <option value="ativa" {{ old('status_matricula', 'ativa') == 'ativa' ? 'selected' : '' }}>Ativa</option>
                <option value="trancada" {{ old('status_matricula') == 'trancada' ? 'selected' : '' }}>Trancada</option>
                <option value="cancelada" {{ old('status_matricula') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                <option value="concluida" {{ old('status_matricula') == 'concluida' ? 'selected' : '' }}>Concluída</option>
            </select>
            @error('status_matricula')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-success">Cadastrar e Matricular</button>
            <a href="{{ route('cursos.show', $curso->id) }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
