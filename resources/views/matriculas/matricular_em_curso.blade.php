{{-- resources/views/matriculas/matricular_em_curso.blade.php --}}
@extends('layouts.app')

@section('title', 'Matricular Aluno')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Matricular Aluno no Curso</h1>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('cursos.index') }}">Cursos</a></li>
        <li class="breadcrumb-item"><a href="{{ route('cursos.show', $curso->id) }}">{{ $curso->nome }}</a></li>
        <li class="breadcrumb-item active">Matricular Aluno</li>
    </ol>

    <div class="row">
        <div class="col-xl-12">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-graduation-cap me-1"></i>
                    Curso: {{ $curso->nome }}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p><strong>Descrição:</strong> {{ $curso->descricao }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Carga Horária:</strong> {{ $curso->carga_horaria }} horas</p>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-1"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user-plus me-1"></i>
                    Formulário de Matrícula
                </div>
                <div class="card-body">
                    <form action="{{ route('cursos.processar-matricula', $curso->id) }}" method="POST" id="matriculaForm">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="aluno_id" class="form-label">Selecione o Aluno:</label>
                                <select name="aluno_id" id="aluno_id" class="form-select @error('aluno_id') is-invalid @enderror" required>
                                    <option value="">Selecione um aluno</option>
                                    {{-- AQUI: Substituído $alunos por $alunosDisponiveis --}}
                                    @foreach($alunosDisponiveis as $aluno)
                                        <option value="{{ $aluno->id }}"
                                            {{ old('aluno_id') == $aluno->id ? 'selected' : '' }}
                                            data-tem-matricula="{{ $aluno->matriculas && $aluno->matriculas->count() > 0 ? 'sim' : 'nao' }}"
                                            data-turma-atual="{{ $aluno->matriculas && $aluno->matriculas->count() > 0 ? $aluno->matriculas->first()->turma_id : '' }}"
                                            data-status-matricula="{{ $aluno->matriculas && $aluno->matriculas->count() > 0 ? $aluno->matriculas->first()->status : '' }}">
                                            {{ $aluno->nome }} (CPF: {{ $aluno->cpf }})
                                            @if($aluno->matriculas && $aluno->matriculas->where('status', 'ativa')->count() > 0)
                                                - <span class="text-danger">Já matriculado</span>
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('aluno_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="alunoMatriculaInfo" class="form-text text-danger mt-2" style="display: none;">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span id="alunoMatriculaMessage"></span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="turma_id" class="form-label">Selecione a Turma:</label>
                                <select name="turma_id" id="turma_id" class="form-select @error('turma_id') is-invalid @enderror" required>
                                    <option value="">Selecione uma turma</option>
                                    {{-- AQUI: Substituído $turmas por $turmasDoCurso --}}
                                    @foreach($turmasDoCurso as $turma)
                                        <option value="{{ $turma->id }}"
                                            {{ old('turma_id') == $turma->id ? 'selected' : '' }}
                                            data-vagas="{{ $turma->vagas_disponiveis ?? 'ilimitado' }}"
                                            data-periodo="{{ $turma->periodo }}"
                                            data-ano="{{ $turma->ano }}">
                                            {{ $turma->nome }} ({{ $turma->ano }} - {{ $turma->periodo ?? 'Não definido' }})
                                            @if(isset($turma->vagas_disponiveis) && $turma->vagas_disponiveis <= 0)
                                                - <span class="text-danger">Sem vagas</span>
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('turma_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="turmaInfo" class="form-text mt-2">
                                    <span id="vagasInfo"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="data_matricula" class="form-label">Data da Matrícula:</label>
                                <input type="date" name="data_matricula" id="data_matricula"
                                       class="form-control @error('data_matricula') is-invalid @enderror"
                                       value="{{ old('data_matricula', date('Y-m-d')) }}" required>
                                @error('data_matricula')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="status" class="form-label">Status da Matrícula:</label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="ativa" {{ old('status') == 'ativa' ? 'selected' : '' }}>Ativa</option>
                                    <option value="trancada" {{ old('status') == 'trancada' ? 'selected' : '' }}>Trancada</option>
                                    <option value="cancelada" {{ old('status') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                    <option value="concluida" {{ old('status') == 'concluida' ? 'selected' : '' }}>Concluída</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="observacoes" class="form-label">Observações:</label>
                                <textarea name="observacoes" id="observacoes" rows="3"
                                          class="form-control @error('observacoes') is-invalid @enderror">{{ old('observacoes') }}</textarea>
                                @error('observacoes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-success" id="btnMatricular">
                                <i class="fas fa-user-plus me-1"></i> Matricular Aluno
                            </button>
                            <a href="{{ route('cursos.show', $curso->id) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-user-graduate me-1"></i>
                    Cadastrar Novo Aluno e Matricular
                </div>
                <div class="card-body">
                    <p>Se o aluno ainda não está cadastrado no sistema, você pode cadastrá-lo e matriculá-lo diretamente.</p>
                    <a href="{{ route('cursos.novo-aluno', $curso->id) }}" class="btn btn-info">
                        <i class="fas fa-plus-circle me-1"></i> Cadastrar Novo Aluno e Matricular
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Função para verificar se o aluno já está matriculado
        function verificarMatriculaExistente() {
            const alunoSelecionado = $('#aluno_id option:selected');
            const turmaId = $('#turma_id').val();

            if (alunoSelecionado.val()) {
                const temMatricula = alunoSelecionado.data('tem-matricula');
                const turmaAtual = alunoSelecionado.data('turma-atual');
                const statusMatricula = alunoSelecionado.data('status-matricula');

                // Limpa mensagens anteriores
                $('#alunoMatriculaInfo').hide();
                $('#alunoMatriculaMessage').text('');
                $('#btnMatricular').prop('disabled', false);

                // Verifica se o aluno já tem matrícula ativa
                if (temMatricula === 'sim' && statusMatricula === 'ativa') {
                    // Se o aluno já está matriculado na mesma turma
                    if (turmaAtual == turmaId && turmaId !== '') {
                        $('#alunoMatriculaMessage').text('Este aluno já está matriculado nesta turma.');
                        $('#alunoMatriculaInfo').show();
                        $('#btnMatricular').prop('disabled', true);
                    }
                    // Se o aluno está matriculado em outra turma
                    else if (turmaId !== '') {
                        $('#alunoMatriculaMessage').text('Este aluno já está matriculado em outra turma. Cancele a matrícula atual antes de matriculá-lo em uma nova turma.');
                        $('#alunoMatriculaInfo').show();
                        $('#btnMatricular').prop('disabled', true);
                    }
                }
            }
        }

        // Função para mostrar informações da turma
        function mostrarInfoTurma() {
            const turmaSelecionada = $('#turma_id option:selected');

            if (turmaSelecionada.val()) {
                const vagas = turmaSelecionada.data('vagas');
                const periodo = turmaSelecionada.data('periodo');
                const ano = turmaSelecionada.data('ano');

                let infoHtml = '';

                if (vagas !== 'ilimitado') {
                    if (vagas <= 0) {
                        infoHtml += '<span class="text-danger"><i class="fas fa-exclamation-circle"></i> Turma sem vagas disponíveis!</span>';
                        $('#btnMatricular').prop('disabled', true);
                    } else {
                        infoHtml += `<span class="text-success"><i class="fas fa-info-circle"></i> Vagas disponíveis: ${vagas}</span>`;
                    }
                }

                $('#vagasInfo').html(infoHtml);
                $('#turmaInfo').show();
            } else {
                $('#turmaInfo').hide();
            }

            // Verifica matrícula existente após selecionar turma
            verificarMatriculaExistente();
        }

        // Eventos para os selects
        $('#aluno_id').change(verificarMatriculaExistente);
        $('#turma_id').change(mostrarInfoTurma);

        // Verificação inicial
        verificarMatriculaExistente();
        mostrarInfoTurma();

        // Validação do formulário antes do envio
        $('#matriculaForm').on('submit', function(e) {
            const alunoId = $('#aluno_id').val();
            const turmaId = $('#turma_id').val();

            if (!alunoId || !turmaId) {
                e.preventDefault();
                alert('Por favor, selecione um aluno e uma turma.');
                return false;
            }

            // Verifica se o botão está desabilitado (indicando validação falhou)
            if ($('#btnMatricular').prop('disabled')) {
                e.preventDefault();
                alert('Não é possível realizar esta matrícula. Verifique as informações e tente novamente.');
                return false;
            }

            return true;
        });
    });
</script>
@endsection
