@extends('layouts.financeiro')

@section('content')
<div class="container">
    <h1 class="mb-4">Resultados da Busca</h1>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Alunos Encontrados</span>
            <a href="{{ route('financeiro.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Voltar
            </a>
        </div>
        <div class="card-body">
            @if(isset($alunos) && $alunos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Matrícula</th>
                                <th>CPF</th>
                                <th>Curso</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alunos as $aluno)
                                <tr>
                                    <td>{{ $aluno->nome }}</td>
                                    <td>{{ $aluno->matricula }}</td>
                                    <td>{{ $aluno->cpf }}</td>
                                    <td>{{ $aluno->curso->nome ?? 'Não informado' }}</td>
                                    <td>
                                        @if(isset($aluno->status))
                                            @if($aluno->status == 'ativo')
                                                <span class="badge bg-success">Ativo</span>
                                            @elseif($aluno->status == 'inativo')
                                                <span class="badge bg-danger">Inativo</span>
                                            @elseif($aluno->status == 'trancado')
                                                <span class="badge bg-warning text-dark">Trancado</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($aluno->status) }}</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Não informado</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            {{-- Visualizar financeiro do aluno --}}
                                            @if(Route::has('financeiro.alunos.show'))
                                                <a href="{{ route('financeiro.alunos.show', $aluno->id) }}"
                                                   class="btn btn-info" title="Ver Financeiro">
                                                    <i class="fas fa-money-bill-wave"></i>
                                                </a>
                                            @endif

                                            {{-- Registrar novo pagamento --}}
                                            @if(Route::has('financeiro.pagamentos.create'))
                                                <a href="{{ route('financeiro.pagamentos.create', ['aluno_id' => $aluno->id]) }}"
                                                   class="btn btn-success" title="Novo Pagamento">
                                                    <i class="fas fa-plus-circle"></i>
                                                </a>
                                            @endif

                                            {{-- Ver mensalidades --}}
                                            @if(Route::has('financeiro.mensalidades.index'))
                                                <a href="{{ route('financeiro.mensalidades.index', ['aluno_id' => $aluno->id]) }}"
                                                   class="btn btn-primary" title="Ver Mensalidades">
                                                    <i class="fas fa-file-invoice-dollar"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Paginação, se aplicável --}}
                @if(method_exists($alunos, 'links'))
                    <div class="mt-3">
                        {{ $alunos->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Nenhum aluno encontrado com o termo "{{ request('termo') }}".
                </div>
                <div class="mt-3">
                    <a href="{{ route('financeiro.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-1"></i> Voltar para o Dashboard
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
