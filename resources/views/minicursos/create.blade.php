@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Cadastrar Novo Minicurso</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('minicursos.store') }}">
                        @csrf

                        <div class="form-group row mb-3">
                            <label for="nome" class="col-md-4 col-form-label text-md-right">Nome do Minicurso</label>
                            <div class="col-md-6">
                                <input id="nome" type="text" class="form-control @error('nome') is-invalid @enderror" name="nome" value="{{ old('nome') }}" required autofocus>
                                @error('nome')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="professor_responsavel" class="col-md-4 col-form-label text-md-right">Professor Responsável</label>
                            <div class="col-md-6">
                                <input id="professor_responsavel" type="text" class="form-control @error('professor_responsavel') is-invalid @enderror" name="professor_responsavel" value="{{ old('professor_responsavel') }}" required>
                                @error('professor_responsavel')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="carga_horaria" class="col-md-4 col-form-label text-md-right">Carga Horária (horas)</label>
                            <div class="col-md-6">
                                <input id="carga_horaria" type="number" min="1" class="form-control @error('carga_horaria') is-invalid @enderror" name="carga_horaria" value="{{ old('carga_horaria') }}" required>
                                @error('carga_horaria')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="descricao" class="col-md-4 col-form-label text-md-right">Descrição</label>
                            <div class="col-md-6">
                                <textarea id="descricao" class="form-control @error('descricao') is-invalid @enderror" name="descricao" rows="3">{{ old('descricao') }}</textarea>
                                @error('descricao')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="data_inicio" class="col-md-4 col-form-label text-md-right">Data de Início</label>
                            <div class="col-md-6">
                                <input id="data_inicio" type="date" class="form-control @error('data_inicio') is-invalid @enderror" name="data_inicio" value="{{ old('data_inicio') }}">
                                @error('data_inicio')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="data_fim" class="col-md-4 col-form-label text-md-right">Data de Término</label>
                            <div class="col-md-6">
                                <input id="data_fim" type="date" class="form-control @error('data_fim') is-invalid @enderror" name="data_fim" value="{{ old('data_fim') }}">
                                @error('data_fim')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Cadastrar
                                </button>
                                <a href="{{ route('minicursos.index') }}" class="btn btn-secondary">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
