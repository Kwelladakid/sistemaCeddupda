{{-- resources/views/alunos/boletim.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="boletim-container">
    <div class="boletim-header">
        <h2>Boletim de {{ $aluno->nome }}</h2>
        <p><strong>CPF:</strong> {{ $aluno->cpf }}</p>
        <p><strong>Email:</strong> {{ $aluno->email }}</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('alunos.notas.create', $aluno->id) }}" class="btn btn-primary">Registrar Nova Nota</a>
    <a href="{{ route('alunos.index') }}" class="btn btn-secondary">Voltar para Lista de Alunos</a>

    {{-- NOVO BOTÃO: Gerar PDF --}}
    <a href="{{ route('alunos.boletim.pdf', $aluno->id) }}" class="btn btn-info" >Gerar PDF</a>

    @if($notas->isEmpty())
        <p>Nenhuma nota registrada para este aluno.</p>
    @else
        <h3>Notas por Disciplina</h3>
        <table class="boletim-table">
            <thead>
                <tr>
                    <th>Disciplina</th>
                    <th>Nota</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notas as $nota)
                    <tr>
                        <td>{{ $nota->disciplina->nome }}</td>
                        <td>{{ number_format($nota->nota, 2, ',', '.') }}</td>
                        <td class="{{ $nota->getStatusCssClass($notaMinima) }}">
                            {{ $nota->getStatusAprovacao($notaMinima) }}
                        </td>
                        <td class="actions">
                            <a href="{{ route('alunos.notas.edit', ['aluno' => $aluno->id, 'nota' => $nota->id]) }}" class="btn btn-primary">Editar</a>
                            <form action="{{ route('alunos.notas.destroy', ['aluno' => $aluno->id, 'nota' => $nota->id]) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta nota?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="resumo">
            <h3>Resumo do Desempenho</h3>
            <p><strong>Média Geral:</strong>
                @if($mediaGeral !== null)
                    <span class="{{ $mediaGeral >= $notaMinima ? 'aprovado' : 'reprovado' }}">
                        {{ number_format($mediaGeral, 2, ',', '.') }}
                    </span>
                @else
                    N/A
                @endif
            </p>
            <p><strong>Disciplinas Cursadas:</strong> {{ $notas->count() }}</p>
            <p><strong>Disciplinas Aprovadas:</strong> {{ $disciplinasAprovadas }}</p>
            <p><strong>Disciplinas Reprovadas:</strong> {{ $disciplinasReprovadas }}</p>
            <p><strong>Percentual de Aprovação:</strong>
                @if($notas->count() > 0)
                    {{ number_format(($disciplinasAprovadas / $notas->count()) * 100, 1, ',', '.') }}%
                @else
                    N/A
                @endif
            </p>
            <p><strong>Status Geral:</strong>
                @if($mediaGeral !== null)
                    @if($disciplinasReprovadas == 0)
                        <span class="aprovado">Aprovado em todas as disciplinas</span>
                    @else
                        <span class="reprovado">Reprovado em {{ $disciplinasReprovadas }} disciplina(s)</span>
                    @endif
                @else
                    N/A
                @endif
            </p>
        </div>
    @endif
</div>
@endsection
