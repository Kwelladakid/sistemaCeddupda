@extends('layouts.app')

@section('content')


<div class="auth-container">
    <h2>Registrar</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label for="name">Nome</label>
            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
        </div>

        <div class="form-group">
            <label for="email">E-mail</label>
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="email">
        </div>

        <div class="form-group">
            <label for="password">Senha</label>
            <input id="password" type="password" class="form-control" name="password" required autocomplete="new-password">
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirmar Senha</label>
            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                Registrar
            </button>
        </div>

        <div class="links">
            <a href="{{ route('login') }}">Já tem uma conta? Faça login</a>
        </div>
    </form>
</div>
@endsection
