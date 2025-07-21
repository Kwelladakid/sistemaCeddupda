{{-- resources/views/alunos/notas/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Nota para {{ $aluno->nome }}</h2>

    <form action="{{ route('alunos.notas.update', ['aluno' => $aluno->id, 'nota' => $nota->id]) }}" method="POST">
        @csrf
        @method('PUT') {{-- Importante para o método UPDATE --}}
        <div class="mb-3">
            <label for="disciplina_id" class="form-label">Disciplina</label>
            <select class="form-select @error('disciplina_id') is-invalid @enderror" id="disciplina_id" name="disciplina_id" required>
                <option value="">Selecione a Disciplina</option>
                @foreach($disciplinas as $disciplina)
                    <option value="{{ $disciplina->id }}" {{ old('disciplina_id', $nota->disciplina_id) == $disciplina->id ? 'selected' : '' }}>
                        {{ $disciplina->nome }}
                    </option>
                @endforeach
            </select>
            @error('disciplina_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="nota" class="form-label">Nota</label>
            <input type="number" class="form-control @error('nota') is-invalid @enderror" id="nota" name="nota" step="0.01" min="0" max="10" value="{{ old('nota', $nota->nota) }}" required>
            @error('nota')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Atualizar Nota</button>
        <a href="{{ route('alunos.boletim', $aluno->id) }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
