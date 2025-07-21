@extends('layouts.app')

<!-- Seção de Detalhes do Aluno -->
@section('content')

<div class="card mt-4">
    <div class="card-header bg-primary text-white">
        <h3 class="mb-0">Detalhes do Aluno</h3>
    </div>
    <div class="card-body">
        <p><strong>Nome:</strong> {{ $aluno->nome }}</p>
        <p><strong>CPF:</strong> {{ $aluno->cpf }}</p>
        <p><strong>Email (Login):</strong>
            @if ($aluno->user)
                {{ $aluno->user->email }}
            @else
                <span class="text-muted">Não registrado</span>
            @endif
        </p>
        <p><strong>Senha (CPF):</strong>
            @if ($aluno->user)
                {{ $aluno->cpf }}
            @else
                <span class="text-muted">Não registrado</span>
            @endif
        </p>
    </div>
</div>

<!-- Seção de Turmas do Aluno -->
<div class="card mt-4">
    <div class="card-header bg-info text-white">
        <h3 class="mb-0">Turmas Matriculadas</h3>
    </div>

    <div class="card-body">
        @if($aluno->turmas->isEmpty())
            <p>Este aluno não está matriculado em nenhuma turma.</p>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Turma</th>
                            <th>Curso</th>
                            <th>Ano/Período</th>
                            <th>Data da Matrícula</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($aluno->turmas as $turma)
                            <tr>
                                <td>{{ $turma->nome }}</td>
                                <td>{{ $turma->curso->nome ?? 'Não definido' }}</td>
                                <td>{{ $turma->ano }} - {{ $turma->periodo ?? 'Não definido' }}</td>
                                <td>{{ \Carbon\Carbon::parse($turma->pivot->data_matricula)->format('d/m/Y') }}</td>
                                <td>
                                    @switch($turma->pivot->status)
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
                                            <span class="badge bg-secondary">{{ ucfirst($turma->pivot->status) }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('turmas.show', $turma->id) }}" class="btn btn-info btn-sm">Ver Turma</a>
                                        <form action="{{ route('turmas.alunos.remover', ['turma' => $turma->id, 'aluno' => $aluno->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja remover este aluno da turma?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-warning btn-sm">Cancelar Matrícula</button>
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

@endsection
