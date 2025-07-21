@extends('layouts.app') {{-- Ou o layout que você usa --}}

@section('title', 'Dashboard do Professor')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard do Professor</h1>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Meu Dashboard</li>
    </ol>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-book me-1"></i>
            Minhas Disciplinas
        </div>
        <div class="card-body">
            @if($disciplinas->isEmpty())
                <p>Você não está vinculado a nenhuma disciplina no momento.</p>
            @else
                <ul class="list-group">
                    @foreach($disciplinas as $disciplina)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{-- Transforma o nome da disciplina em um link para selecionar --}}
                            <a href="{{ route('dashboard.professor', ['disciplina_id' => $disciplina->id]) }}" class="text-decoration-none">
                                {{ $disciplina->nome }} ({{ $disciplina->codigo ?? 'N/A' }})
                            </a>
                            {{-- Indica visualmente a disciplina selecionada --}}
                            @if(isset($selectedDisciplina) && $selectedDisciplina->id == $disciplina->id)
                                <span class="badge bg-primary rounded-pill">Selecionada</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    {{-- Seção para exibir os alunos da disciplina selecionada --}}
    @if(isset($selectedDisciplina))
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-users me-1"></i>
                Alunos da Disciplina: {{ $selectedDisciplina->nome }}
            </div>
            <div class="card-body">
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
                                        <td>{{ $aluno->matricula ?? 'N/A' }}</td>
                                        <td>
                                            {{-- Link para lançar notas e faltas.
                                                A rota 'disciplinas.notas' já existe no seu DisciplinaController.
                                                Ela espera o ID da disciplina.
                                            --}}
                                            <a href="{{ route('disciplinas.notas', $selectedDisciplina->id) }}" class="btn btn-sm btn-info me-2">
                                                <i class="fas fa-edit"></i> Lançar Notas/Faltas
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
