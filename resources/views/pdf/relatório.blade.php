<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Pagamentos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 14px;
            margin-bottom: 20px;
        }
        .filters {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }
        .filter-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .summary {
            margin-top: 20px;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">RELATÓRIO DE PAGAMENTOS</div>
        <div class="subtitle">{{ config('app.name') }}</div>
    </div>

    <div class="filters">
        <div class="filter-title">Filtros aplicados:</div>
        <div>
            @if(isset($filtros['aluno_id']))
                <strong>Aluno:</strong>
                @php
                    $aluno = \App\Models\Aluno::find($filtros['aluno_id']);
                    echo $aluno ? $aluno->nome : 'Não encontrado';
                @endphp
                <br>
            @endif

            @if(isset($filtros['data_inicio']) && isset($filtros['data_fim']))
                <strong>Período:</strong> {{ \Carbon\Carbon::parse($filtros['data_inicio'])->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($filtros['data_fim'])->format('d/m/Y') }}<br>
            @elseif(isset($filtros['data_inicio']))
                <strong>A partir de:</strong> {{ \Carbon\Carbon::parse($filtros['data_inicio'])->format('d/m/Y') }}<br>
            @elseif(isset($filtros['data_fim']))
                <strong>Até:</strong> {{ \Carbon\Carbon::parse($filtros['data_fim'])->format('d/m/Y') }}<br>
            @endif

            @if(isset($filtros['tipo']))
                <strong>Tipo:</strong>
                @switch($filtros['tipo'])
                    @case('mensalidade') Mensalidade @break
                    @case('matricula') Matrícula @break
                    @case('material') Material @break
                    @default Outro @break
                @endswitch
                <br>
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Aluno</th>
                <th>Matrícula</th>
                <th>Tipo</th>
                <th>Valor</th>
                <th>Recebedor</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pagamentos as $pagamento)
                <tr>
                    <td>{{ $pagamento->data_pagamento->format('d/m/Y') }}</td>
                    <td>{{ $pagamento->aluno->nome }}</td>
                    <td>{{ $pagamento->numeroMatricula }}</td>
                    <td>
                        @switch($pagamento->tipo)
                            @case('mensalidade') Mensalidade @break
                            @case('matricula') Matrícula @break
                            @case('material') Material @break
                            @default Outro @break
                        @endswitch
                    </td>
                    <td>R$ {{ number_format($pagamento->valor, 2, ',', '.') }}</td>
                    <td>{{ $pagamento->recebedor->name }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Nenhum pagamento encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <div><strong>Total de pagamentos:</strong> {{ $pagamentos->count() }}</div>
        <div><strong>Valor total:</strong> R$ {{ number_format($pagamentos->sum('valor'), 2, ',', '.') }}</div>

        @php
            $totalMensalidades = $pagamentos->where('tipo', 'mensalidade')->sum('valor');
            $totalMatriculas = $pagamentos->where('tipo', 'matricula')->sum('valor');
            $totalMaterial = $pagamentos->where('tipo', 'material')->sum('valor');
            $totalOutros = $pagamentos->where('tipo', 'outro')->sum('valor');
        @endphp

        <div><strong>Total de mensalidades:</strong> R$ {{ number_format($totalMensalidades, 2, ',', '.') }}</div>
        <div><strong>Total de matrículas:</strong> R$ {{ number_format($totalMatriculas, 2, ',', '.') }}</div>
        <div><strong>Total de material:</strong> R$ {{ number_format($totalMaterial, 2, ',', '.') }}</div>
        <div><strong>Total de outros:</strong> R$ {{ number_format($totalOutros, 2, ',', '.') }}</div>
    </div>

    <div class="footer">
        <p>Relatório gerado em: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
