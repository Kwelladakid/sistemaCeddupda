<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Verso do Certificado</title>
    <style>
        @page {
            size: landscape; /* Orientação paisagem */
            margin: 0; /* Remove margens padrão da página */
        }
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%; /* Garante que o body ocupe 100% da altura da página */
            font-family: 'DejaVu Sans', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            box-sizing: border-box; /* Inclui padding e border na largura/altura total do elemento */
            overflow: hidden; /* Previne barras de rolagem e potencial segunda página */
        }
        .certificate-wrapper { /* Novo wrapper principal para todo o conteúdo */
            width: calc(100% - 40px); /* Largura total menos 20px de margem em cada lado */
            height: calc(100% - 40px); /* Altura total menos 20px de margem em cima e embaixo */
            margin: 20px; /* Margem externa de 20px que cria o espaço em volta da borda */
            border: 2px solid #0056b3; /* Borda aplicada diretamente a este wrapper */
            box-sizing: border-box;
            display: flex; /* Usa flexbox para organizar o conteúdo verticalmente */
            flex-direction: column;
            justify-content: space-between; /* Distribui o espaço entre o cabeçalho, conteúdo e rodapé */
            padding: 20px; /* Preenchimento interno para o conteúdo dentro da borda */
        }
        .header {
            text-align: center;
            padding-bottom: 15px;
            border-bottom: 1px solid #0056b3;
            margin-bottom: 20px; /* Ajustado para melhor espaçamento */
        }
        .title {
            font-size: 70px; /* Aumentado de 24px */
            font-weight: bold;
            color: #0056b3;
            margin-bottom: 10px;
        }
        .subtitle {
            font-size: 42px; /* Aumentado de 18px */
            color: #666;
        }
        .content-area { /* Área flexível para o conteúdo principal */
            flex-grow: 1; /* Permite que esta área cresça e ocupe o espaço disponível */
            padding: 0 20px; /* Ajuste de preenchimento lateral */
            display: flex;
            flex-direction: column;
            justify-content: flex-start; /* Alinha o conteúdo ao topo da área */
            overflow: hidden; /* Garante que se o texto for *muito* longo, ele não empurre para a próxima página */
        }
        .course-title {
            font-size: 48px; /* Aumentado de 20px */
            font-weight: bold;
            color: #0056b3;
            margin-bottom: 20px;
            text-align: center;
        }
        .course-description {
            font-size: 38px; /* Aumentado de 14px */
            line-height: 1.6;
            text-align: justify;
            margin-bottom: 20px; /* Ajustado de 30px */
        }
        .course-topics {
            font-size: 38px; /* Aumentado de 14px */
            line-height: 1.6;
            margin-bottom: 20px; /* Ajustado de 30px */
        }
        .course-topics h3 {
            font-size: 40px; /* Título dos tópicos ligeiramente maior que os itens */
            margin-bottom: 10px;
        }
        .course-topics ul {
            padding-left: 25px; /* Mais espaço para os marcadores de lista */
            list-style-type: disc; /* Garante marcadores de disco */
        }
        .course-topics li {
            margin-bottom: 8px; /* Mais espaço entre os itens da lista */
        }
        .professor-info {
            margin-top: auto; /* Empurra esta seção para o final da área de conteúdo */
            text-align: center;
            padding-top: 20px; /* Adiciona espaço acima das informações do professor */
        }
        .professor-name {
            font-size: 40px; /* Aumentado de 18px */
            font-weight: bold;
            margin-bottom: 5px;
        }
        .professor-title {
            font-size: 36px; /* Aumentado de 14px */
            color: #666;
        }
        .footer {
            text-align: center;
            font-size: 34px; /* Aumentado de 12px */
            color: #666;
            padding-top: 15px;
            border-top: 1px solid #eee; /* Linha sutil separando o rodapé */
            margin-top: 15px; /* Espaço entre o conteúdo e o rodapé */
        }
    </style>
</head>
<body>
    <div class="certificate-wrapper">
        <div class="header">
            <div class="title">DETALHES DO CURSO</div>
            <div class="subtitle">Informações Complementares do Certificado</div>
        </div>

        <div class="content-area">
            <div class="course-title">{{ $minicurso->nome }}</div>

            <div class="course-description">
                @if($minicurso->descricao)
                    {{ $minicurso->descricao }}
                @else
                    <p>Este minicurso tem como objetivo capacitar os participantes com conhecimentos teóricos e práticos essenciais na área abordada. O programa foi desenvolvido para proporcionar uma experiência de aprendizado completa e enriquecedora, abrangendo as últimas tendências e metodologias do setor.</p>
                @endif
            </div>

            <div class="course-topics">
                <h3>Principais tópicos abordados:</h3>
                <ul>
                    @if($minicurso->descricao)
                        @php
                            $descricao = $minicurso->descricao;
                            $topicos = [];

                            // Tenta extrair tópicos formatados com marcadores (-, *, •)
                            if (preg_match_all('/(?:^|\n)\s*[\-\*\u2022]\s*(.*?)(?=\n|$)/u', $descricao, $matches)) {
                                $topicos = array_map('trim', $matches[1]);
                            }

                            // Se não encontrar marcadores, tenta extrair por sentenças (dividindo por . ? ! ;)
                            if (empty($topicos)) {
                                $sentences = preg_split('/(?<=[.?!;])\s+/', $descricao, -1, PREG_SPLIT_NO_EMPTY);
                                $topicos = array_map('trim', $sentences);
                                // Filtra sentenças muito curtas que podem não ser tópicos
                                $topicos = array_filter($topicos, function($t) { return strlen($t) > 15; });
                            }

                            // Filtra strings vazias e limita o número de tópicos para não transbordar
                            $topicos = array_slice(array_filter($topicos), 0, 7); // Limita a 7 tópicos
                        @endphp

                        @foreach($topicos as $topico)
                            <li>{{ $topico }}</li>
                        @endforeach

                        @if(empty($topicos))
                            <li>Não foi possível extrair tópicos específicos da descrição fornecida.</li>
                            <li>Considere formatar a descrição do minicurso com marcadores de lista para uma melhor apresentação dos tópicos.</li>
                        @endif
                    @else
                        <li>Fundamentos teóricos e conceitos básicos</li>
                        <li>Aplicações práticas e estudos de caso</li>
                        <li>Técnicas e metodologias atuais</li>
                        <li>Desenvolvimento de habilidades específicas</li>
                        <li>Análise de tendências de mercado</li>
                        <li>Discussão e resolução de problemas reais</li>
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
