@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Cadastrar Novo Aluno</h1>

        {{-- Exibe erros de validação --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Erro!</strong> Verifique os campos abaixo:
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Formulário de cadastro --}}
        <form action="{{ route('alunos.store') }}" method="POST">
            @csrf

            {{-- Nome --}}
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" name="nome" id="nome" class="form-control" value="{{ old('nome') }}" required>
            </div>

            {{-- CPF --}}
            <div class="mb-3">
                <label for="cpf" class="form-label">CPF</label>
                <input type="text" name="cpf" id="cpf" class="form-control" value="{{ old('cpf') }}" required>
            </div>

            {{-- Data de Nascimento --}}
            <div class="mb-3">
                <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                <input type="date" name="data_nascimento" id="data_nascimento" class="form-control" value="{{ old('data_nascimento') }}" required>
            </div>

            {{-- Endereço --}}
            <div class="mb-3">
                <label for="endereco" class="form-label">Endereço</label>
                <textarea name="endereco" id="endereco" class="form-control" required>{{ old('endereco') }}</textarea>
            </div>

            {{-- Telefone --}}
            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" name="telefone" id="telefone" class="form-control" value="{{ old('telefone') }}" required>
            </div>

            {{-- E-mail --}}
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            {{-- Status --}}
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="ativo" {{ old('status') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                    <option value="inativo" {{ old('status') == 'inativo' ? 'selected' : '' }}>Inativo</option>
                    <option value="trancado" {{ old('status') == 'trancado' ? 'selected' : '' }}>Trancado</option>
                    <option value="formado" {{ old('status') == 'formado' ? 'selected' : '' }}>Formado</option>
                </select>
            </div>

            {{-- Botão de submissão --}}
            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>
@endsection
