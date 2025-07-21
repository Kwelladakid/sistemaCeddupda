{{-- resources/views/minicursos/certificado_completo.blade.php --}}

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificado de Conclusão</title>
    <style>
        /* Estilos globais para o certificado */
        @page {
            size: landscape;
            margin: 0;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
        }
        .page-break {
            page-break-after: always;
        }

        /* Estilos para a primeira página (frente) */
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

        /* Estilos para o verso do certificado */
        .verso-container {
            position: relative;
            width: 100%;
            height: 100%;
            padding: 40px;
            box-sizing: border-box;
            background-color: #f9f9f9;
        }
        .border {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border: 2px solid #0056b3; /* Cor azul similar à frente */
            z-index: -1;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-top: 20px;
            border-bottom: 1px solid #0056b3;
            padding-bottom: 15px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #0056b3;
            margin-bottom: 10px;
        }
        .subtitle {
            font-size: 18px;
            color: #666;
        }
        .verso-content {
            padding: 0 40px;
        }
        .course-title {
            font-size: 20px;
            font-weight: bold;
            color: #0056b3;
            margin-bottom: 20px;
            text-align: center;
        }
        .course-description {
            font-size: 14px;
            line-height: 1.6;
            text-align: justify;
            margin-bottom: 30px;
        }
        .course-topics {
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .course-topics ul {
            padding-left: 20px;
        }
        .course-topics li {
            margin-bottom: 5px;
        }
        .professor-info {
            margin-top: 40px;
            text-align: center;
        }
        .professor-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .professor-title {
            font-size: 14px;
            color: #666;
        }
        .footer {
            position: absolute;
            bottom: 30px;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- PRIMEIRA PÁGINA (FRENTE DO CERTIFICADO) -->
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
                Verifique em: {{ url('certificados/verificar?codigo=' . $participante->codigo_autenticacao) }}
            </div>
        </div>
    </div>

    <!-- QUEBRA DE PÁGINA ENTRE FRENTE E VERSO -->
    <div class="page-break"></div>

    <!-- SEGUNDA PÁGINA (VERSO DO CERTIFICADO) -->
    <div class="verso-container">
        <div class="border"></div>

        <div class="header">
            <div class="title">DETALHES DO CURSO</div>
            <div class="subtitle">Informações Complementares do Certificado</div>
        </div>

        <div class="verso-content">
            <div class="course-title">{{ $minicurso->nome }}</div>

            <div class="course-description">
                @if($minicurso->descricao)
                    {{ $minicurso->descricao }}
                @else
                    <p>Este minicurso tem como objetivo capacitar os participantes com conhecimentos teóricos e práticos essenciais na área abordada. O programa foi desenvolvido para proporcionar uma experiência de aprendizado completa e enriquecedora.</p>
                @endif
            </div>

            <div class="course-topics">
                <h3>Principais tópicos abordados:</h3>
                <ul>
                    @if($minicurso->descricao)
                        @php
                            // Tenta extrair tópicos da descrição
                            $descricao = $minicurso->descricao;
                            $topicos = [];

                            // Procura por listas com marcadores
                            if (preg_match_all('/[•\-\*]\s*([^\n]+)/', $descricao, $matches)) {
                                $topicos = $matches[1];
                            }
                            // Se não encontrar, divide por pontos
                            elseif (count($topicos) == 0) {
                                $sentences = preg_split('/\.(?!\d)/', $descricao);
                                $topicos = array_filter(array_map('trim', $sentences));
                                $topicos = array_slice($topicos, 0, 5);
                            }

                            // Limita a 5 tópicos
                            $topicos = array_slice($topicos, 0, 5);
                        @endphp

                        @foreach($topicos as $topico)
                            <li>{{ $topico }}</li>
                        @endforeach
                    @else
                        <li>Fundamentos teóricos e conceitos básicos</li>
                        <li>Aplicações práticas e estudos de caso</li>
                        <li>Técnicas e metodologias atuais</li>
                        <li>Desenvolvimento de habilidades específicas</li>
                        <li>Avaliação e feedback contínuo</li>
                    @endif
                </ul>
            </div>

            <div class="professor-info">
                <div class="professor-name">{{ $minicurso->professor_responsavel }}</div>
                <div class="professor-title">Professor Responsável</div>
            </div>
        </div>

        <div class="footer">
            <p>CEDDU - Centro de Ensino | Documento complementar ao certificado emitido em {{ $data_emissao }}</p>
        </div>
    </div>
</body>
</html>
