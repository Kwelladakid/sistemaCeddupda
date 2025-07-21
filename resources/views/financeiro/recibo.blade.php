<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Pagamento #{{ $pagamento->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 14px;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 200px;
            margin-bottom: 10px;
        }
        .titulo {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .subtitulo {
            font-size: 16px;
            margin-bottom: 5px;
        }
        .info-escola {
            font-size: 12px;
            color: #555;
        }
        .recibo-box {
            border: 1px solid #000;
            padding: 15px;
            margin-bottom: 30px;
        }
        .recibo-numero {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
            background-color: #f5f5f5;
            padding: 5px;
        }
        .recibo-valor {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            background-color: #f5f5f5;
            border: 1px dashed #999;
        }
        .info-item {
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
        .assinatura {
            margin-top: 50px;
            text-align: center;
        }
        .linha-assinatura {
            width: 70%;
            margin: 0 auto;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        .footer {
            margin-top: 40px;
            font-size: 12px;
            text-align: center;
            color: #555;
        }
        .mensalidades {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }
        .mensalidades th, .mensalidades td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .mensalidades th {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="titulo">ESCOLA TÉCNICA</div>
        <div class="subtitulo">RECIBO DE PAGAMENTO</div>
        <div class="info-escola">
            Rua Exemplo, 123 - Bairro - Cidade/UF - CEP: 00000-000<br>
            CNPJ: 00.000.000/0001-00 - Telefone: (00) 0000-0000
        </div>
    </div>

    <div class="recibo-box">
        <div class="recibo-numero">
            RECIBO Nº {{ $pagamento->id }}
        </div>

        <div class="info-item">
            <span class="info-label">Nome do Aluno:</span> {{ $pagamento->aluno->nome }}
        </div>
        <div class="info-item">
            <span class="info-label">Matrícula:</span> {{ $numeroMatricula }}
        </div>
        <div class="info-item">
            <span class="info-label">Data do Pagamento:</span> {{ $pagamento->data_pagamento->format('d/m/Y') }}
        </div>
        <div class="info-item">
            <span class="info-label">Forma de Pagamento:</span> {{ $pagamento->getMetodoPagamentoFormatado() }}
        </div>

        <div class="recibo-valor">
            Valor Total: R$ {{ number_format($pagamento->valor, 2, ',', '.') }}
        </div>

        @if($pagamento->mensalidades->count() > 0)
            <div class="info-item">
                <strong>Referente a:</strong>
            </div>
            <table class="mensalidades">
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th>Vencimento</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pagamento->mensalidades as $mensalidade)
                        <tr>
                            <td>Mensalidade {{ $mensalidade->getMesReferencia() }}</td>
                            <td>{{ $mensalidade->data_vencimento->format('d/m/Y') }}</td>
                            <td>R$ {{ number_format($mensalidade->valor_final, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="info-item">
                <strong>Referente a:</strong> Pagamento avulso
            </div>
        @endif

        @if($pagamento->observacao)
            <div class="info-item" style="margin-top: 15px;">
                <strong>Observação:</strong> {{ $pagamento->observacao }}
            </div>
        @endif

        <div class="assinatura">
            <div class="linha-assinatura">
                {{ $pagamento->usuario->name ?? 'Responsável pelo recebimento' }}
            </div>
        </div>
    </div>

    <div class="footer">
        Este recibo não tem valor fiscal. Documento emitido em {{ date('d/m/Y H:i:s') }}.
    </div>
</body>
</html>
