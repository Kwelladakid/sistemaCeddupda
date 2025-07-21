@extends('layouts.app')

@section('content')
<div class="auth-container">
    <h2>Login</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="cpf">CPF</label>
            <input type="text" name="cpf" id="cpf" class="form-control" required autofocus>
        </div>

        <div class="form-group">
            <label for="password">Senha</label>
            <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password">
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">
                Lembrar-me
            </label>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                Entrar
            </button>
        </div>

        <div class="links">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Esqueceu sua senha?</a>
            @endif
            <br>

        </div>
    </form>
</div>

<!-- Adicionando o script para a máscara de CPF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"></script>
<script>
    $(document).ready(function() {
        // Aplica a máscara de CPF ao campo
        $('#cpf').inputmask('999.999.999-99');
    });
</script>
@endsection
