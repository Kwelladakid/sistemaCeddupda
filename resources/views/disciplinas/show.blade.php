{{-- resources/views/disciplinas/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detalhes da Disciplina</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">{{ $disciplina->nome }}</h3>
        </div>
        <div class="card-body">
            <p><strong>Carga Horária:</strong> {{ $disciplina->carga_horaria }} horas</p>
            <p><strong>Módulo:</strong> {{ $disciplina->modulo }}</p>
            <p><strong>Curso:</strong> {{ $disciplina->curso->nome ?? 'N/A' }}</p>
            {{-- NOVO: Exibindo o professor responsável --}}
            <p><strong>Professor Responsável:</strong> {{ $disciplina->professor->nome ?? 'Não Atribuído' }}</p>
            <div class="mt-3">
                <a href="{{ route('disciplinas.edit', $disciplina->id) }}" class="btn btn-primary">Editar Disciplina</a>
                {{-- NOVO BOTÃO: Atribuir Notas e Faltas --}}
                <a href="{{ route('disciplinas.notas', $disciplina->id) }}" class="btn btn-info">Atribuir Notas e Faltas</a>
                <a href="{{ route('disciplinas.index') }}" class="btn btn-secondary">Voltar para Lista</a>
                <form action="{{ route('disciplinas.destroy', $disciplina->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta disciplina?')">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
