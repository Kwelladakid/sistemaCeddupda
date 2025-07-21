@extends('layouts.app')

@section('title', 'Dashboard Principal')

{{-- REMOVA ESTE BLOCO INTEIRO SE ELE EXISTIR NO SEU dashboard.blade.php --}}
{{--
@section('styles')
    <style>
        .quick-actions-grid { /* ... */ }
        .icon-card-link { /* ... */ }
        /* ... e todo o CSS relacionado aos botões do dashboard ... */
    </style>
@endsection
--}}

@section('content')
    <div class="container-fluid">
        <div class="main-content">
            <h1>Bem-vindo ao Dashboard Principal!</h1>

            <p>Aqui você pode ter uma visão geral do sistema e acessar as principais funcionalidades.</p>

            <div style="margin-top: 30px;">
                <h2>Ações Rápidas</h2>
                <div class="quick-actions-grid">
                    <a href="{{ route('professores.index') }}" class="icon-card-link professores">
                        <i class="fas fa-chalkboard-teacher"></i>
                        Gerenciar Professores
                    </a>
                    <a href="{{ route('alunos.index') }}" class="icon-card-link alunos">
                        <i class="fas fa-user-graduate"></i>
                        Gerenciar Alunos
                    </a>
                    <a href="{{ route('cursos.index') }}" class="icon-card-link cursos">
                        <i class="fas fa-book-open"></i>
                        Gerenciar Cursos
                    </a>
                    <a href="{{ route('disciplinas.index') }}" class="icon-card-link disciplinas">
                        <i class="fas fa-book"></i>
                        Gerenciar Disciplinas
                    </a>
                    <a href="{{ route('turmas.index') }}" class="icon-card-link turmas">
                        <i class="fas fa-users"></i>
                        Gerenciar Turmas
                    </a>
                    {{-- Adicione mais botões conforme as principais funcionalidades do seu sistema --}}
                </div>
            </div>

            {{-- Você pode adicionar mais seções aqui, como: --}}
            {{--
            <div style="margin-top: 40px;">
                <h2>Estatísticas Rápidas</h2>
                <div style="display: flex; flex-wrap: wrap; gap: 20px;">
                    <div style="background-color: #ecf0f1; padding: 20px; border-radius: 8px; text-align: center; flex: 1; min-width: 200px;">
                        <h3>Total de Alunos</h3>
                        <p style="font-size: 2em; font-weight: bold; color: #3498db;">{{ $totalAlunos ?? 'N/A' }}</p>
                    </div>
                    <div style="background-color: #ecf0f1; padding: 20px; border-radius: 8px; text-align: center; flex: 1; min-width: 200px;">
                        <h3>Total de Professores</h3>
                        <p style="font-size: 2em; font-weight: bold; color: #2ecc71;">{{ $totalProfessores ?? 'N/A' }}</p>
                    </div>
                    <div style="background-color: #ecf0f1; padding: 20px; border-radius: 8px; text-align: center; flex: 1; min-width: 200px;">
                        <h3>Total de Cursos</h3>
                        <p style="font-size: 2em; font-weight: bold; color: #f39c12;">{{ $totalCursos ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
            --}}
        </div>
    </div>
@endsection

@section('scripts')
    {{-- Scripts específicos para o dashboard, se houver --}}
@endsection
