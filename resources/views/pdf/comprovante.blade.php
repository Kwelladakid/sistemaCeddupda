<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comprovante de Pagamento</title>
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
        .info-block {
            margin-bottom: 20px;
        }
        .info-row {
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
        }
        .value {
            margin-left: 5px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
        }
        .signature {
            margin-top: 50px;
            text-align: center;
        }
        .signature-line {
            width: 200px;
            border-top: 1px solid #000;
            margin: 10px auto;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">COMPROVANTE DE PAGAMENTO</div>
        <div class="subtitle">{{ config('app.name') }}</div>
    </div>

    <div class="info-block">
        <div class="info-row">
            <span class="label">Nº do Comprovante:</span>
            <span class="value">{{ str_pad($pagamento->id, 6, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="info-row">
            <span class="label">Data:</span>
            <span class="value">{{ $pagamento->data_pagamento->format('d/m/Y') }}</span>
        </div>
    </div>

    <div class="info-block">
        <div class="info-row">
            <span class="label">Aluno:</span>
            <span class="value">{{ $pagamento->aluno->nome }}</span>
        </div>
        <div class="info-row">
            <span class="label">Matrícula:</span>
            <span class="value">{{ $numeroMatricula }}</span>
        </div>
        <div class="info-row">
            <span class="label">Curso:</span>
            <span class="value">{{ $curso }}</span>
        </div>
    </div>

    <div class="info-block">
        <div class="info-row">
            <span class="label">Tipo de Pagamento:</span>
            <span class="value">
                @switch($pagamento->tipo)
                    @case('mensalidade') Mensalidade @break
                    @case('matricula') Matrícula @break
                    @case('material') Material @break
                    @default Outro @break
                @endswitch
            </span>
        </div>
        <div class="info-row">
            <span class="label">Nº da Mensalidade:</span>
            <span class="value">{{ $pagamento->numero_mensalidade }}</span>
        </div>
        <div class="info-row">
            <span class="label">Valor:</span>
            <span class="value">R$ {{ number_format($pagamento->valor, 2, ',', '.') }}</span>
        </div>
        <div class="info-row">
            <span class="label">Forma de Pagamento:</span>
            <span class="value">
                @switch($pagamento->metodo_pagamento)
                    @case('dinheiro') Dinheiro @break
                    @case('cartao_credito') Cartão de Crédito @break
                    @case('cartao_debito') Cartão de Débito @break
                    @case('pix') PIX @break
                    @case('transferencia') Transferência Bancária @break
                    @case('boleto') Boleto Bancário @break
                @endswitch
            </span>
        </div>
    </div>

    @if($pagamento->observacoes)
    <div class="info-block">
        <div class="info-row">
            <span class="label">Observações:</span>
            <span class="value">{{ $pagamento->observacoes }}</span>
        </div>
    </div>
    @endif

    <div class="signature">
        <div class="signature-line"></div>
        <div>{{ $pagamento->recebedor->name }}</div>
        <div>Responsável pelo recebimento</div>
    </div>

    <div class="footer">
        <p>Este documento é um comprovante de pagamento. Guarde-o para futuras consultas.</p>
        <p>Emitido em: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
