@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Nova Matrícula</h1>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5>Matricular em Turma</h5>
                </div>
                <div class="card-body">
                    <p>Selecione uma turma para matricular um aluno existente ou criar um novo aluno.</p>

                    <form action="{{ route('turmas.alunos.adicionar', ['turma' => 0]) }}" method="GET" id="selectTurmaForm">
                        <div class="form-group">
                            <label for="turma_id">Selecione uma Turma:</label>
                            <select class="form-control" id="turma_id" name="turma_id" required>
                                <option value="">-- Selecione uma Turma --</option>
                                @foreach($turmas as $turma)
                                    <option value="{{ $turma->id }}">{{ $turma->nome }} ({{ $turma->curso->nome ?? 'Sem curso' }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-3">
                            <button type="button" class="btn btn-primary" onclick="redirectToTurma()">Continuar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5>Matricular em Curso</h5>
                </div>
                <div class="card-body">
                    <p>Selecione um curso para matricular um aluno.</p>

                    <form action="{{ route('cursos.matricular', ['curso' => 0]) }}" method="GET" id="selectCursoForm">
                        <div class="form-group">
                            <label for="curso_id">Selecione um Curso:</label>
                            <select class="form-control" id="curso_id" name="curso_id" required>
                                <option value="">-- Selecione um Curso --</option>
                                @foreach($cursos as $curso)
                                    <option value="{{ $curso->id }}">{{ $curso->nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-3">
                            <button type="button" class="btn btn-success" onclick="redirectToCurso()">Continuar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function redirectToTurma() {
    var turmaId = document.getElementById('turma_id').value;
    if (turmaId) {
        window.location.href = "{{ url('turmas') }}/" + turmaId + "/alunos/adicionar";
    } else {
        alert('Por favor, selecione uma turma');
    }
}

function redirectToCurso() {
    var cursoId = document.getElementById('curso_id').value;
    if (cursoId) {
        window.location.href = "{{ url('cursos') }}/" + cursoId + "/matricular";
    } else {
        alert('Por favor, selecione um curso');
    }
}
</script>
@endsection
