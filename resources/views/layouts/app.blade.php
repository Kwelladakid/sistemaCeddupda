<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sistema Escolar') }}</title>

        {{-- Font Awesome para ícones (local) --}}
        {{-- Certifique-se de que as pastas 'css' e 'webfonts' do Font Awesome estão em 'public/' --}}
        <link rel="stylesheet" href="{{ asset('css/all.min.css') }}">

        {{-- NOVO: Bootstrap 5 CSS (via CDN) --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

        <!-- CSS BÁSICO EMBUTIDO (SEM DEPENDÊNCIAS EXTERNAS) -->
        <style>
            /* Reset básico para garantir box-sizing consistente */
            *, *::before, *::after {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
                background-color: #f4f7f6; /* Fundo cinza claro */
                color: #333;
                line-height: 1.6;
                overflow-x: hidden; /* Adicionado para evitar rolagem horizontal indesejada com o menu lateral */
            }
            /* AJUSTADO: container-fluid para funcionar com Bootstrap */
            .container-fluid {
                width: 100%;
                /* max-width: 1200px; REMOVIDO: Deixa o Bootstrap controlar a largura máxima se usar .container */
                margin: 0 auto;
                padding: 20px; /* Padding padrão */
            }
            /* AJUSTADO: main-content para funcionar com Bootstrap */
            .main-content {
                background-color: #ffffff;
                padding: 30px; /* Padding padrão */
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                min-height: 400px; /* Altura mínima para o conteúdo */
            }

            /* ========================================================= */
            /* NOVOS ESTILOS PARA O HEADER (CABEÇALHO) */
            /* ========================================================= */
            .header {
                background-color: #2c3e50; /* Azul escuro */
                color: #ffffff;
                padding: 20px; /* Padding geral para as bordas */
                min-height: 150px; /* Altura mínima do cabeçalho */
                box-shadow: 0 2px 5px rgba(0,0,0,0.2);

                display: flex; /* Ativa o Flexbox */
                flex-direction: column; /* Organiza os itens em coluna (logo em cima, texto embaixo) */
                justify-content: flex-end; /* Alinha os itens ao final do contêiner (parte inferior) */
                align-items: center; /* Centraliza os itens horizontalmente */
                gap: 10px; /* Espaço entre o logo e o título */
                position: relative; /* Adicionado para posicionar o botão hambúrguer */
            }
            .header-logo { /* Nova classe para o logotipo */
                max-height: 80px; /* Altura máxima do logotipo */
                width: auto; /* Mantém a proporção */
            }
            .header h1 {
                margin: 0; /* Remove margem padrão do h1 para controle total pelo flexbox */
                font-size: 28px;
            }
            /* ========================================================= */
            /* FIM DOS NOVOS ESTILOS PARA O HEADER */
            /* ========================================================= */

            .navbar {
                background-color: #34495e; /* Azul um pouco mais claro */
                padding: 10px 20px;
                margin-bottom: 20px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            }
            .navbar ul {
                list-style: none;
                padding: 0;
                margin: 0;
                display: flex;
                justify-content: center;
                flex-wrap: wrap; /* Permite que os itens quebrem linha em telas menores */
            }
            .navbar ul li {
                margin: 5px 15px;
            }
            .navbar ul li a {
                text-decoration: none;
                color: #ecf0f1; /* Cinza claro */
                font-weight: bold;
                padding: 8px 12px;
                border-radius: 4px;
                transition: background-color 0.3s ease;
            }
            .navbar ul li a:hover {
                background-color: #2980b9; /* Azul mais vibrante no hover */
            }

            /* ========================================================= */
            /* NOVOS ESTILOS PARA O MENU MÓVEL (HAMBÚRGUER) */
            /* ========================================================= */
            .hamburger-menu-toggle {
                display: none; /* Escondido por padrão em telas grandes */
                position: absolute;
                top: 20px; /* Ajuste conforme a altura do seu header */
                right: 20px;
                background: none;
                border: none;
                color: #ffffff;
                font-size: 2em;
                cursor: pointer;
                z-index: 1001; /* Acima de outros elementos */
            }

            .mobile-nav {
                display: none; /* Escondido por padrão em telas grandes */
                position: fixed;
                top: 0;
                right: 0;
                width: 250px; /* Largura do menu lateral */
                height: 100%;
                background-color: #34495e; /* Cor do menu lateral */
                box-shadow: -2px 0 5px rgba(0,0,0,0.5);
                transform: translateX(100%); /* Esconde o menu para a direita */
                transition: transform 0.3s ease-in-out;
                z-index: 1000;
                padding-top: 80px; /* Espaço para o topo */
            }

            .mobile-nav ul {
                list-style: none;
                padding: 0;
                margin: 0;
            }

            .mobile-nav ul li a {
                display: block;
                padding: 15px 20px;
                color: #ecf0f1;
                text-decoration: none;
                border-bottom: 1px solid rgba(255,255,255,0.1);
                transition: background-color 0.3s ease;
            }

            .mobile-nav ul li a:hover {
                background-color: #2980b9;
            }

            .mobile-nav .close-btn {
                position: absolute;
                top: 20px;
                right: 20px;
                background: none;
                border: none;
                color: #ffffff;
                font-size: 2em;
                cursor: pointer;
            }

            /* Estado quando o menu móvel está aberto */
            body.mobile-menu-open .mobile-nav {
                transform: translateX(0); /* Mostra o menu */
            }

            /* Overlay para quando o menu móvel está aberto */
            body.mobile-menu-open::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0,0,0,0.5); /* Escurece o fundo */
                z-index: 999;
                cursor: pointer;
            }
            /* ========================================================= */
            /* FIM DOS NOVOS ESTILOS PARA O MENU MÓVEL */
            /* ========================================================= */

            .footer {
                text-align: center;
                padding: 20px;
                margin-top: 30px;
                color: #7f8c8d; /* Cinza médio */
                font-size: 14px;
            }
            /* Estilos para botões básicos (TAMANHO DIMINUÍDO) */
            /* AJUSTADO: Removido padding/font-size para deixar o Bootstrap controlar */
            .btn {
                /* display: inline-block; REMOVIDO: Bootstrap já faz isso */
                /* padding: 8px 16px; REMOVIDO: Bootstrap já faz isso */
                /* font-size: 14px; REMOVIDO: Bootstrap já faz isso */
                font-weight: bold;
                text-align: center;
                text-decoration: none;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s ease, color 0.3s ease;
            }
            /* AJUSTADO: Cores de botões para serem mais consistentes com Bootstrap */
            .btn-primary {
                background-color: #007bff; /* Azul Bootstrap */
                color: #ffffff;
                border: 1px solid #007bff;
            }
            .btn-primary:hover {
                background-color: #0069d9;
                border-color: #0062cc;
            }
            .btn-success {
                background-color: #28a745; /* Verde Bootstrap */
                color: #ffffff;
                border: 1px solid #28a745;
            }
            .btn-success:hover {
                background-color: #218838;
                border-color: #1e7e34;
            }
            .btn-warning {
                background-color: #ffc107; /* Laranja Bootstrap */
                color: #212529; /* Texto escuro para contraste */
                border: 1px solid #ffc107;
            }
            .btn-warning:hover {
                background-color: #e0a800;
                border-color: #d39e00;
            }
            .btn-danger {
                background-color: #dc3545; /* Vermelho Bootstrap */
                color: #ffffff;
                border: 1px solid #dc3545;
            }
            .btn-danger:hover {
                background-color: #c82333;
                border-color: #bd2130;
            }
            .btn-info {
                background-color: #17a2b8; /* Turquesa Bootstrap */
                color: #ffffff;
                border: 1px solid #17a2b8;
            }
            .btn-info:hover {
                background-color: #138496;
                border-color: #117a8b;
            }
            .btn-secondary {
                background-color: #6c757d; /* Cinza Bootstrap */
                color: #ffffff;
                border: 1px solid #6c757d;
            }
            .btn-secondary:hover {
                background-color: #5a6268;
                border-color: #545b62;
            }
            /* Estilos para tabelas */
            /* AJUSTADO: Removido estilos que Bootstrap já fornece */
            .table {
                /* width: 100%; REMOVIDO */
                /* border-collapse: collapse; REMOVIDO */
                margin-bottom: 20px;
            }
            .table th, .table td {
                /* border: 1px solid #ddd; REMOVIDO */
                padding: 8px;
                text-align: left;
            }
            .table th {
                background-color: #ecf0f1;
                font-weight: bold;
            }
            .table tr:nth-child(even) {
                background-color: #f9f9f9;
            }
            .table tr:hover {
                background-color: #f1f1f1;
            }
            /* NOVO: Estilo para thead.table-dark */
            .table thead.table-dark th {
                background-color: #343a40; /* Cor de fundo escura */
                color: #ffffff; /* Cor do texto branca */
                border-color: #454d55; /* Borda um pouco mais clara que o fundo */
            }
            /* NOVO: Estilo para table-striped (se quiser um control mais explícito) */
            .table.table-striped tbody tr:nth-of-type(odd) {
                background-color: #f8f9fa; /* Cor para linhas ímpares */
            }
            .table.table-striped tbody tr:nth-of-type(even) {
                background-color: #ffffff; /* Cor para linhas pares */
            }
            /* NOVO: Estilo para table-bordered */
            .table.table-bordered th,
            .table.table-bordered td {
                border: 1px solid #dee2e6;
            }
            .table.table-bordered thead th {
                border-bottom-width: 2px;
            }


            /* Estilos para formulários */
            /* AJUSTADO: Removido estilos que Bootstrap já fornece */
            .form-group {
                margin-bottom: 15px;
            }
            .form-group label {
                display: block;
                margin-bottom: 5px;
                font-weight: bold;
            }
            .form-control {
                /* width: 100%; REMOVIDO */
                /* padding: 10px; REMOVIDO */
                /* border: 1px solid #ccc; REMOVIDO */
                /* border-radius: 4px; REMOVIDO */
                /* box-sizing: border-box; REMOVIDO */
                /* font-size: 16px; REMOVIDO */
            }
            .form-control:focus {
                border-color: #3498db;
                outline: none;
                box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
            }
            textarea.form-control {
                resize: vertical;
                min-height: 80px;
            }

            /* NOVO: Estilos para validação de formulário */
            /* AJUSTADO: Ícone de erro do Bootstrap já lida com isso */
            .form-control.is-invalid {
                /* border-color: #dc3545; REMOVIDO */
                /* padding-right: calc(1.5em + 0.75rem); REMOVIDO */
                /* background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e"); REMOVIDO */
                /* background-repeat: no-repeat; REMOVIDO */
                /* background-position: right calc(0.375em + 0.1875rem) center; REMOVIDO */
                /* background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem); REMOVIDO */
            }
            .invalid-feedback {
                display: block; /* Garante que a mensagem de erro seja visível */
                width: 100%;
                margin-top: 0.25rem;
                font-size: 0.875em;
                color: #dc3545; /* Cor do texto de erro */
            }


            /* Estilos para mensagens de sessão */
            /* AJUSTADO: Removido estilos que Bootstrap já fornece */
            .alert {
                /* padding: 15px; REMOVIDO */
                /* margin-bottom: 20px; REMOVIDO */
                /* border: 1px solid transparent; REMOVIDO */
                /* border-radius: 4px; REMOVIDO */
            }
            .alert-success {
                /* color: #3c763d; REMOVIDO */
                /* background-color: #dff0d8; REMOVIDO */
                /* border-color: #d6e9c6; REMOVIDO */
            }
            .alert-danger {
                /* color: #a94442; REMOVIDO */
                /* background-color: #f2dede; REMOVIDO */
                /* border-color: #ebccd1; REMOVIDO */
            }
            .alert-warning {
                /* color: #8a6d3b; REMOVIDO */
                /* background-color: #fcf8e3; REMOVIDO */
                /* border-color: #faebcc; REMOVIDO */
            }
            .alert-info {
                /* color: #31708f; REMOVIDO */
                /* background-color: #d9edf7; REMOVIDO */
                /* border-color: #bce8f1; REMOVIDO */
            }

            /* Adicionado para remover sublinhado padrão de todos os links */
            a {
                text-decoration: none;
            }

            /* ========================================================= */
            /* AJUSTES GERAIS PARA ÍCONES DO FONT AWESOME */
            /* ========================================================= */
            /* Garante que os ícones sejam renderizados de forma suave */
            .fa, .fas, .far, .fal, .fad, .fab {
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
                text-rendering: optimizeLegibility;
            }

            /* Alinhamento vertical padrão para ícones dentro de botões e links */
            .btn i,
            .action-icon-btn i, /* Se esta classe for definida em outras views */
            .form-btn-icon i,  /* Se esta classe for definida em outras views */
            .navbar ul li a i,
            button i,
            a i {
                vertical-align: middle; /* Centraliza o ícone verticalmente com o texto */
                line-height: 1; /* Ajuda no alinhamento em algumas fontes */
            }

            /* Espaçamento padrão para ícones que aparecem antes do texto */
            .btn i:not(:last-child),
            .action-icon-btn i:not(:last-child),
            .form-btn-icon i:not(:last-child),
            .navbar ul li a i:not(:last-child),
            button i:not(:last-child),
            a i:not(:last-child) {
                margin-right: 0.5em; /* Adiciona um pequeno espaço à direita do ícone */
            }
            /* ========================================================= */
            /* FIM DOS AJUSTES GERAIS PARA ÍCONES */
            /* ========================================================= */

            /* ========================================================= */
            /* ESTILOS ESPECÍFICOS DO DASHBOARD PRINCIPAL (MOVIDOS PARA CÁ) */
            /* ========================================================= */
            .quick-actions-grid {
                display: flex;
                flex-wrap: wrap;
                gap: 20px; /* Espaçamento entre os cartões */
                justify-content: center;
                padding: 20px 0;
            }

            .icon-card-link {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                width: 130px;
                height: 130px;
                background-color: #ffffff;
                border: 1px solid #e0e0e0;
                border-radius: 12px;
                text-decoration: none;
                color: #555;
                box-shadow: 0 4px 10px rgba(0,0,0,0.08);
                transition: all 0.3s ease;
                text-align: center;
                font-size: 14px;
                font-weight: 500;
                cursor: pointer; /* Indica que o elemento é clicável */
            }

            .icon-card-link:hover {
                background-color: #f8f8f8;
                transform: translateY(-5px);
                box-shadow: 0 8px 20px rgba(0,0,0,0.15);
                color: #333;
            }

            .icon-card-link i {
                font-size: 3.8em; /* Aumentado de 3.2em para 3.8em */
                margin-bottom: 8px; /* Ajustado de 10px para 8px para compensar o tamanho */
                color: #34495e; /* Cor padrão para os ícones (azul escuro do header) */
                transition: color 0.3s ease;
            }

            /* Cores específicas para os ícones no hover */
            .icon-card-link.professores:hover i { color: #3498db; }
            .icon-card-link.alunos:hover i { color: #2ecc71; }
            .icon-card-link.cursos:hover i { color: #1abc9c; }
            .icon-card-link.disciplinas:hover i { color: #f39c12; }
            .icon-card-link.turmas:hover i { color: #95a5a6; }
            /* ========================================================= */
            /* FIM DOS ESTILOS ESPECÍFICOS DO DASHBOARD PRINCIPAL */
            /* ========================================================= */

            /* ========================================================= */
            /* ESTILOS ESPECÍFICOS PARA CURSOS/INDEX (MOVIDOS PARA CÁ) */
            /* ========================================================= */
            /* Estilo base para todos os botões de ação com ícones */
            .action-icon-btn {
                display: inline-flex; /* Permite que o ícone e o texto fiquem na mesma linha e centralizados */
                align-items: center;
                justify-content: center;
                padding: 6px 10px; /* Padding padrão para botões de tabela */
                font-size: 13px; /* Tamanho da fonte para o texto do botão */
                font-weight: bold;
                border-radius: 5px;
                text-decoration: none; /* Garante que não haja sublinhado */
                transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
                margin-right: 5px; /* Espaçamento entre os botões na mesma linha */
                border: 1px solid transparent; /* Borda padrão transparente */
                color: #ffffff; /* Cor do texto padrão para contraste */
                white-space: nowrap; /* Impede que o texto quebre linha */
            }

            .action-icon-btn:hover {
                box-shadow: 0 2px 5px rgba(0,0,0,0.2);
                transform: translateY(-1px);
            }

            .action-icon-btn i {
                margin-right: 6px; /* Espaço entre o ícone e o texto */
                font-size: 1.1em; /* Tamanho do ícone */
            }

            /* NOVO: Estilos específicos para botões de ação DENTRO DE TABELAS em telas grandes */
            .table .action-icon-btn {
                padding: 4px 8px; /* Padding reduzido */
                font-size: 12px; /* Fonte ligeiramente menor */
                margin-right: 3px; /* Margem reduzida entre botões */
            }
            .table .action-icon-btn i {
                margin-right: 4px; /* Margem do ícone reduzida */
                font-size: 1em; /* Ajusta o tamanho do ícone relativo à nova fonte */
            }


            /* Cores e estilos específicos para cada tipo de botão */
            .action-icon-btn.create {
                background-color: #28a745; /* Verde para "Cadastrar" */
                border-color: #28a745;
            }
            .action-icon-btn.create:hover {
                background-color: #218838;
                border-color: #1e7e34;
            }

            .action-icon-btn.details {
                background-color: #17a2b8; /* Azul claro para "Detalhes" */
                border-color: #17a2b8;
            }
            .action-icon-btn.details:hover {
                background-color: #138496;
                border-color: #117a8b;
            }

            .action-icon-btn.edit {
                background-color: #007bff; /* Azul para "Editar" */
                border-color: #007bff;
            }
            .action-icon-btn.edit:hover {
                background-color: #0069d9;
                border-color: #0062cc;
            }

            .action-icon-btn.view-turmas {
                background-color: #6c757d; /* Cinza para "Ver Turmas" */
                border-color: #6c757d;
            }
            .action-icon-btn.view-turmas:hover {
                background-color: #5a6268;
                border-color: #545b62;
            }

            .action-icon-btn.delete {
                background-color: #dc3545; /* Vermelho para "Excluir" */
                border-color: #dc3545;
            }
            .action-icon-btn.delete:hover {
                background-color: #c82333;
                border-color: #bd2130;
            }

            /* NOVOS ESTILOS PARA ALUNOS/INDEX */
            .action-icon-btn.view-boletim {
                background-color: #2ecc71; /* Verde para "Ver Boletim" */
                border-color: #2ecc71;
            }
            .action-icon-btn.view-boletim:hover {
                background-color: #27ae60;
                border-color: #27ae60;
            }

            .action-icon-btn.confirm-matricula {
                background-color: #f39c12; /* Laranja para "Confirmar Matrícula" */
                border-color: #f39c12;
            }
            .action-icon-btn.confirm-matricula:hover {
                background-color: #e67e22;
                border-color: #e67e22;
            }
            /* FIM DOS NOVOS ESTILOS PARA ALUNOS/INDEX */

            /* Ajustes para o formulário de exclusão dentro da tabela */
            .table form {
                display: inline-block; /* Garante que o botão de exclusão fique alinhado */
                margin-right: 0; /* Remove margem extra se houver */
            }
            /* ========================================================= */
            /* FIM DOS ESTILOS ESPECÍFICOS PARA CURSOS/INDEX */
            /* ========================================================= */

            /* NOVO: Estilos para botões de formulário com ícones */
            .form-btn-icon {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 8px 15px; /* Ajustado para um tamanho bom */
                font-size: 14px;
                font-weight: bold;
                text-align: center;
                text-decoration: none;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
                border: 1px solid transparent;
                color: #ffffff; /* Cor do texto padrão para contraste */
                white-space: nowrap; /* Impede que o texto quebre linha */
                margin-right: 10px; /* Espaçamento entre os botões */
            }

            .form-btn-icon:hover {
                box-shadow: 0 2px 5px rgba(0,0,0,0.2);
                transform: translateY(-1px);
            }

            /* Cores específicas para os botões de formulário */
            .form-btn-icon.save-course {
                background-color: #28a745; /* Verde para salvar */
                border-color: #28a745;
            }
            .form-btn-icon.save-course:hover {
                background-color: #218838;
                border-color: #1e7e34;
            }

            .form-btn-icon.back-button {
                background-color: #6c757d; /* Cinza para voltar */
                border-color: #6c757d;
            }
            .form-btn-icon.back-button:hover {
                background-color: #5a6268;
                border-color: #545b62;
            }
            /* FIM DOS NOVOS ESTILOS PARA BOTÕES DE FORMULÁRIO */

            /* NOVO: Estilos para btn-group */
            /* AJUSTADO: Removido estilos que Bootstrap já fornece */
            .btn-group {
                /* display: inline-flex; REMOVIDO */
                vertical-align: middle; /* Alinha verticalmente com outros elementos inline */
            }
            .btn-group .btn {
                border-radius: 0; /* Remove o border-radius para que se unam */
                margin-right: -1px; /* Remove o espaçamento entre eles */
            }
            .btn-group .btn:first-child {
                border-top-left-radius: 5px;
                border-bottom-left-radius: 5px;
            }
            .btn-group .btn:last-child {
                border-top-right-radius: 5px;
                border-bottom-right-radius: 5px;
                margin-right: 0; /* Último botão não precisa de margem à direita */
            }
            .btn-group .btn:hover {
                z-index: 1; /* Garante que a borda do botão hover não seja cortada */
            }

            /* NOVO: Estilos para Card */
            /* AJUSTADO: Removido estilos que Bootstrap já fornece */
            .card {
                /* background-color: #ffffff; REMOVIDO */
                /* border: 1px solid #e0e0e0; REMOVIDO */
                /* border-radius: 8px; REMOVIDO */
                box-shadow: 0 2px 10px rgba(0,0,0,0.05); /* Mantido para sombra customizada */
                margin-bottom: 20px; /* Equivalente a mb-4 */
            }
            .card-header {
                padding: 15px 20px;
                background-color: #f8f9fa;
                border-bottom: 1px solid #e0e0e0;
                border-top-left-radius: 8px;
                border-top-right-radius: 8px;
                display: flex; /* Para alinhar título e botão */
                justify-content: space-between; /* Para alinhar título e botão */
                align-items: center;
                font-weight: bold;
                color: #343a40;
            }
            .card-body {
                padding: 20px;
            }

            /* NOVO: Estilos para Breadcrumb */
            /* AJUSTADO: Removido estilos que Bootstrap já fornece */
            .breadcrumb {
                /* display: flex; REMOVIDO */
                /* flex-wrap: wrap; REMOVIDO */
                padding: 0.75rem 1rem;
                margin-bottom: 1rem;
                /* list-style: none; REMOVIDO */
                background-color: #e9ecef;
                /* border-radius: 0.25rem; REMOVIDO */
            }
            .breadcrumb-item + .breadcrumb-item::before {
                display: inline-block;
                padding-right: 0.5rem;
                color: #6c757d;
                content: "/";
            }
            .breadcrumb-item.active {
                color: #6c757d;
            }
            .breadcrumb-item a {
                color: #007bff;
                text-decoration: none;
            }
            .breadcrumb-item a:hover {
                text-decoration: underline;
            }

            /* NOVO: Estilos para Alerts (simplificados) */
            /* AJUSTADO: Removido estilos que Bootstrap já fornece */
            .alert {
                /* padding: 1rem 1rem; REMOVIDO */
                margin-bottom: 1rem;
                /* border: 1px solid transparent; REMOVIDO */
                /* border-radius: 0.25rem; REMOVIDO */
            }
            .alert-success {
                /* color: #155724; REMOVIDO */
                /* background-color: #d4edda; REMOVIDO */
                /* border-color: #c3e6cb; REMOVIDO */
            }
            /* Removido .alert-dismissible, .fade, .show, .btn-close para simplificar */

            /* NOVO: Classes de espaçamento utilitárias (simplificadas) */
            /* AJUSTADO: Removido, Bootstrap já fornece */
            .mt-4 { margin-top: 1.5rem !important; }
            .mb-3 { margin-bottom: 1rem !important; } /* Adicionado para consistência com o minicurso/create */
            .mb-4 { margin-bottom: 1.5rem !important; }
            .me-1 { margin-right: 0.25rem !important; } /* Para ícones */
            .px-4 { padding-left: 1.5rem !important; padding-right: 1.5rem !important; }
            /* NOVO: Adicionado do financeiro/index.blade.php */
            .me-2 { margin-right: 0.5rem !important; }
            .w-100 { width: 100% !important; }


            /* ========================================================= */
            /* NOVO: Estilos para Badges */
            /* ========================================================= */
            /* AJUSTADO: Removido estilos que Bootstrap já fornece */
            .badge {
                /* display: inline-block; REMOVIDO */
                /* padding: 0.35em 0.65em; REMOVIDO */
                /* font-size: 0.75em; REMOVIDO */
                /* font-weight: 700; REMOVIDO */
                /* line-height: 1; REMOVIDO */
                /* color: #fff; REMOVIDO */
                /* text-align: center; REMOVIDO */
                /* white-space: nowrap; REMOVIDO */
                /* vertical-align: baseline; REMOVIDO */
                /* border-radius: 0.25rem; REMOVIDO */
            }
            .badge.bg-success {
                background-color: #28a745 !important;
            }
            .badge.bg-secondary {
                background-color: #6c757d !important;
            }
            .badge.bg-primary {
                background-color: #007bff !important;
            }
            .badge.bg-danger {
                background-color: #dc3545 !important;
            }
            .badge.bg-warning {
                background-color: #ffc107 !important;
                color: #212529; /* Texto escuro para badge amarelo */
            }
            .badge.bg-info { /* Adicionado para status como 'Concluído' ou 'Matriculado' */
                background-color: #17a2b8 !important;
            }

            /* ========================================================= */
            /* NOVOS ESTILOS ESPECÍFICOS DO DASHBOARD FINANCEIRO */
            /* ========================================================= */
            .card {
                border: none; /* Remove borda padrão do Bootstrap */
                border-radius: 0.5rem;
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15); /* Sombra mais pronunciada */
                margin-bottom: 1.5rem;
            }
            .card-body {
                padding: 1.5rem;
            }
            .card-title {
                font-size: 1.1rem;
                margin-bottom: 0.5rem;
            }
            .card-text {
                font-size: 2rem;
                font-weight: bold;
            }
            /* Cores de fundo e texto (algumas já existem, mas garantimos com !important) */
            .bg-primary { background-color: #007bff !important; }
            .bg-danger { background-color: #dc3545 !important; }
            .bg-warning { background-color: #ffc107 !important; }
            .bg-success { background-color: #28a745 !important; }
            .text-white { color: #fff !important; }
            .text-dark { color: #343a40 !important; }
            .h-100 { height: 100% !important; }
            .d-grid { display: grid !important; }
            .gap-2 { gap: 0.5rem !important; }
            .btn-light {
                background-color: #f8f9fa;
                border-color: #f8f9fa;
                color: #212529;
            }
            .btn-light:hover {
                background-color: #e2e6ea;
                border-color: #dae0e5;
            }
            .list-group-item-action:hover {
                background-color: #f8f9fa;
            }
            .btn-group-sm > .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.875rem;
                border-radius: 0.2rem;
            }
            /* AJUSTADO: Estilos para o container principal do financeiro, se necessário */
            /* .container {
                max-width: 960px;
                margin: 0 auto;
                padding: 0 15px;
            } */
            /* REMOVIDO: Regras de grid (.row, .col-md-*, .col-12) - Bootstrap já as fornece */
            /* REMOVIDO: Regras de input-group, form-control, list-group, list-group-item, table-responsive - Bootstrap já as fornece */
            /* ========================================================= */
            /* FIM DOS NOVOS ESTILOS ESPECÍFICOS DO DASHBOARD FINANCEIRO */
            /* ========================================================= */


            /* ========================================================= */
            /* MEDIA QUERIES PARA RESPONSIVIDADE */
            /* ========================================================= */

            /* Para telas menores que 768px (smartphones e tablets pequenos) */
            @media (max-width: 767.98px) {
                .header {
                    min-height: 120px; /* Ajusta a altura mínima do cabeçalho em telas menores */
                }
                .header-logo {
                    max-height: 60px; /* Reduz o tamanho do logo em telas menores */
                }
                .header h1 {
                    font-size: 22px; /* Reduz o tamanho do título do cabeçalho */
                }

                /* Esconde o navbar desktop e mostra o toggle do hambúrguer */
                .navbar {
                    display: none;
                }
                .hamburger-menu-toggle, .mobile-nav {
                    display: block; /* Mostra o botão hambúrguer e o menu mobile */
                }

                /* Ajusta padding para conteúdo principal em telas pequenas */
                .container-fluid {
                    padding: 10px;
                }
                .main-content {
                    padding: 15px;
                }

                /* Tabelas: Adiciona rolagem horizontal para evitar quebras de layout */
                /* Esta regra é mantida, mas a transformação para "cartões" é prioritária */
                .table-responsive {
                    overflow-x: auto;
                    -webkit-overflow-scrolling: touch; /* Para rolagem suave em iOS */
                }
                /* Para usar isso, você precisará envolver suas tabelas com <div class="table-responsive">...</div> */

                /* Botões: Ajusta tamanho para telas pequenas */
                .btn {
                    padding: 6px 12px;
                    font-size: 12px;
                }

                /* === AJUSTES DE RESPONSIVIDADE PARA ÍCONES DO DASHBOARD === */
                .icon-card-link i {
                    font-size: 3.2em; /* Aumentado de 2.8em para 3.2em */
                    margin-bottom: 6px; /* Ajustado para telas menores */
                }
                /* ============================================================ */

                /* === AJUSTES DE RESPONSIVIDADE PARA BOTÕES DE AÇÃO DA TABELA === */
                /* Botões de ação dentro da tabela: ÍCONE APENAS */
                .table .action-icon-btn {
                    padding: 8px;
                    width: 38px;
                    height: 38px;
                    margin-bottom: 5px;
                    margin-right: 5px;
                    text-indent: -9999px; /* Move o texto para fora da tela */
                    overflow: hidden; /* Esconde o texto que saiu da tela */
                    white-space: nowrap; /* Garante que o texto não quebre linha */
                    position: relative; /* Necessário para text-indent funcionar corretamente em alguns contextos */
                }
                .table .action-icon-btn i {
                    margin-right: 0;
                    font-size: 1.5em; /* Tamanho do ícone */
                    text-indent: 0; /* Garante que o ícone não seja afetado pelo text-indent do pai */
                    position: absolute; /* Posiciona o ícone de volta no centro */
                    left: 50%;
                    top: 50%;
                    transform: translate(-50%, -50%); /* Centraliza o ícone */
                }

                /* O botão "Cadastrar Novo Curso" (fora da tabela) mantém texto + ícone */
                .action-icon-btn.create {
                    font-size: 14px; /* Restaura o tamanho da fonte */
                    padding: 8px 15px; /* Restaura o padding */
                    width: auto; /* Restaura a largura automática */
                    height: auto; /* Restaura a altura automática */
                    margin-bottom: 10px; /* Garante que ele empilhe bem */
                    display: inline-flex; /* Garante que ele se comporte como flex */
                    text-indent: 0; /* Garante que o texto seja visível para este botão específico */
                    overflow: visible;
                    white-space: normal;
                    position: static;
                }
                .action-icon-btn.create i {
                    margin-right: 8px; /* Restaura a margem do ícone */
                    font-size: 1.1em; /* Restaura o tamanho do ícone */
                    position: static; /* Reset position */
                    transform: none; /* Reset transform */
                }
                /* ============================================================ */

                /* --- NOVOS ESTILOS PARA TABELAS RESPONSIVAS (VISÃO DE CARTÃO) --- */
                .table.table-striped, .table.table-hover {
                    border: none; /* Remove bordas da tabela principal */
                }

                .table thead {
                    display: none; /* Esconde o cabeçalho da tabela em telas pequenas */
                }

                .table tbody tr {
                    display: block; /* Faz cada linha da tabela se comportar como um bloco */
                    margin-bottom: 15px; /* Espaçamento entre os "cartões" */
                    border: 1px solid #e0e0e0; /* Borda para o cartão */
                    border-radius: 8px; /* Cantos arredondados */
                    background-color: #ffffff; /* Fundo do cartão */
                    box-shadow: 0 2px 5px rgba(0,0,0,0.05); /* Sombra suave */
                    padding: 10px; /* Padding interno do cartão */
                }

                .table tbody td {
                    display: flex; /* Usa flexbox para alinhar rótulo e valor */
                    justify-content: space-between; /* Empurra o rótulo para a esquerda e o valor para a direita */
                    align-items: center; /* Centraliza verticalmente */
                    text-align: right; /* Alinha o conteúdo da célula à direita */
                    padding: 8px 0; /* Padding vertical para cada "campo" */
                    border-bottom: 1px solid #f0f0f0; /* Separador entre os campos */
                }

                .table tbody td:last-child {
                    border-bottom: none; /* Remove a borda do último campo */
                    padding-bottom: 0;
                }

                .table tbody td::before {
                    content: attr(data-label); /* Exibe o valor do atributo data-label como rótulo */
                    font-weight: bold;
                    text-align: left; /* Alinha o rótulo à esquerda */
                    flex-basis: 40%; /* Dá ao rótulo uma base de 40% da largura */
                    color: #555;
                    padding-right: 10px; /* Espaço entre o rótulo e o valor */
                }

                /* Ajustes específicos para a coluna "Ações" */
                .table tbody td[data-label="Ações"] {
                    justify-content: center; /* Centraliza os botões de ação */
                    padding-top: 15px; /* Adiciona um espaço acima dos botões */
                    flex-wrap: wrap; /* Permite que os botões quebrem linha se houver muitos */
                    gap: 5px; /* Espaçamento entre os botões */
                }

                .table tbody td[data-label="Ações"]::before {
                    content: ""; /* Esconde o rótulo "Ações" */
                    display: none;
                }
                /* --- FIM DOS NOVOS ESTILOS PARA TABELAS RESPONSIVAS --- */
            }

            /* Para telas menores que 576px (smartphones muito pequenos) */
            @media (max-width: 575.98px) {
                .header {
                    min-height: 100px; /* Mais um ajuste para telas muito pequenas */
                    padding: 10px 15px;
                }
                .header-logo {
                    max-height: 50px; /* Logo ainda menor */
                }
                .header h1 {
                    font-size: 18px;
                }
                /* Oculta o navbar desktop */
                .navbar {
                    display: none;
                }
                .hamburger-menu-toggle, .mobile-nav {
                    display: block; /* Garante que o botão hambúrguer e o menu mobile sejam visíveis */
                }
                .main-content {
                    padding: 10px;
                }
                .footer {
                    font-size: 12px;
                }
                /* === AJUSTES DE RESPONSIVIDADE PARA ÍCONES DO DASHBOARD === */
                .icon-card-link i {
                    font-size: 2.8em; /* Aumentado de 2.5em para 2.8em */
                    margin-bottom: 5px; /* Ajustado para telas muito pequenas */
                }
                /* ============================================================ */

                /* Media query para botões de formulário em telas pequenas */
                .form-btn-icon {
                    width: 100%; /* Ocupa a largura total */
                    margin-right: 0; /* Remove margem lateral */
                    margin-bottom: 10px; /* Adiciona margem inferior para empilhar */
                }
            }

            /* FORÇAR FONT AWESOME - TENTATIVA DE CORREÇÃO PARA ÍCONES SUMINDO */
            /* Isso deve ser colocado no final do bloco <style> para maior especificidade */
            .fa, .fas, .far, .fal, .fad, .fab {
                font-family: "Font Awesome 5 Free" !important;
                font-weight: 900 !important; /* Para ícones sólidos (fas) */
            }
            .far { /* Para ícones regulares */
                font-weight: 400 !important;
            }
            .fab { /* Para ícones de marcas */
                font-family: "Font Awesome 5 Brands" !important;
                font-weight: 400 !important;
            }
            /* FIM DA FORÇAR FONT AWESOME */
        </style>
        @stack('styles') {{-- Ponto para estilos específicos da página --}}
    </head>
    <body>
        <div class="header">
            {{-- O logotipo será inserido aqui. Substitua 'images/logo.png' pelo caminho real do seu logo --}}
            {{-- Certifique-se de que seu logo está na pasta 'public/images/' ou ajuste o caminho --}}
            <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name', 'Sistema Escolar') }} Logo" class="header-logo">
            <h1>{{ config('app.name', 'Sistema Escolar') }}</h1>

            {{-- Botão Hamburger para Mobile --}}
            <button class="hamburger-menu-toggle" id="hamburger-toggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        {{-- Menu de Navegação Desktop --}}
        <div class="navbar" id="desktop-navbar">
            <ul>
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                {{--<li><a href="{{ route('minicursos.index') }}">Minicursos</a></li>--}}
                <li><a href="{{ route('cursos.index') }}">Cursos</a></li>
                <li><a href="{{ route('disciplinas.index') }}">Disciplinas</a></li>
                <li><a href="{{ route('turmas.index') }}">Turmas</a></li>
                <li><a href="{{ route('professores.index') }}">Professores</a></li>
                <li><a href="{{ route('alunos.index') }}">Alunos</a></li>
                <li><a href="{{ route('minicursos.index') }}">Minicursos</a></li>
                <li><a href="{{ route('financeiro.index') }}">Financeiro</a></li>
                {{--<li><a href="{{ route('financeiro.index') }}">Financeiro</a></li>--}}
                {{--<li><a href="{{ route('financeiro.relatorios') }}">Relatórios</a></li>--}}
                <!-- Links de autenticação (ajuste as rotas conforme seu sistema) -->
                @guest
                    <li><a href="{{ route('login') }}">Login</a></li>
                    {{-- <li><a href="{{ route('register') }}">Registrar</a></li> --}}
                @endguest
                @auth
                    <li><a href="#">Olá, {{ Auth::user()->name ?? 'Usuário' }}</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" style="background: none; border: none; color: #ecf0f1; cursor: pointer; font-weight: bold; padding: 0;">Sair</button>
                        </form>
                    </li>
                @endauth
            </ul>
        </div>

        {{-- Menu de Navegação Mobile (Off-canvas) --}}
        <div class="mobile-nav" id="mobile-nav">
            <button class="close-btn" id="close-mobile-nav">
                <i class="fas fa-times"></i>
            </button>
            <ul>
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('cursos.index') }}">Cursos</a></li>
                <li><a href="{{ route('disciplinas.index') }}">Disciplinas</a></li>
                <li><a href="{{ route('turmas.index') }}">Turmas</a></li>
                <li><a href="{{ route('professores.index') }}">Professores</a></li>
                <li><a href="{{ route('alunos.index') }}">Alunos</a></li>
                <li><a href="{{ route('minicursos.index') }}">Minicursos</a></li>
                <li><a href="{{ route('financeiro.index') }}">Financeiro</a></li>
                @guest
                    <li><a href="{{ route('login') }}">Login</a></li>
                @endguest
                @auth
                    <li><a href="#">Olá, {{ Auth::user()->name ?? 'Usuário' }}</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" style="background: none; border: none; color: #ecf0f1; cursor: pointer; font-weight: bold; padding: 0;">Sair</button>
                        </form>
                    </li>
                @endauth
            </ul>
        </div>

        <div class="container-fluid">
            <div class="main-content">
                @yield('content')
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Seu Sistema Escolar. Todos os direitos reservados.</p>
        </div>

        {{-- NOVO: Bootstrap 5 JS (via CDN) --}}
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        {{-- NOVO: Biblioteca Chart.js (via CDN) --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        @stack('scripts') {{-- Ponto para scripts específicos da página --}}

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const hamburgerToggle = document.getElementById('hamburger-toggle');
                const closeMobileNav = document.getElementById('close-mobile-nav');
                const mobileNav = document.getElementById('mobile-nav');
                const body = document.body;

                // Abre o menu mobile
                hamburgerToggle.addEventListener('click', function() {
                    body.classList.add('mobile-menu-open');
                });

                // Fecha o menu mobile pelo botão "X"
                closeMobileNav.addEventListener('click', function() {
                    body.classList.remove('mobile-menu-open');
                });

                // Fecha o menu mobile clicando fora (no overlay)
                body.addEventListener('click', function(event) {
                    // Verifica se o clique foi no overlay e não dentro do menu ou no botão de toggle
                    if (body.classList.contains('mobile-menu-open') &&
                        !mobileNav.contains(event.target) &&
                        !hamburgerToggle.contains(event.target)) {
                        body.classList.remove('mobile-menu-open');
                    }
                });

                // Fecha o menu mobile ao clicar em um link (opcional, mas melhora UX)
                mobileNav.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', function() {
                        body.classList.remove('mobile-menu-open');
                    });
                });

                // Script do Chart.js (movido do financeiro/index.blade.php)
                var ctx = document.getElementById('graficoReceitas');
                if (ctx) { // Verifica se o elemento existe
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            // Assumindo que $ultimosMeses e $valoresMensais estão sendo passados pelo controller
                            labels: {!! json_encode($ultimosMeses ?? []) !!},
                            datasets: [{
                                label: 'Receita Mensal (R\$)',
                                data: {!! json_encode($valoresMensais ?? []) !!},
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return 'R\$ ' + value.toFixed(2).replace('.', ',');
                                        }
                                    }
                                }
                            },
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return 'R\$ ' + context.raw.toFixed(2).replace('.', ',');
                                        }
                                    }
                                }
                            }
                        }
                    });
                } else {
                    console.error("Elemento canvas #graficoReceitas não encontrado.");
                }
            });
        </script>
    </body>
</html>
