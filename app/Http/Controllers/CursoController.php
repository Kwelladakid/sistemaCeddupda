<?php

namespace App\Http\Controllers;

use App\Models\Curso; // [ADICIONADO] Importa o model Curso
use Illuminate\Http\Request;

class CursoController extends Controller
{
    /**
     * Exibe a lista de cursos.
     */
    public function index()
    {
        $cursos = Curso::all(); // [ADICIONADO] Busca todos os cursos
        return view('cursos.index', compact('cursos')); // [ADICIONADO] Retorna a view com os cursos
    }

    /**
     * Mostra o formulário de criação de um novo curso.
     */
    public function create()
    {
        return view('cursos.create'); // [ADICIONADO] Retorna a view de criação
    }

    /**
     * Salva um novo curso no banco de dados.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'carga_horaria' => 'required|integer|min:1'
        ]); // [ADICIONADO] Validação básica

        Curso::create($request->all()); // [ADICIONADO] Cria o curso
        return redirect()->route('cursos.index')->with('success', 'Curso cadastrado com sucesso!');
    }

    /**
     * Mostra o formulário de edição de um curso.
     */
    public function edit(Curso $curso)
    {
        return view('cursos.edit', compact('curso')); // [ADICIONADO] Retorna a view de edição
    }

    /**
     * Atualiza um curso no banco de dados.
     */
    public function update(Request $request, Curso $curso)
    {
        $request->validate([
            'nome' => 'required',
            'carga_horaria' => 'required|integer|min:1'
        ]); // [ADICIONADO] Validação básica

        $curso->update($request->all()); // [ADICIONADO] Atualiza o curso
        return redirect()->route('cursos.index')->with('success', 'Curso atualizado com sucesso!');
    }

    public function show(Curso $curso)
    {
        // Carrega as turmas relacionadas a este curso
        $curso->load('turmas.professor');

        return view('cursos.show', compact('curso'));
    }

    /**
     * Remove um curso do banco de dados.
     */
    public function destroy(Curso $curso)
    {
        $curso->delete(); // [ADICIONADO] Remove o curso
        return redirect()->route('cursos.index')->with('success', 'Curso removido com sucesso!');
    }
}
