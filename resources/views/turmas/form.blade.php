<!-- resources/views/turmas/form.blade.php -->
@csrf

<div class="form-group mb-3">
    <label for="nome">Nome da Turma:</label>
    <input type="text" name="nome" id="nome" class="form-control" value="{{ old('nome', $turma->nome ?? '') }}" required>
</div>

<div class="form-group mb-3">
    <label for="curso_id">Curso:</label>
    <select name="curso_id" id="curso_id" class="form-control" required>
        <option value="">Selecione um curso</option>
        @foreach($cursos as $curso)
            <option value="{{ $curso->id }}" {{ (old('curso_id', $turma->curso_id ?? '') == $curso->id) ? 'selected' : '' }}>
                {{ $curso->nome }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group mb-3">
    <label for="ano">Ano:</label>
    <input type="number" name="ano" id="ano" class="form-control" value="{{ old('ano', $turma->ano ?? date('Y')) }}" required>
</div>

<div class="form-group mb-3">
    <label for="periodo">Período:</label>
    <select name="periodo" id="periodo" class="form-control">
        <option value="">Selecione um período</option>
        <option value="Matutino" {{ (old('periodo', $turma->periodo ?? '') == 'Matutino') ? 'selected' : '' }}>Matutino</option>
        <option value="Vespertino" {{ (old('periodo', $turma->periodo ?? '') == 'Vespertino') ? 'selected' : '' }}>Vespertino</option>
        <option value="Noturno" {{ (old('periodo', $turma->periodo ?? '') == 'Noturno') ? 'selected' : '' }}>Noturno</option>
        <option value="Integral" {{ (old('periodo', $turma->periodo ?? '') == 'Integral') ? 'selected' : '' }}>Integral</option>
    </select>
</div>

<div class="form-group mb-3">
    <label for="status">Status:</label>
    <select name="status" id="status" class="form-control">
        <option value="ativa" {{ (old('status', $turma->status ?? 'ativa') == 'ativa') ? 'selected' : '' }}>Ativa</option>
        <option value="inativa" {{ (old('status', $turma->status ?? '') == 'inativa') ? 'selected' : '' }}>Inativa</option>
        <option value="concluida" {{ (old('status', $turma->status ?? '') == 'concluida') ? 'selected' : '' }}>Concluída</option>
        <option value="cancelada" {{ (old('status', $turma->status ?? '') == 'cancelada') ? 'selected' : '' }}>Cancelada</option>
    </select>
</div>

<div class="mt-3">
    <button type="submit" class="btn btn-success">Salvar</button>
    <a href="{{ route('turmas.index') }}" class="btn btn-secondary">Cancelar</a>
</div>
