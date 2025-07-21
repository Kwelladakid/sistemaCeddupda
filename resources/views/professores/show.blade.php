{{-- resources/views/professores/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detalhes do Professor</h2>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">{{ $professore->nome }}</h3> {{-- ALTERADO AQUI --}}
        </div>
        <div class="card-body">
            <p><strong>CPF:</strong> {{ $professore->cpf }}</p> {{-- ALTERADO AQUI --}}
            <p><strong>Especialidade:</strong> {{ $professore->especialidade }}</p> {{-- ALTERADO AQUI --}}
            <p><strong>Telefone:</strong> {{ $professore->telefone }}</p> {{-- ALTERADO AQUI --}}
            <p><strong>Email:</strong> {{ $professore->email }}</p> {{-- ALTERADO AQUI --}}
            <p><strong>Criado em:</strong> {{ $professore->created_at ? $professore->created_at->format('d/m/Y H:i:s') : 'N/A' }}</p> {{-- ALTERADO AQUI --}}
            <p><strong>Última atualização:</strong> {{ $professore->updated_at ? $professore->updated_at->format('d/m/Y H:i:s') : 'N/A' }}</p> {{-- ALTERADO AQUI --}}

            <div class="mt-3">
                <a href="{{ route('professores.edit', ['professore' => $professore->id]) }}" class="btn btn-warning">Editar Professor</a> {{-- ALTERADO AQUI --}}

                <a href="{{ route('professores.index') }}" class="btn btn-secondary">Voltar para a Lista</a>

                <form action="{{ route('professores.destroy', ['professore' => $professore->id]) }}" method="POST" style="display:inline;"> {{-- ALTERADO AQUI --}}
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este professor?')">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
