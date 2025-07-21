@extends('layouts.app')
{{-- resources/views/alunos/boletim_pdf.blade.php --}}
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boletim de {{ $aluno->nome }}</title>

</head>
<body>
    <div class="container">
        <h1>Boletim Escolar</h1>

        <div class="header-info">
            <h2>{{ $aluno->nome }}</h2>
            <p><strong>CPF:</strong> {{ $aluno->cpf }}</p>
            <p><strong>Email:</strong> {{ $aluno->email }}</p>
        </div>

        @if($notas->isEmpty())
            <p class="text-center">Nenhuma nota registrada para este aluno.</p>
        @else
            <h3>Notas por Disciplina</h3>
            <table class="boletim-table">
                <thead>
                    <tr>
                        <th>Disciplina</th>
                        <th>Nota</th>
                        <th>Status</th>
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

        <div class="footer">
            Gerado em: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}
        </div>
    </div>
</body>
</html>
