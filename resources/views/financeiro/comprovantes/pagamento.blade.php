<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comprovante de Pagamento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            padding: 0;
            font-size: 24px;
            color: #333;
        }
        .details {
            margin-bottom: 20px;
        }
        .details p {
            margin: 5px 0;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f2f2f2;
        }
        .total {
            text-align: right;
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 50px;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Comprovante de Pagamento</h1>
            <p>Gerado em: {{ date('d/m/Y H:i:s') }}</p>
        </div>

        <div class="details">
            <div class="section-title">Dados do Pagamento</div>
            <p><strong>ID do Pagamento:</strong> {{ $pagamento->id }}</p>
            <p><strong>Data do Pagamento:</strong> {{ date('d/m/Y', strtotime($pagamento->data_pagamento)) }}</p>
            <p><strong>Valor:</strong> R$ {{ number_format($pagamento->valor, 2, ',', '.') }}</p>
            <p><strong>Método de Pagamento:</strong> {{ ucfirst(str_replace('_', ' ', $pagamento->metodo_pagamento)) }}</p>
            <p><strong>Observação:</strong> {{ $pagamento->observacao ?? 'N/A' }}</p>
            <p><strong>Registrado por:</strong> {{ $pagamento->usuario->name ?? 'Sistema' }}</p>
        </div>

        <div class="details">
            <div class="section-title">Dados do Aluno</div>
            <p><strong>Nome:</strong> {{ $aluno->nome }}</p>
            <p><strong>Matrícula:</strong> {{ $aluno->matricula }}</p>
            <p><strong>CPF:</strong> {{ $aluno->cpf }}</p>
            <p><strong>E-mail:</strong> {{ $aluno->email }}</p>
            <p><strong>Telefone:</strong> {{ $aluno->telefone }}</p>
        </div>

        @if($mensalidades->count() > 0)
            <div class="details">
                <div class="section-title">Mensalidades Vinculadas</div>
                <table>
                    <thead>
                        <tr>
                            <th>Mês/Ano</th>
                            <th>Vencimento</th>
                            <th>Valor</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mensalidades as $mensalidade)
                            <tr>
                                <td>{{ date('m/Y', strtotime($mensalidade->data_vencimento)) }}</td>
                                <td>{{ date('d/m/Y', strtotime($mensalidade->data_vencimento)) }}</td>
                                <td>R$ {{ number_format($mensalidade->valor_final, 2, ',', '.') }}</td>
                                <td>{{ ucfirst($mensalidade->status) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="footer">
            <p>Este é um comprovante gerado automaticamente. Não é necessária assinatura.</p>
            <p>{{ config('app.name', 'Seu Sistema') }}</p>
        </div>
    </div>
</body>
</html>
