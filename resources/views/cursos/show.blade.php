{{-- resources/views/cursos/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detalhes do Curso</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">{{ $curso->nome }}</h3>
        </div>
        <div class="card-body">
            <p><strong>Descrição:</strong> {{ $curso->descricao }}</p>
            <p><strong>Carga Horária:</strong> {{ $curso->carga_horaria }} horas</p>
            <div class="mt-3">
                <a href="{{ route('cursos.edit', $curso->id) }}" class="btn btn-primary">Editar Curso</a>
                <a href="{{ route('cursos.index') }}" class="btn btn-secondary">Voltar para Lista</a>
                <a href="{{ route('cursos.matricular', $curso->id) }}" class="btn btn-success">Matricular Aluno</a>

                {{-- NOVO BOTÃO: Acesso à lista geral de disciplinas --}}
                <a href="{{ route('disciplinas.index') }}" class="btn btn-info">Ver Todas as Disciplinas</a>

                <form action="{{ route('cursos.destroy', $curso->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este curso?')">Excluir</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Seção de Turmas do Curso com âncora para navegação direta -->
    <div class="card mb-4" id="turmas">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Turmas deste Curso</h3>
            <a href="{{ route('cursos.turmas.create', $curso->id) }}" class="btn btn-light">Adicionar Nova Turma</a>
        </div>
        <div class="card-body">
            @if($curso->turmas->isEmpty())
                <p>Nenhuma turma cadastrada para este curso.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Nome</th>
                                <th>Ano</th>
                                <th>Período</th>
                                <th>Professor</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($curso->turmas as $turma)
                                <tr>
                                    <td>{{ $turma->nome }}</td>
                                    <td>{{ $turma->ano }}</td>
                                    <td>{{ $turma->periodo ?? 'Não definido' }}</td>
                                    <td>{{ $turma->professor->nome ?? 'Não atribuído' }}</td>
                                    <td>
                                        @switch($turma->status)
                                            @case('ativa')
                                                <span class="badge bg-success">Ativa</span>
                                                @break
                                            @case('inativa')
                                                <span class="badge bg-secondary">Inativa</span>
                                                @break
                                            @case('concluida')
                                                <span class="badge bg-primary">Concluída</span>
                                                @break
                                            @case('cancelada')
                                                <span class="badge bg-danger">Cancelada</span>
                                                @break
                                            @default
                                                <span class="badge bg-warning">{{ ucfirst($turma->status ?? 'Não definido') }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('turmas.show', $turma->id) }}" class="btn btn-info btn-sm">Detalhes</a>
                                            <a href="{{ route('turmas.edit', $turma->id) }}" class="btn btn-primary btn-sm">Editar</a>
                                            <form action="{{ route('turmas.destroy', $turma->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir esta turma?')">Excluir</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Seção de Disciplinas do Curso -->
    <div class="card mb-4" id="disciplinas">
        <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Disciplinas deste Curso</h3>
            <a href="{{ route('cursos.disciplinas.create', $curso->id) }}" class="btn btn-light">Adicionar Nova Disciplina</a>
        </div>
        <div class="card-body">
            @if($curso->disciplinas->isEmpty())
                <p>Nenhuma disciplina cadastrada para este curso.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Nome</th>
                                <th>Carga Horária</th>
                                <th>Módulo</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($curso->disciplinas as $disciplina)
                                <tr>
                                    <td>{{ $disciplina->nome }}</td>
                                    <td>{{ $disciplina->carga_horaria }} horas</td>
                                    <td>{{ $disciplina->modulo }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('disciplinas.show', $disciplina->id) }}" class="btn btn-info btn-sm">Detalhes</a>
                                            <a href="{{ route('disciplinas.edit', $disciplina->id) }}" class="btn btn-primary btn-sm">Editar</a>
                                            <form action="{{ route('disciplinas.destroy', $disciplina->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir esta disciplina?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Seção de Alunos Matriculados no Curso -->
    <div class="card">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Alunos Matriculados no Curso</h3>
            <div>
                <a href="{{ route('cursos.matricular', $curso->id) }}" class="btn btn-light">Matricular Aluno Existente</a>
                <a href="{{ route('cursos.novo-aluno', $curso->id) }}" class="btn btn-light">Cadastrar e Matricular</a>
            </div>
        </div>

        <div class="card-body">
            @php
                $alunosMatriculados = collect([]);
                foreach($curso->turmas as $turma) {
                    $alunosMatriculados = $alunosMatriculados->concat($turma->alunos);
                }
                $alunosMatriculados = $alunosMatriculados->unique('id');
            @endphp

            @if($alunosMatriculados->isEmpty())
                <p>Nenhum aluno matriculado neste curso.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Nome</th>
                                <th>CPF</th>
                                <th>Email</th>
                                <th>Turma</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($curso->turmas as $turma)
                                @foreach($turma->alunos as $aluno)
                                    <tr>
                                        <td>{{ $aluno->nome }}</td>
                                        <td>{{ $aluno->cpf }}</td>
                                        <td>{{ $aluno->email }}</td>
                                        <td>{{ $turma->nome }}</td>
                                        <td>
                                            @switch($aluno->pivot->status ?? 'ativa')
                                                @case('ativa')
                                                    <span class="badge bg-success">Ativa</span>
                                                    @break
                                                @case('trancada')
                                                    <span class="badge bg-warning">Trancada</span>
                                                    @break
                                                @case('cancelada')
                                                    <span class="badge bg-danger">Cancelada</span>
                                                    @break
                                                @case('concluida')
                                                    <span class="badge bg-primary">Concluída</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ ucfirst($aluno->pivot->status ?? 'Não definido') }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('alunos.show', $aluno->id) }}" class="btn btn-info btn-sm">Detalhes do Aluno</a>
                                                <a href="{{ route('turmas.show', $turma->id) }}" class="btn btn-primary btn-sm">Ver Turma</a>
                                                <form action="{{ route('turmas.alunos.remover', ['turma' => $turma->id, 'aluno' => $aluno->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja remover este aluno da turma?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-warning btn-sm">Remover da Turma</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
