<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Certificado de Conclusão</title>
    <style>
        @page {
            size: landscape; /* Orientação paisagem */
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: 'DejaVu Sans', sans-serif;
        }
        .certificate-container {
            position: relative;
            width: 100%;
            height: 100%;
        }
        .background-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        .content {
            position: relative;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        /* Estilo para o texto principal do certificado */
        .main-certificate-text {
            position: absolute;
            top: 45%; /* Ajuste para ficar acima do centro */
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            text-align: center;
            font-size: 18px;
            line-height: 1.6;
            color: #000;
        }

        .main-certificate-text p {
            margin-bottom: 10px;
        }

        .main-certificate-text strong {
            font-weight: bold;
        }

        /* Estilo para o nome do aluno na linha à esquerda */
        .student-signature {
            position: absolute;
            bottom: 15%; /* Ajuste conforme necessário para alinhar com a linha */
            left: 25%; /* Posiciona à esquerda */
            transform: translateX(-50%); /* Centraliza em relação à linha */
            width: 40%; /* Largura do texto */
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            color: #000;
        }

        /* Estilos para informações de rodapé */
        .issue-date {
            position: absolute;
            bottom: 8%; /* Ajuste conforme necessário */
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 14px;
            color: #000;
        }

        .verification-code {
            position: absolute;
            bottom: 4%; /* Ajuste conforme necessário */
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <!-- Imagem de fundo pré-desenhada -->
        <img src="{{ public_path('images/certificado-template.jpg') }}" class="background-image" alt="Template de Certificado">

        <!-- Conteúdo do certificado -->
        <div class="content">
            <!-- Bloco principal de texto do certificado -->
            <div class="main-certificate-text">
                <p>
                    Certificamos que <strong>{{ $participante->nome_participante }}</strong>
                    participou com êxito do minicurso <strong>{{ $minicurso->nome }}</strong>,
                    ministrado pelo professor <strong>{{ $minicurso->professor_responsavel }}</strong>,
                    com carga horária total de <strong>{{ $minicurso->carga_horaria }} horas</strong>.
                </p>

                @if($minicurso->data_inicio && $minicurso->data_fim)
                    <p>
                        O minicurso foi realizado no período de
                        {{ \Carbon\Carbon::parse($minicurso->data_inicio)->format('d/m/Y') }} a
                        {{ \Carbon\Carbon::parse($minicurso->data_fim)->format('d/m/Y') }}.
                    </p>
                @endif
            </div>

            <!-- Nome do aluno na linha à esquerda -->
            <div class="student-signature">
                {{ $participante->nome_participante }}
            </div>

            <!-- Informações de rodapé -->
            <div class="issue-date">
                Emitido em: {{ $data_emissao }}
            </div>

            <div class="verification-code">
                Código de Verificação: {{ $participante->codigo_autenticacao }}
                <br>

            </div>
        </div>
    </div>
</body>
</html>
