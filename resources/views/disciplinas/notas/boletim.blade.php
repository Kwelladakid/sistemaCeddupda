@extends('layouts.app')

@section('content')
    <h2>Boletim de {{ $aluno->nome }}</h2>

    @if(session('success'))
        <div>{{ session('success') }}</div>
    @endif

    <a href="{{ route('alunos.notas.create', $aluno->id) }}">Registrar Nova Nota</a>

    <table border="1">
        <thead>
            <tr>
                <th>Disciplina</th>
                <th>Nota</th>
            </tr>
        </thead>
        <tbody>
            @forelse($notas as $nota)
                <tr>
                    <td>{{ $nota->disciplina->nome }}</td>
                    <td>{{ $nota->nota }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">Nenhuma nota registrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
