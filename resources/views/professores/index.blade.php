{{-- resources/views/professores/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Professores')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Gerenciamento de Professores</h1>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Professores</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-chalkboard-teacher me-1"></i>
            Lista de Professores
            <a href="{{ route('professores.create') }}" class="btn btn-primary btn-sm float-end">
                <i class="fas fa-plus"></i> Novo Professor
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="professoresTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>Especialidade</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($professores as $prof)
                        <tr>
                            <td>{{ $prof->nome }}</td>
                            <td>{{ $prof->cpf }}</td>
                            <td>{{ $prof->especialidade }}</td>
                            <td class="text-center" style="width: 200px;">
                                <a href="{{ route('professores.show', $prof->id) }}" class="btn btn-info btn-sm" title="Visualizar">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('professores.edit', $prof->id) }}" class="btn btn-warning btn-sm" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('professores.destroy', $prof->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Excluir"
                                            onclick="return confirm('Tem certeza que deseja excluir este professor?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#professoresTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json'
            },
            responsive: true
        });
    });
</script>
@endsection
