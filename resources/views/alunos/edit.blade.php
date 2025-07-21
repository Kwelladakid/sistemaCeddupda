@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Editar Aluno: {{ $aluno->nome }}</h1>

        {{-- Exibe erros de validação, se houver --}}
        @if ($errors->any())
            <div class="alert alert-danger" style="color: red; margin-bottom: 20px; padding: 10px; border: 1px solid red; border-radius: 5px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Início do formulário HTML --}}
        <form action="{{ route('alunos.update', $aluno->id) }}" method="POST">
            @csrf {{-- Token CSRF para segurança --}}
            @method('PUT') {{-- Método HTTP para atualização --}}

            {{-- Inclui o formulário comum, passando o objeto $aluno existente --}}
            @include('alunos.form', ['aluno' => $aluno])
        </form>
        {{-- Fim do formulário HTML --}}
    </div>
@endsection
