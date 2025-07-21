@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Verificar Autenticidade do Certificado</div>

                <div class="card-body">
                    <form method="GET" action="{{ route('certificados.verificar') }}">
                        <div class="form-group row mb-3">
                            <label for="codigo" class="col-md-4 col-form-label text-md-right">Código de Autenticação</label>
                            <div class="col-md-6">
                                <input id="codigo" type="text" class="form-control" name="codigo" value="{{ request('codigo') }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Verificar
                                </button>
                            </div>
                        </div>
                    </form>

                    @if(isset($mensagem))
                        <div class="mt-4">
                            <div class="alert alert-{{ $valido ? 'success' : 'danger' }}">
                                {{ $mensagem }}
                            </div>

                            @if($valido && isset($participante) && isset($minicurso))
                                <div class="card mt-3">
                                    <div class="card-header">Informações do Certificado</div>
                                    <div class="card-body">
                                        <p><strong>Nome do Participante:</strong> {{ $participante->nome_participante }}</p>
                                        <p><strong>Minicurso:</strong> {{ $minicurso->nome }}</p>
                                        <p><strong>Professor Responsável:</strong> {{ $minicurso->professor_responsavel }}</p>
                                        <p><strong>Carga Horária:</strong> {{ $minicurso->carga_horaria }} horas</p>
                                        <p><strong>Data de Conclusão:</strong> {{ \Carbon\Carbon::parse($participante->data_conclusao)->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
