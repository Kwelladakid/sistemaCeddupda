{{-- resources/views/disciplinas/notas.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Atribuir Notas e Faltas</h2>
    <h3>Disciplina: {{ $disciplina->nome }}</h3>
    <p><strong>Curso:</strong> {{ $disciplina->curso->nome ?? 'N/A' }}</p>
    <p><strong>Professor:</strong> {{ $disciplina->professor->nome ?? 'Não Atribuído' }}</p>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($alunos->isEmpty())
        <div class="alert alert-warning">
            Não há alunos matriculados neste curso.
        </div>
    @else
        <form action="{{ route('disciplinas.storeNotas', $disciplina->id) }}" method="POST">
            @csrf

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Aluno</th>
                            <th>Nota (0-10)</th>
                            <th>Faltas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alunos as $aluno)
                            <tr>
                                <td>{{ $aluno->nome }}</td>
                                <td>
                                    {{-- Campo oculto para o ID do aluno --}}
                                    <input type="hidden" name="notas[{{ $loop->index }}][aluno_id]" value="{{ $aluno->id }}">

                                    {{-- Campo para a nota --}}
                                    <input type="number" name="notas[{{ $loop->index }}][nota]"
                                           class="form-control @error('notas.'.$loop->index.'.nota') is-invalid @enderror"
                                           step="0.1" min="0" max="10"
                                           value="{{ old('notas.'.$loop->index.'.nota', $notasExistentes[$aluno->id]->nota ?? '') }}">
                                    @error('notas.'.$loop->index.'.nota')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    {{-- Campo para as faltas --}}
                                    <input type="number" name="notas[{{ $loop->index }}][faltas]"
                                           class="form-control @error('notas.'.$loop->index.'.faltas') is-invalid @enderror"
                                           min="0"
                                           value="{{ old('notas.'.$loop->index.'.faltas', $notasExistentes[$aluno->id]->faltas ?? 0) }}">
                                    @error('notas.'.$loop->index.'.faltas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-success">Salvar Notas e Faltas</button>
                <a href="{{ route('disciplinas.show', $disciplina->id) }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    @endif
</div>
@endsection
