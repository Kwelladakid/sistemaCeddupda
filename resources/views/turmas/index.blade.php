@extends('layouts.app')

@section('title', 'Lista de Turmas') {{-- Adicionei o title, se não tiver --}}

@section('content')
<div class="container-fluid"> {{-- Alterado de 'container' para 'container-fluid' --}}
    <div class="main-content"> {{-- Adicionado para consistência com o layout principal --}}
        <h2>Lista de Turmas</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Botão "Cadastrar Nova Turma" padronizado com action-icon-btn --}}
        <a href="{{ route('turmas.create') }}" class="action-icon-btn create mb-3">
            <i class="fas fa-plus-square"></i> Cadastrar Nova Turma
        </a>

        @if($turmas->isEmpty())
            <p>Nenhuma turma cadastrada.</p>
        @else
            {{-- Removido o table-responsive aqui, pois o CSS fará a transformação --}}
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Nome</th>
                        <th>Curso</th>
                        <th>Professor</th>
                        <th>Ano</th>
                        <th>Período</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($turmas as $turma)
                        <tr>
                            <td data-label="Nome">{{ $turma->nome }}</td>
                            <td data-label="Curso">
                                @if($turma->curso)
                                    <a href="{{ route('cursos.show', $turma->curso_id) }}">
                                        {{ $turma->curso->nome }}
                                    </a>
                                @else
                                    <span class="text-muted">Não definido</span>
                                @endif
                            </td>
                            <td data-label="Professor">
                                @if($turma->professor)
                                    {{ $turma->professor->nome }}
                                @else
                                    <span class="text-muted">Não atribuído</span>
                                @endif
                            </td>
                            <td data-label="Ano">{{ $turma->ano }}</td>
                            <td data-label="Período">{{ $turma->periodo ?: 'Não definido' }}</td>
                            <td data-label="Status">
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
                                        <span class="badge bg-warning">{{ ucfirst($turma->status) }}</span>
                                @endswitch
                            </td>
                            <td data-label="Ações">
                                {{-- Botões de ação padronizados com action-icon-btn --}}
                                <a href="{{ route('turmas.show', $turma->id) }}" class="action-icon-btn details" title="Detalhes">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('turmas.edit', $turma->id) }}" class="action-icon-btn edit" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('turmas.destroy', $turma->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-icon-btn delete" onclick="return confirm('Tem certeza que deseja excluir esta turma?')" title="Excluir">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div> {{-- Fechamento da div main-content --}}
</div> {{-- Fechamento da div container-fluid --}}
@endsection
