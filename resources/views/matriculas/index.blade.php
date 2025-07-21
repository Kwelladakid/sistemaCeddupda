@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="main-content">
        <h1>Lista de Matrículas</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('matriculas.create') }}" class="btn btn-primary" style="margin-bottom: 20px;">Nova Matrícula</a>

        @if ($matriculas->isEmpty())
            <p>Nenhuma matrícula encontrada.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Aluno</th>
                        <th>Curso</th>
                        <th>Data da Matrícula</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($matriculas as $matricula)
                        <tr>
                            <td>{{ $matricula->id }}</td>
                            <td>{{ $matricula->aluno->nome ?? 'N/A' }}</td> {{-- Assumindo relacionamento com Aluno --}}
                            <td>{{ $matricula->curso->nome ?? 'N/A' }}</td> {{-- Assumindo relacionamento com Curso --}}
                            <td>{{ $matricula->data_matricula->format('d/m/Y') ?? 'N/A' }}</td> {{-- Assumindo campo data_matricula --}}
                            <td>{{ $matricula->status ?? 'N/A' }}</td> {{-- Assumindo campo status --}}
                            <td>
                                <a href="{{ route('matriculas.show', $matricula->id) }}" class="btn btn-info">Ver</a>
                                <a href="{{ route('matriculas.edit', $matricula->id) }}" class="btn btn-warning">Editar</a>
                                <form action="{{ route('matriculas.destroy', $matricula->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta matrícula?')">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- Se você usou paginate() no controller, descomente a linha abaixo --}}
            {{-- {{ $matriculas->links() }} --}}
        @endif
    </div>
</div>
@endsection
