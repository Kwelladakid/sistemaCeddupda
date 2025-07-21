@extends('layouts.app')

@section('title', 'Dashboard do Administrador')

@section('content')
<div class="container-fluid">
    <div class="main-content">
        <h2>Dashboard do Administrador</h2>

        {{-- Seção 1: Visão Geral Rápida (Quick Stats) --}}
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-line me-1"></i> Visão Geral Rápida
            </div>
            <div class="card-body">
                <div class="quick-stats-grid">
                    {{-- Cartão de Estatística: Total de Alunos --}}
                    <div class="stat-card">
                        <div class="stat-icon bg-primary"><i class="fas fa-users"></i></div>
                        <div class="stat-info">
                            <div class="stat-value">{{ $totalAlunos ?? 'N/A' }}</div>
                            <div class="stat-label">Alunos</div>
                        </div>
                    </div>
                    {{-- Cartão de Estatística: Total de Professores --}}
                    <div class="stat-card">
                        <div class="stat-icon bg-success"><i class="fas fa-chalkboard-teacher"></i></div>
                        <div class="stat-info">
                            <div class="stat-value">{{ $totalProfessores ?? 'N/A' }}</div>
                            <div class="stat-label">Professores</div>
                        </div>
                    </div>
                    {{-- Cartão de Estatística: Total de Cursos --}}
                    <div class="stat-card">
                        <div class="stat-icon bg-info"><i class="fas fa-book"></i></div>
                        <div class="stat-info">
                            <div class="stat-value">{{ $totalCursos ?? 'N/A' }}</div>
                            <div class="stat-label">Cursos</div>
                        </div>
                    </div>
                    {{-- Cartão de Estatística: Turmas Ativas --}}
                    <div class="stat-card">
                        <div class="stat-icon bg-warning"><i class="fas fa-school"></i></div>
                        <div class="stat-info">
                            <div class="stat-value">{{ $totalTurmasAtivas ?? 'N/A' }}</div>
                            <div class="stat-label">Turmas Ativas</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Seção 2: Ações Rápidas (Quick Actions) --}}
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-bolt me-1"></i> Ações Rápidas
            </div>
            <div class="card-body">
                <div class="quick-actions-grid">
                    <a href="{{ route('alunos.index') }}" class="icon-card-link alunos">
                        <i class="fas fa-user-graduate"></i>
                        <span>Alunos</span>
                    </a>
                    <a href="{{ route('professores.index') }}" class="icon-card-link professores">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>Professores</span>
                    </a>
                    <a href="{{ route('cursos.index') }}" class="icon-card-link cursos">
                        <i class="fas fa-book-open"></i>
                        <span>Cursos</span>
                    </a>
                    <a href="{{ route('minicursos.index') }}" class="icon-card-link"> {{-- Adicionei um ícone genérico, se quiser um específico, me diga --}}
                        <i class="fas fa-laptop-code"></i>
                        <span>Minicursos</span>
                    </a>
                    <a href="{{ route('turmas.index') }}" class="icon-card-link turmas">
                        <i class="fas fa-users-class"></i>
                        <span>Turmas</span>
                    </a>
                    <a href="{{ route('disciplinas.index') }}" class="icon-card-link disciplinas">
                        <i class="fas fa-pencil-ruler"></i>
                        <span>Disciplinas</span>
                    </a>
                    <a href="{{ route('financeiro.index') }}" class="icon-card-link">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Financeiro</span>
                    </a>
                    <a href="{{ route('usuarios.create') }}" class="icon-card-link">
                        <i class="fas fa-user-plus"></i>
                        <span>Novo Usuário</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Seção 3: Atividade Recente --}}
        <div class="card">
            <div class="card-header">
                <i class="fas fa-history me-1"></i> Atividade Recente
            </div>
            <div class="card-body">
                @if(isset($ultimasMatriculas) && $ultimasMatriculas->isNotEmpty())
                    {{-- O table-responsive é mantido aqui para garantir rolagem em caso de muitas colunas,
                         mas o CSS de "cartão" em mobile ainda se aplicará --}}
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Aluno</th>
                                    <th>Turma/Curso</th>
                                    <th>Data</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ultimasMatriculas as $matricula)
                                    <tr>
                                        <td data-label="Aluno">{{ $matricula->aluno->nome ?? 'N/A' }}</td>
                                        <td data-label="Turma/Curso">{{ $matricula->turma->nome ?? ($matricula->curso->nome ?? 'N/A') }}</td>
                                        <td data-label="Data">{{ $matricula->created_at->format('d/m/Y H:i') }}</td>
                                        <td data-label="Ação"><span class="badge bg-success">Matrícula</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p>Nenhuma atividade recente para exibir.</p>
                @endif
            </div>
        </div>

    </div> {{-- Fechamento da div main-content --}}
