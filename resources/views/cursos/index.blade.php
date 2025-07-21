{{-- resources/views/cursos/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Lista de Cursos')

@section('content')
<div class="container-fluid">
    <div class="main-content">
        <h2>Lista de Cursos</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Botão "Cadastrar Novo Curso" --}}
        <a href="{{ route('cursos.create') }}" class="action-icon-btn create mb-3">
            <i class="fas fa-plus-square"></i> Cadastrar Novo Curso
        </a>

        @if($cursos->isEmpty())
            <p>Nenhum curso cadastrado.</p>
        @else
            {{-- Adicionadas as classes table-striped e table-hover --}}
            <table class="table table-striped table-hover">
                {{-- Adicionada a classe table-dark --}}
                <thead class="table-dark">
                    <tr>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Carga Horária</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cursos as $curso)
                        <tr>
                            {{-- Adicionados os atributos data-label --}}
                            <td data-label="Nome">{{ $curso->nome }}</td>
                            <td data-label="Descrição">{{ Str::limit($curso->descricao, 50) }}</td>
                            <td data-label="Carga Horária">{{ $curso->carga_horaria }} horas</td>
                            <td data-label="Ações">
                                {{-- Botão "Detalhes" --}}
                                <a href="{{ route('cursos.show', $curso->id) }}" class="action-icon-btn details" title="Detalhes">
                                    <i class="fas fa-eye"></i>
                                </a>
                                {{-- Botão "Editar" --}}
                                <a href="{{ route('cursos.edit', $curso->id) }}" class="action-icon-btn edit" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>

                                {{-- Botão "Ver Turmas" --}}
                                <a href="{{ route('cursos.show', $curso->id) }}#turmas" class="action-icon-btn view-turmas" title="Ver Turmas">
                                    <i class="fas fa-users"></i>
                                </a>

                                {{-- Botão "Excluir" --}}
                                <form action="{{ route('cursos.destroy', $curso->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-icon-btn delete" onclick="return confirm('Tem certeza que deseja excluir este curso?')" title="Excluir">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
