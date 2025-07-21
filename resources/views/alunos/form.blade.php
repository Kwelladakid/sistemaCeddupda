{{-- Este arquivo contém o formulário para criação e edição de alunos --}}
@extends('layouts.app')
{{-- Campo Nome --}}
<div style="margin-bottom: 15px;">
    <label for="nome" style="display: block; margin-bottom: 5px; font-weight: bold;">Nome:</label>
    <input type="text" id="nome" name="nome" value="{{ old('nome', $aluno->nome ?? '') }}" required
           style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
    @error('nome')
        <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
    @enderror
</div>

{{-- Campo CPF --}}
<div style="margin-bottom: 15px;">
    <label for="cpf" style="display: block; margin-bottom: 5px; font-weight: bold;">CPF:</label>
    <input type="text" id="cpf" name="cpf" value="{{ old('cpf', $aluno->cpf ?? '') }}" required
           style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
    @error('cpf')
        <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
    @enderror
</div>

{{-- Campo Data de Nascimento --}}
<div style="margin-bottom: 15px;">
    <label for="data_nascimento" style="display: block; margin-bottom: 5px; font-weight: bold;">Data de Nascimento:</label>
    <input type="date" id="data_nascimento" name="data_nascimento" value="{{ old('data_nascimento', $aluno->data_nascimento ?? '') }}" required
           style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
    @error('data_nascimento')
        <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
    @enderror
</div>

{{-- Campo Endereço --}}
<div style="margin-bottom: 15px;">
    <label for="endereco" style="display: block; margin-bottom: 5px; font-weight: bold;">Endereço:</label>
    <input type="text" id="endereco" name="endereco" value="{{ old('endereco', $aluno->endereco ?? '') }}" required
           style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
    @error('endereco')
        <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
    @enderror
</div>

{{-- Campo Telefone --}}
<div style="margin-bottom: 15px;">
    <label for="telefone" style="display: block; margin-bottom: 5px; font-weight: bold;">Telefone:</label>
    <input type="text" id="telefone" name="telefone" value="{{ old('telefone', $aluno->telefone ?? '') }}" required
           style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
    @error('telefone')
        <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
    @enderror
</div>

{{-- Campo Email --}}
<div style="margin-bottom: 15px;">
    <label for="email" style="display: block; margin-bottom: 5px; font-weight: bold;">Email:</label>
    <input type="email" id="email" name="email" value="{{ old('email', $aluno->email ?? '') }}" required
           style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
    @error('email')
        <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
    @enderror
</div>

{{-- Campo Status --}}
<div style="margin-bottom: 15px;">
    <label for="status" style="display: block; margin-bottom: 5px; font-weight: bold;">Status:</label>
    <select id="status" name="status" required
            style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
        <option value="ativo" {{ old('status', $aluno->status ?? '') == 'ativo' ? 'selected' : '' }}>Ativo</option>
        <option value="inativo" {{ old('status', $aluno->status ?? '') == 'inativo' ? 'selected' : '' }}>Inativo</option>
        <option value="trancado" {{ old('status', $aluno->status ?? '') == 'trancado' ? 'selected' : '' }}>Trancado</option>
        <option value="formado" {{ old('status', $aluno->status ?? '') == 'formado' ? 'selected' : '' }}>Formado</option>
    </select>
    @error('status')
        <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
    @enderror
</div>

{{-- Botão de Envio --}}
<button type="submit" style="background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 1em;">
    Salvar Aluno
</button>
<a href="{{ route('alunos.index') }}" style="background-color: #6c757d; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 1em; text-decoration: none; margin-left: 10px;">
    Cancelar
</a>
