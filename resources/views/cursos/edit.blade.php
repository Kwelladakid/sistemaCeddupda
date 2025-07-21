@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Editar Curso</h1>

        {{-- Exibe erros de validação, se houver --}}
        @if ($errors->any())
            <div style="color: red; margin-bottom: 20px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('cursos.update', $curso->id) }}" method="POST">
            @csrf
            @method('PUT') {{-- Método HTTP PUT para atualização --}}

            <div style="margin-bottom: 15px;">
                <label for="nome">Nome do Curso:</label><br>
                <input type="text" id="nome" name="nome" value="{{ old('nome', $curso->nome) }}" required
                       style="width: 100%; padding: 8px; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 15px;">
                <label for="descricao">Descrição:</label><br>
                <textarea id="descricao" name="descricao" rows="5"
                          style="width: 100%; padding: 8px; box-sizing: border-box;">{{ old('descricao', $curso->descricao) }}</textarea>
            </div>

            <div style="margin-bottom: 15px;">
                <label for="carga_horaria">Carga Horária (horas):</label><br>
                <input type="number" id="carga_horaria" name="carga_horaria" value="{{ old('carga_horaria', $curso->carga_horaria) }}" required min="1"
                       style="width: 100%; padding: 8px; box-sizing: border-box;">
            </div>

            <div style="margin-top: 20px;">
                <button type="submit"
                        style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
                    Atualizar Curso
                </button>
                <a href="{{ route('cursos.index') }}"
                   style="padding: 10px 20px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 5px; margin-left: 10px;">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection
