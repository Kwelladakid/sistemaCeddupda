@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Registrar Novo Usuário</h1>

    {{-- Mensagem de sucesso após o registro --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Mensagens de erro gerais --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Houve alguns problemas com o seu registro:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif



@section('content')
<div class="container">
    <h1>Cadastrar Usuário</h1>
    <form action="{{ route('usuarios.store') }}" method="POST">
        @csrf

        <!-- Nome -->
        <div class="mb-3">
            <label for="name" class="form-label">Nome</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <!-- E-mail -->
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <!-- Senha -->
        <div class="mb-3">
            <label for="password" class="form-label">Senha</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>

        <!-- Papel (Somente Administrador e Secretaria) -->
        <div class="mb-3">
            <label for="role" class="form-label">Papel</label>
            <select name="role" id="role" class="form-control" required>
                <option value="administrador">Administrador</option>
                <option value="secretaria">Secretaria</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Cadastrar</button>
    </form>
</div>
@endsection
