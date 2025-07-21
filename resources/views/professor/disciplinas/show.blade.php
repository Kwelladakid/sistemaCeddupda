@extends('layouts.app') {{-- Ou o layout que você usa --}}

@section('title', 'Gerenciar Alunos da Disciplina')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Gerenciar Alunos</h1>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('dashboard.professor') }}">Meu Dashboard</a></li>
        <li class="breadcrumb-item active">Disciplina: {{ $disciplina->nome }}</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-book me-1"></i>
            Detalhes da Disciplina
        </div>
        <div class="card-body">
            <p><strong>Nome da Disciplina:</strong> {{ $disciplina->nome }}</p>
            <p><strong>Código:</strong> {{ $disciplina->codigo ?? 'N/A' }}</p>
            <p><strong>Carga Horária:</strong> {{ $disciplina->carga_horaria }} horas</p>
            <p><strong>Módulo:</strong> {{ $disciplina->modulo }}</p>
            <p><strong>Curso:</strong> {{ $disciplina->curso->nome ?? 'N/A' }}</p>
            <p><strong>Professor Responsável:</strong> {{ $disciplina->professor->nome ?? 'N/A' }}</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-users me-1"></i>
            Alunos Matriculados
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if($alunos->isEmpty())
                <p>Não há alunos matriculados nesta disciplina através das turmas do curso.</p>
                <p>Verifique se a disciplina está vinculada a um curso e se há alunos matriculados nas turmas desse curso.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Nome do Aluno</th>
                                <th>CPF</th>
                                <th>Matrícula</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alunos as $aluno)
                                <tr>
                                    <td>{{ $aluno->nome }}</td>
                                    <td>{{ $aluno->cpf }}</td>
                                    <td>{{ $aluno->matricula ?? 'N/A' }}</td> {{-- Ajuste para o campo de matrícula do seu modelo Aluno --}}
                                    <td>
                                        {{-- Link para lançar notas e faltas --}}
                                        <a href="{{ route('disciplinas.showNotasForm', $disciplina->id) }}" class="btn btn-sm btn-info me-2">
                                            <i class="fas fa-edit"></i> Lançar Notas/Faltas
                                        </a>
                                        {{-- Adicione outros botões de ação aqui, se necessário --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <a href="{{ route('dashboard.professor') }}" class="btn btn-secondary mt-3">
        <i class="fas fa-arrow-left me-1"></i> Voltar ao Dashboard
    </a>
</div>
@endsection
