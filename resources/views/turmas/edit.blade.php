<!-- resources/views/turmas/create.blade.php -->
@extends('layouts.app')

@section('content')
    <h2>Cadastrar Nova Turma</h2>

    @if ($errors->any())
        <div>
            <strong>Erros:</strong>
            <ul>
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('turmas.store') }}" method="POST">
        @include('turmas.form')
    </form>
@endsection
