@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Detalhes do Minicurso</span>
                    <div>
                        <a href="{{ route('minicursos.edit', $minicurso) }}" class="btn btn-warning btn-sm">Editar</a>
                        <a href="{{ route('minicursos.index') }}" class="btn btn-secondary btn-sm">Voltar</a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h4>{{ $minicurso->nome }}</h4>
                            <p><strong>Professor Responsável:</strong> {{ $minicurso->professor_responsavel }}</p>
                            <p><strong>Carga Horária:</strong> {{ $minicurso->carga_horaria }} horas</p>

                            @if($minicurso->data_inicio)
                                <p><strong>Data de Início:</strong> {{ \Carbon\Carbon::parse($minicurso->data_inicio)->format('d/m/Y') }}</p>
                            @endif

                            @if($minicurso->data_fim)
                                <p><strong>Data de Término:</strong> {{ \Carbon\Carbon::parse($minicurso->data_fim)->format('d/m/Y') }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h5>Descrição</h5>
                            <p>{{ $minicurso->descricao ?? 'Nenhuma descrição disponível.' }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Participantes</h5>
                        <a href="{{ route('minicursos.participantes.adicionar', $minicurso) }}" class="btn btn-primary btn-sm">Adicionar Participante</a>
                    </div>

                    @if (count($participantes) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>CPF</th>
                                        <th>Data de Conclusão</th>
                                        <th>Certificado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($participantes as $participante)
                                        <tr>
                                            <td>{{ $participante->nome_participante }}</td>
                                            <td>{{ $participante->cpf_participante ?? 'Não informado' }}</td>
                                            <td>
                                                @if($participante->data_conclusao)
                                                    {{ \Carbon\Carbon::parse($participante->data_conclusao)->format('d/m/Y') }}
                                                @else
                                                    Não concluído
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('minicursos.certificado.gerar', ['minicurso' => $minicurso->id, 'participante' => $participante->id]) }}"
                                                       class="btn btn-success btn-sm" title="Gerar frente do certificado">
                                                        <i class="bi bi-file-earmark-pdf"></i> Frente
                                                    </a>
                                                    <a href="{{ route('minicursos.certificado.verso', ['minicurso' => $minicurso->id, 'participante' => $participante->id]) }}"
                                                       class="btn btn-info btn-sm" title="Gerar verso do certificado">
                                                        <i class="bi bi-file-earmark-text"></i> Verso
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center">Nenhum participante cadastrado para este minicurso.</p>
                        <div class="text-center">
                            <a href="{{ route('minicursos.participantes.adicionar', $minicurso) }}" class="btn btn-primary">Adicionar Participante</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
