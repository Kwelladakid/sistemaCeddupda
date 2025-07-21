@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Adicionar Participante ao Minicurso: {{ $minicurso->nome }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('minicursos.participantes.store', $minicurso) }}">
                        @csrf

                        <div class="form-group row mb-3">
                            <label for="nome_participante" class="col-md-4 col-form-label text-md-right">Nome do Participante</label>
                            <div class="col-md-6">
                                <input id="nome_participante" type="text" class="form-control @error('nome_participante') is-invalid @enderror" name="nome_participante" value="{{ old('nome_participante') }}" required autofocus>
                                @error('nome_participante')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="cpf_participante" class="col-md-4 col-form-label text-md-right">CPF do Participante</label>
                            <div class="col-md-6">
                                <input id="cpf_participante" type="text" class="form-control @error('cpf_participante') is-invalid @enderror" name="cpf_participante" value="{{ old('cpf_participante') }}" placeholder="000.000.000-00">
                                @error('cpf_participante')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Adicionar
                                </button>
                                <a href="{{ route('minicursos.show', $minicurso) }}" class="btn btn-secondary">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
