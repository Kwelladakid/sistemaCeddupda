{{-- resources/views/alunos/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Lista de Alunos')

@section('content')
<div class="container-fluid">
    <div class="main-content">
        <h2>Lista de Alunos</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Botão "Cadastrar Novo Aluno" (mantém texto visível) --}}
        <a href="{{ route('alunos.create') }}" class="action-icon-btn create mb-3">
            <i class="fas fa-user-plus"></i> Cadastrar Novo Aluno
        </a>

        @if($alunos->isEmpty())
            <p>Nenhum aluno cadastrado.</p>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>Email (Login)</th>
                            <th>Telefone</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alunos as $aluno)
                            <tr>
                                <td>{{ $aluno->nome }}</td>
                                <td>{{ $aluno->cpf }}</td>
                                <td>
                                    @if ($aluno->user)
                                        {{ $aluno->user->email }}
                                    @else
                                        <span class="text-muted">Não registrado</span>
                                    @endif
                                </td>
                                <td>{{ $aluno->telefone }}</td>
                                <td>
                                    @php
                                        $statusClass = '';
                                        switch($aluno->status) {
                                            case 'ativo':
                                            case 'matriculado':
                                                $statusClass = 'bg-success';
                                                break;
                                            case 'inativo':
                                            case 'cancelado':
                                                $statusClass = 'bg-danger';
                                                break;
                                            case 'trancado':
                                                $statusClass = 'bg-warning';
                                                break;
                                            case 'concluido':
                                                $statusClass = 'bg-info';
                                                break;
                                            default:
                                                $statusClass = 'bg-secondary'; // Para status desconhecidos
                                                break;
                                        }
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ ucfirst($aluno->status) }}</span>
                                </td>
                                <td>
                                    {{-- Botões de ação (apenas ícones, texto no title para tooltip) --}}
                                    <a href="{{ route('alunos.show', $aluno->id) }}" class="action-icon-btn details" title="Detalhes">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('alunos.edit', $aluno->id) }}" class="action-icon-btn edit" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('alunos.boletim', $aluno->id) }}" class="action-icon-btn view-boletim" title="Ver Boletim">
                                        <i class="fas fa-file-alt"></i>
                                    </a>

                                    @if (!$aluno->user)
                                        <form action="{{ route('alunos.confirmarMatricula', $aluno->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="action-icon-btn confirm-matricula" title="Confirmar Matrícula">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('alunos.destroy', $aluno->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-icon-btn delete" onclick="return confirm('Tem certeza que deseja excluir este aluno?')" title="Excluir">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
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
