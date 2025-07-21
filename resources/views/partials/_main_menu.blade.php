<ul class="list-unstyled components">
    <p>Navegação</p>
    <li class="{{ Request::routeIs('dashboard') ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}">
            <i class="fas fa-home me-2"></i>
            Dashboard Principal
        </a>
    </li>

    {{-- Módulo Financeiro --}}
    <li class="{{ Request::routeIs('financeiro.*') ? 'active' : '' }}">
        <a href="#financeSubmenu" data-bs-toggle="collapse" aria-expanded="{{ Request::routeIs('financeiro.*') ? 'true' : 'false' }}" class="dropdown-toggle">
            <i class="fas fa-money-bill-wave me-2"></i>
            Financeiro
        </a>
        <ul class="collapse list-unstyled {{ Request::routeIs('financeiro.*') ? 'show' : '' }}" id="financeSubmenu">
            <li class="{{ Request::routeIs('financeiro.index') ? 'active' : '' }}">
                <a href="{{ route('financeiro.index') }}">Visão Geral</a>
            </li>
            <li class="{{ Request::routeIs('financeiro.pagamentos.*') ? 'active' : '' }}">
                <a href="{{ route('financeiro.pagamentos.index') }}">Pagamentos</a>
            </li>
            <li class="{{ Request::routeIs('financeiro.mensalidades.*') ? 'active' : '' }}">
                <a href="{{ route('financeiro.mensalidades.index') }}">Mensalidades</a>
            </li>
            <li class="{{ Request::routeIs('financeiro.relatorios') ? 'active' : '' }}">
                <a href="{{ route('financeiro.relatorios') }}">Relatórios Financeiros</a>
            </li>
        </ul>
    </li>

    {{-- Módulo Alunos (Exemplo) --}}
    <li class="{{ Request::routeIs('alunos.*') ? 'active' : '' }}">
        <a href="#alunosSubmenu" data-bs-toggle="collapse" aria-expanded="{{ Request::routeIs('alunos.*') ? 'true' : 'false' }}" class="dropdown-toggle">
            <i class="fas fa-user-graduate me-2"></i>
            Alunos
        </a>
        <ul class="collapse list-unstyled {{ Request::routeIs('alunos.*') ? 'show' : '' }}" id="alunosSubmenu">
            <li class="{{ Request::routeIs('alunos.index') ? 'active' : '' }}">
                <a href="{{ route('alunos.index') }}">Listar Alunos</a>
            </li>
            <li class="{{ Request::routeIs('alunos.create') ? 'active' : '' }}">
                <a href="{{ route('alunos.create') }}">Novo Aluno</a>
            </li>
        </ul>
    </li>

    {{-- Módulo Professores (Exemplo) --}}
    <li class="{{ Request::routeIs('professores.*') ? 'active' : '' }}">
        <a href="{{ route('professores.index') }}">
            <i class="fas fa-chalkboard-teacher me-2"></i>
            Professores
        </a>
    </li>

    {{-- Módulo Cursos (Exemplo) --}}
    <li class="{{ Request::routeIs('cursos.*') ? 'active' : '' }}">
        <a href="{{ route('cursos.index') }}">
            <i class="fas fa-book me-2"></i>
            Cursos
        </a>
    </li>

    {{-- Outros módulos podem ser adicionados aqui --}}
    {{-- Ex: Turmas, Disciplinas, Notas, Matrículas, etc. --}}
</ul>
