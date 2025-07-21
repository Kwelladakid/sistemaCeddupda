@csrf
@extends('layouts.app')
<div>
    <label for="nome">Nome da Disciplina</label>
    <input type="text" name="nome" id="nome" value="{{ old('nome', $disciplina->nome ?? '') }}" required>
</div>

<div>
    <label for="carga_horaria">Carga Horária (horas)</label>
    <input type="number" name="carga_horaria" id="carga_horaria" value="{{ old('carga_horaria', $disciplina->carga_horaria ?? '') }}" required>
</div>

<div>
    <label for="modulo">Módulo</label>
    <select name="modulo" id="modulo" required>
        <option value="">Selecione</option>
        @foreach([1,2,3] as $m)
            <option value="{{ $m }}" {{ old('modulo', $disciplina->modulo ?? '') == $m ? 'selected' : '' }}>Módulo {{ $m }}</option>
        @endforeach
    </select>
</div>

<div>
    <label for="curso_id">Curso</label>
    <select name="curso_id" id="curso_id" required>
        <option value="">Selecione</option>
        @foreach($cursos as $curso)
            <option value="{{ $curso->id }}" {{ old('curso_id', $disciplina->curso_id ?? '') == $curso->id ? 'selected' : '' }}>
                {{ $curso->nome }}
            </option>
        @endforeach
    </select>
</div>

{{-- NOVO CAMPO: Professor Responsável --}}
<div>
    <label for="professor_id">Professor Responsável</label>
    <select name="professor_id" id="professor_id" required>
        <option value="">Selecione um professor</option>
        @foreach($professores as $professor)
            <option value="{{ $professor->id }}" {{ old('professor_id', $disciplina->professor_id ?? '') == $professor->id ? 'selected' : '' }}>
                {{ $professor->nome }}
            </option>
        @endforeach
    </select>
</div>
