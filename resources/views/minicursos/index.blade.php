{{-- resources/views/minicursos/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Lista de Minicursos')

@section('content')
<div class="container-fluid">
    <div class="main-content">
        <h2>Minicursos</h2>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Botão "Novo Minicurso" padronizado com action-icon-btn --}}
        <a href="{{ route('minicursos.create') }}" class="action-icon-btn create mb-3">
            <i class="fas fa-plus-circle"></i> Novo Minicurso
        </a>

        @if (count($minicursos) > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Nome</th>
                            <th>Professor</th>
                            <th>Carga Horária</th>
                            <th>Participantes</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($minicursos as $minicurso)
                            <tr>
                                <td>{{ $minicurso->nome }}</td>
                                <td>{{ $minicurso->professor_responsavel }}</td>
                                <td>{{ $minicurso->carga_horaria }} horas</td>
                                <td>{{ $minicurso->participantes->count() }}</td>
                                <td>
                                    {{-- Botões de ação (apenas ícones, texto no title para tooltip) --}}
                                    <a href="{{ route('minicursos.show', $minicurso) }}" class="action-icon-btn details" title="Detalhes">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('minicursos.edit', $minicurso) }}" class="action-icon-btn edit" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('minicursos.destroy', $minicurso) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-icon-btn delete" onclick="return confirm('Tem certeza que deseja excluir este minicurso?');" title="Excluir">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center">Nenhum minicurso cadastrado.</p>
            <div class="text-center">
                {{-- Botão "Cadastrar Minicurso" padronizado com action-icon-btn --}}
                <a href="{{ route('minicursos.create') }}" class="action-icon-btn create">
                    <i class="fas fa-plus-circle"></i> Cadastrar Minicurso
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
