<?php

namespace App\Http\Controllers;

use App\Models\Professor;
use Illuminate\Http\Request;
use App\Http\Requests\ProfessorStoreRequest; // Mantenha se estiver usando
use App\Models\User;
use App\Models\Disciplina; // <--- ADICIONADO: Importe o modelo Disciplina
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfessorController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Professor::class);
        $professores = Professor::all();
        return view('professores.index', compact('professores'));
    }

    public function create()
    {
        $this->authorize('create', Professor::class);
        $disciplinas = Disciplina::all(); // <--- ADICIONADO: Carrega todas as disciplinas
        return view('professores.create', compact('disciplinas')); // <--- MODIFICADO: Passa as disciplinas para a view
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request // Ou ProfessorStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) // <--- MODIFICADO: Use Request ou ProfessorStoreRequest
    {
        // 1. Validação dos dados do formulário
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => [
                'required',
                'string',
                'max:14',
                Rule::unique('professores', 'cpf'),
                Rule::unique('users', 'cpf'),
            ],
            'especialidade' => 'required|string|max:255',
            'telefone' => 'required|string|max:20',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('professores', 'email'),
                Rule::unique('users', 'email'),
            ],
            'formacao' => 'nullable|string|max:255',
            'disciplinas' => 'nullable|array', // <--- ADICIONADO: Validação para o array de disciplinas
            'disciplinas.*' => 'exists:disciplinas,id', // <--- ADICIONADO: Garante que cada ID de disciplina exista
        ]);

        // 2. Criar o registro do USUÁRIO na tabela 'users'
        $user = User::create([
            'name' => $validatedData['nome'],
            'cpf' => $validatedData['cpf'],
            'email' => $validatedData['email'] ?? null,
            'password' => Hash::make($validatedData['cpf']),
            'role' => User::ROLE_TEACHER,
        ]);

        // 3. Criar o registro do PROFESSOR na tabela 'professores'
        $professor = new Professor($validatedData);
        $professor->user_id = $user->id;
        $professor->save();

        // 4. VINCULAR AS DISCIPLINAS AO PROFESSOR <--- ADICIONADO ESTE BLOCO
        if (isset($validatedData['disciplinas'])) {
            $professor->disciplinas()->attach($validatedData['disciplinas']);
        }

        // 5. Redirecionar com mensagem de sucesso
        return redirect()->route('professores.index')->with('success', 'Professor cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Professor  $professore
     * @return \Illuminate\Http\Response
     */
    public function show(Professor $professore)
    {
        $this->authorize('view', $professore);
        return view('professores.show', compact('professore'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Professor  $professore
     * @return \Illuminate\Http\Response
     */
    public function edit(Professor $professore)
    {
        $this->authorize('update', $professore);
        $disciplinas = Disciplina::all(); // <--- ADICIONADO: Carrega todas as disciplinas
        // <--- ADICIONADO: Carrega os IDs das disciplinas já vinculadas para pré-seleção
        $professorDisciplinasIds = $professore->disciplinas->pluck('id')->toArray();
        return view('professores.edit', compact('professore', 'disciplinas', 'professorDisciplinasIds')); // <--- MODIFICADO: Passa as disciplinas e os IDs vinculados
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Professor  $professore
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Professor $professore)
    {
        $this->authorize('update', $professore);

        $validatedData = $request->validate([
            'nome' => 'required',
            'cpf' => 'required|unique:professores,cpf,' . $professore->id,
            'especialidade' => 'required',
            'telefone' => 'required',
            'email' => 'required|email|unique:professores,email,' . $professore->id,
            'disciplinas' => 'nullable|array', // <--- ADICIONADO: Validação para o array de disciplinas
            'disciplinas.*' => 'exists:disciplinas,id', // <--- ADICIONADO: Garante que cada ID de disciplina exista
        ]);

        // Atualiza os dados básicos do professor
        $professore->update($validatedData);

        // SINCRONIZAR AS DISCIPLINAS DO PROFESSOR <--- ADICIONADO ESTE BLOCO
        // O método sync() adiciona, remove ou mantém as associações para que correspondam ao array fornecido.
        $professore->disciplinas()->sync($validatedData['disciplinas'] ?? []); // Passa um array vazio se 'disciplinas' não estiver presente

        return redirect()->route('professores.index')->with('success', 'Professor atualizado!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Professor  $professore
     * @return \Illuminate\Http\Response
     */
    public function destroy(Professor $professore)
    {
        $this->authorize('delete', $professore);
        // Se você não tiver onDelete('cascade') na migração da tabela pivô,
        // você precisaria desvincular as disciplinas manualmente aqui:
        // $professore->disciplinas()->detach();
        $professore->delete();
        return redirect()->route('professores.index')->with('success', 'Professor removido!');
    }
}
