<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sistema Escolar') }}</title>

        <!-- CSS BÁSICO EMBUTIDO (SEM DEPENDÊNCIAS EXTERNAS) -->
        <style>
            body {
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
                background-color: #f4f7f6; /* Fundo cinza claro */
                color: #333;
                line-height: 1.6;
            }
            .container-fluid {
                width: 100%;
                max-width: 1200px; /* Largura máxima do conteúdo */
                margin: 0 auto;
                padding: 20px;
                box-sizing: border-box;
            }
            .header {
                background-color: #2c3e50; /* Azul escuro */
                color: #ffffff;
                padding: 15px 20px;
                text-align: center;
                box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            }
            .header h1 {
                margin: 0;
                font-size: 28px;
            }
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
            .main-content {
                background-color: #ffffff;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                min-height: 400px; /* Altura mínima para o conteúdo */
            }
            .footer {
                text-align: center;
                padding: 20px;
                margin-top: 30px;
                color: #7f8c8d; /* Cinza médio */
                font-size: 14px;
            }
            /* Estilos para botões básicos */
            .btn {
                display: inline-block;
                padding: 10px 20px;
                font-size: 16px;
                font-weight: bold;
                text-align: center;
                text-decoration: none;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s ease, color 0.3s ease;
            }
            .btn-primary {
                background-color: #3498db; /* Azul */
                color: #ffffff;
                border: 1px solid #3498db;
            }
            .btn-primary:hover {
                background-color: #2980b9;
                border-color: #2980b9;
            }
            .btn-success {
                background-color: #2ecc71; /* Verde */
                color: #ffffff;
                border: 1px solid #2ecc71;
            }
            .btn-success:hover {
                background-color: #27ae60;
                border-color: #27ae60;
            }
            .btn-warning {
                background-color: #f39c12; /* Laranja */
                color: #ffffff;
                border: 1px solid #f39c12;
            }
            .btn-warning:hover {
                background-color: #e67e22;
                border-color: #e67e22;
            }
            .btn-danger {
                background-color: #e74c3c; /* Vermelho */
                color: #ffffff;
                border: 1px solid #e74c3c;
            }
            .btn-danger:hover {
                background-color: #c0392b;
                border-color: #c0392b;
            }
            .btn-info {
                background-color: #1abc9c; /* Turquesa */
                color: #ffffff;
                border: 1px solid #1abc9c;
            }
            .btn-info:hover {
                background-color: #16a085;
                border-color: #16a085;
            }
            .btn-secondary {
                background-color: #95a5a6; /* Cinza */
                color: #ffffff;
                border: 1px solid #95a5a6;
            }
            .btn-secondary:hover {
                background-color: #7f8c8d;
                border-color: #7f8c8d;
            }
            /* Estilos para tabelas */
            .table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }
            .table th, .table td {
                border: 1px solid #ddd;
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
            /* Estilos para formulários */
            .form-group {
                margin-bottom: 15px;
            }
            .form-group label {
                display: block;
                margin-bottom: 5px;
                font-weight: bold;
            }
            .form-control {
                width: 100%;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
                font-size: 16px;
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
            /* Estilos para mensagens de sessão */
            .alert {
                padding: 15px;
                margin-bottom: 20px;
                border: 1px solid transparent;
                border-radius: 4px;
            }
            .alert-success {
                color: #3c763d;
                background-color: #dff0d8;
                border-color: #d6e9c6;
            }
            .alert-danger {
                color: #a94442;
                background-color: #f2dede;
                border-color: #ebccd1;
            }
            .alert-warning {
                color: #8a6d3b;
                background-color: #fcf8e3;
                border-color: #faebcc;
            }
            .alert-info {
                color: #31708f;
                background-color: #d9edf7;
                border-color: #bce8f1;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>{{ config('app.name', 'Sistema Escolar') }}</h1>
        </div>

        <div class="navbar">
            <ul>
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('minicursos.index') }}">Minicursos</a></li>
                 <li class="nav-item">
                        <a class="nav-link" href="{{ route('alunos.index') }}">Alunos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('professores.index') }}">Professores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cursos.index') }}">Cursos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('turmas.index') }}">Turmas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('financeiro.index') }}">Financeiro</a>
                    </li>
                <!-- Links de autenticação (ajuste as rotas conforme seu sistema) -->
                @guest
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Registrar</a></li>
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

        <!-- Scripts JavaScript (se houver necessidade futura, pode ser adicionado aqui) -->
        <!-- Por enquanto, nenhum JS externo ou de compilação. -->

    </body>
</html>