</div> {{-- Fechamento da div container-fluid --}}
@endsection

{{-- Adiciona estilos específicos para o dashboard --}}
@push('styles')
<style>
    /* Estilos para os cartões de estatísticas rápidas */
    .quick-stats-grid {
        display: grid;
        /* Cria colunas que se ajustam automaticamente, com mínimo de 200px e máximo de 1 fração */
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px; /* Espaçamento entre os cartões */
        margin-bottom: 20px;
    }

    .stat-card {
        background-color: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        display: flex; /* Usa flexbox para alinhar ícone e info */
        align-items: center; /* Alinha verticalmente no centro */
        padding: 20px;
        transition: transform 0.2s ease-in-out; /* Efeito suave no hover */
    }

    .stat-card:hover {
        transform: translateY(-3px); /* Levanta o cartão no hover */
        box-shadow: 0 4px 15px rgba(0,0,0,0.1); /* Sombra mais pronunciada */
    }

    .stat-icon {
        font-size: 2.5em;
        padding: 15px;
        border-radius: 50%; /* Torna o ícone circular */
        color: #ffffff;
        margin-right: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Cores para os ícones de estatísticas (reaproveitando classes de cores existentes) */
    .stat-icon.bg-primary { background-color: #3498db; }
    .stat-icon.bg-success { background-color: #2ecc71; }
    .stat-icon.bg-info { background-color: #1abc9c; }
    .stat-icon.bg-warning { background-color: #f39c12; }

    .stat-info {
        flex-grow: 1; /* Permite que o info ocupe o espaço restante */
    }

    .stat-value {
        font-size: 2em;
        font-weight: bold;
        color: #333;
    }

    .stat-label {
        font-size: 0.9em;
        color: #777;
        text-transform: uppercase;
    }

    /* Media Queries para os cartões de estatísticas */
    @media (max-width: 767.98px) {
        .quick-stats-grid {
            grid-template-columns: 1fr; /* Uma coluna em telas pequenas */
        }
        .stat-card {
            flex-direction: row; /* Mantém ícone e texto lado a lado */
            justify-content: center; /* Centraliza o conteúdo */
            text-align: center;
        }
        .stat-icon {
            margin-right: 10px;
            font-size: 2em;
            padding: 10px;
        }
        .stat-info {
            text-align: left; /* Alinha o texto à esquerda */
        }
        .stat-value {
            font-size: 1.8em;
        }
    }

    @media (max-width: 575.98px) {
        .stat-card {
            flex-direction: column; /* Empilha ícone e texto em telas muito pequenas */
            text-align: center;
        }
        .stat-icon {
            margin-right: 0;
            margin-bottom: 10px;
        }
        .stat-info {
            text-align: center;
        }
    }

    /* Ajustes para o quick-actions-grid existente para melhor responsividade */
    /* Sobrescreve as regras globais para um layout mais denso no dashboard */
    .quick-actions-grid {
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); /* Mais colunas para ações rápidas */
        gap: 15px; /* Espaçamento entre os cartões de ação */
    }

    @media (max-width: 767.98px) {
        .quick-actions-grid {
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); /* Ajuste para telas médias */
            gap: 10px;
        }
        .icon-card-link {
            width: auto; /* Deixa o flexbox controlar a largura */
            height: 100px; /* Altura um pouco menor */
            font-size: 12px;
        }
        .icon-card-link i {
            font-size: 3em; /* Ícones um pouco menores */
        }
    }

    @media (max-width: 575.98px) {
        .quick-actions-grid {
            grid-template-columns: repeat(2, 1fr); /* Duas colunas em telas muito pequenas */
        }
        .icon-card-link {
            height: 90px;
            font-size: 11px;
        }
        .icon-card-link i {
            font-size: 2.5em;
        }
    }

</style>
@endpush
