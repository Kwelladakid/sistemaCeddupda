@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Adicionar Aluno à Turma</h2>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">Turma: {{ $turma->nome }}</h3>
        </div>
        <div class="card-body">
            <p><strong>Curso:</strong> {{ $turma->curso->nome ?? 'Não definido' }}</p>
            <p><strong>Professor:</strong> {{ $turma->professor->nome ?? 'Não atribuído' }}</p>
            <p><strong>Ano:</strong> {{ $turma->ano }}</p>
            <p><strong>Período:</strong> {{ $turma->periodo ?? 'Não definido' }}</p>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($alunosDisponiveis->isEmpty())
        <div class="alert alert-info">
            Não há alunos disponíveis para matricular nesta turma.
        </div>
        <a href="{{ route('turmas.show', $turma->id) }}" class="btn btn-secondary">Voltar para Turma</a>
        <a href="{{ route('matriculas.novo_aluno', $turma->id) }}" class="btn btn-primary">Cadastrar Novo Aluno</a>
    @else
        <form action="{{ route('turmas.alunos.vincular', $turma->id) }}" method="POST">
            @csrf

            <div class="form-group mb-3">
                <label for="aluno_id">Selecione o Aluno:</label>
                <select name="aluno_id" id="aluno_id" class="form-control" required>
                    <option value="">Selecione um aluno</option>
                    @foreach($alunosDisponiveis as $aluno)
                        <option value="{{ $aluno->id }}">{{ $aluno->nome }} (CPF: {{ $aluno->cpf }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="data_matricula">Data da Matrícula:</label>
                <input type="date" name="data_matricula" id="data_matricula" class="form-control" value="{{ date('Y-m-d') }}">
            </div>

            <div class="form-group mb-3">
                <label for="status">Status da Matrícula:</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="ativa" selected>Ativa</option>
                    <option value="trancada">Trancada</option>
                    <option value="cancelada">Cancelada</option>
                    <option value="concluida">Concluída</option>
                </select>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-success">Matricular Aluno</button>
                <a href="{{ route('turmas.show', $turma->id) }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    @endif
</div>
@endsection
