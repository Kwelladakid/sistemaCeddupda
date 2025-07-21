@extends('layouts.app')

<!-- Seção de Alunos da Turma -->
@section('content')
<div class="card mt-4">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Alunos Matriculados</h3>
        <div>
            <a href="{{ route('turmas.alunos.adicionar', $turma->id) }}" class="btn btn-light">Adicionar Aluno Existente</a>
            <a href="{{ route('matriculas.novo_aluno', $turma->id) }}" class="btn btn-light">Cadastrar Novo Aluno</a>
        </div>
    </div>

    <div class="card-body">
        @if($turma->alunos->isEmpty())
            <p>Nenhum aluno matriculado nesta turma.</p>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>Email</th>
                            <th>Data da Matrícula</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($turma->alunos as $aluno)
                            <tr>
                                <td>{{ $aluno->nome }}</td>
                                <td>{{ $aluno->cpf }}</td>
                                <td>{{ $aluno->email }}</td>
                                <td>{{ \Carbon\Carbon::parse($aluno->pivot->data_matricula)->format('d/m/Y') }}</td>
                                <td>
                                    @switch($aluno->pivot->status)
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
                                            <span class="badge bg-secondary">{{ ucfirst($aluno->pivot->status) }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('alunos.show', $aluno->id) }}" class="btn btn-info btn-sm">Detalhes</a>
                                        <form action="{{ route('turmas.alunos.remover', ['turma' => $turma->id, 'aluno' => $aluno->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja remover este aluno da turma?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-warning btn-sm">Remover da Turma</button>
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
