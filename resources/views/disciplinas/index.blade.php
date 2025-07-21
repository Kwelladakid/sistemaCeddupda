{{-- resources/views/disciplinas/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Lista de Disciplinas')

@section('content')
<div class="container-fluid"> {{-- Alterado de 'container' para 'container-fluid' para consistência com app.blade.php --}}
    <div class="main-content"> {{-- Adicionado para consistência com o layout principal --}}
        <h2>Lista de Disciplinas</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Botão "Cadastrar Nova Disciplina" --}}
        <a href="{{ route('disciplinas.create') }}" class="action-icon-btn create mb-3">
            <i class="fas fa-plus-square"></i> Cadastrar Nova Disciplina
        </a>

        @if($disciplinas->isEmpty())
            <p>Nenhuma disciplina cadastrada.</p>
        @else
            {{-- REMOVIDO: <div class="table-responsive"> --}}
            {{-- ADICIONADO: classes table-striped e table-hover --}}
            <table class="table table-striped table-hover">
                {{-- ADICIONADO: classe table-dark --}}
                <thead class="table-dark">
                    <tr>
                        <th>Nome</th>
                        <th>Carga Horária</th>
                        <th>Módulo</th>
                        <th>Curso</th>
                        <th>Professor</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                        @foreach($disciplinas as $disciplina)
                            <tr>
                                {{-- ADICIONADO: atributos data-label para cada td --}}
                                <td data-label="Nome">{{ $disciplina->nome }}</td>
                                <td data-label="Carga Horária">{{ $disciplina->carga_horaria }} horas</td>
                                <td data-label="Módulo">{{ $disciplina->modulo }}</td>
                                <td data-label="Curso">{{ $disciplina->curso->nome ?? 'N/A' }}</td>
                                <td data-label="Professor">{{ $disciplina->professor->nome ?? 'Não Atribuído' }}</td>
                                <td data-label="Ações">
                                    {{-- Botão "Detalhes" --}}
                                    <a href="{{ route('disciplinas.show', $disciplina->id) }}" class="action-icon-btn details" title="Detalhes">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    {{-- Botão "Editar" --}}
                                    <a href="{{ route('disciplinas.edit', $disciplina->id) }}" class="action-icon-btn edit" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    {{-- Botão "Excluir" --}}
                                    <form action="{{ route('disciplinas.destroy', $disciplina->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-icon-btn delete" onclick="return confirm('Tem certeza que deseja excluir esta disciplina?')" title="Excluir">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                </tbody>
            </table>
            {{-- REMOVIDO: </div> (fechamento do table-responsive) --}}
        @endif
    </div> {{-- Fechamento da div main-content --}}
</div> {{-- Fechamento da div container-fluid --}}
@endsection
