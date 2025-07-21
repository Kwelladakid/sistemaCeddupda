<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // Certifique-se de que esta linha está presente

class AlunoController extends Controller
{
    /**
     * Exibe a lista de alunos.
     */
    public function index()
    {
        $this->authorize('viewAny', Aluno::class);

        $alunos = Aluno::all();

        return view('alunos.index', compact('alunos'));
    }

    /**
     * Mostra o formulário de criação de aluno.
     */
    public function create()
    {
        $this->authorize('create', Aluno::class);

        return view('alunos.create');
    }

    /**
     * Armazena um novo aluno sem criar o login automaticamente.
     */
    public function store(Request $request)
    {
        // Autoriza a ação 'create' na AlunoPolicy
        $this->authorize('create', Aluno::class);

        // Validação dos dados
        $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:alunos,cpf',
            'data_nascimento' => 'required|date',
            'endereco' => 'required|string|max:255',
            'telefone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:alunos,email',
            'status' => 'required|string|in:ativo,inativo,trancado,formado',
        ]);

        // Criação do aluno
        Aluno::create([
            'nome' => $request->nome,
            'cpf' => $request->cpf,
            'data_nascimento' => $request->data_nascimento,
            'endereco' => $request->endereco,
            'telefone' => $request->telefone,
            'email' => $request->email,
            'status' => $request->status,
        ]);

        // Redireciona com mensagem de sucesso
        return redirect()->route('alunos.index')->with('success', 'Aluno cadastrado com sucesso!');
    }

    /**
     * Exibe os detalhes de um aluno.
     */
    public function show(Aluno $aluno)
    {
        $this->authorize('view', $aluno);

        return view('alunos.show', compact('aluno'));
    }

    /**
     * Mostra o formulário de edição de aluno.
     */
    public function edit(Aluno $aluno)
    {
        $this->authorize('update', $aluno);

        return view('alunos.edit', compact('aluno'));
    }

    /**
     * Atualiza os dados de um aluno.
     */
    public function update(Request $request, Aluno $aluno)
    {
        $this->authorize('update', $aluno);

        // Validação dos dados
        $this->validateAluno($request, $aluno->id);

        // Atualização do aluno
        $aluno->update($request->all());

        return redirect()->route('alunos.index')->with('success', 'Aluno atualizado com sucesso!');
    }

    /**
     * Remove um aluno.
     */
    public function destroy(Aluno $aluno)
    {
        $this->authorize('delete', $aluno);

        $aluno->delete();

        return redirect()->route('alunos.index')->with('success', 'Aluno removido com sucesso!');
    }

    /**
     * Confirma a matrícula de um aluno e cria o login automaticamente.
     */
    public function confirmarMatricula($id)
    {
        // Busca o aluno pelo ID
        $aluno = Aluno::findOrFail($id);

        // Verifica se o aluno já possui um login
        if ($aluno->user) {
            return redirect()->back()->with('info', 'O aluno já possui login no sistema.');
        }

        // Criação do login para o aluno
        $user = User::create([
            'name' => $aluno->nome,
            'email' => $aluno->email, // O e-mail do aluno será usado como login
            'cpf' => $aluno->cpf,     // Adicionando o CPF do aluno ao usuário
            'password' => Hash::make($aluno->cpf), // O CPF será usado como senha padrão
            'role' => 'aluno', // Define o papel do usuário como 'aluno'
        ]);

        // Vincula o usuário ao aluno
        $aluno->user_id = $user->id;
        $aluno->status = 'ativo'; // Atualiza o status do aluno para 'ativo'
        $aluno->save();

        return redirect()->route('alunos.index')->with('success', 'Matrícula confirmada e login criado com sucesso!');
    }

    /**
     * Validação dos dados do aluno.
     */
    private function validateAluno(Request $request, $alunoId = null)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:alunos,cpf,' . $alunoId,
            'data_nascimento' => 'required|date',
            'endereco' => 'required|string|max:255',
            'telefone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:alunos,email,' . $alunoId,
            'status' => 'required|string|in:ativo,inativo,trancado,formado',
        ]);
    }

    /**
     * Exibe o perfil do aluno logado com suas notas e histórico financeiro.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function meuPerfil()
    {
        // Obtém o usuário logado
        $user = Auth::user();

        // Verifica se o usuário logado é um aluno
        if ($user->role !== 'aluno') {
            // Redireciona para o dashboard padrão ou exibe um erro
            return redirect()->route('dashboard')->with('error', 'Acesso não autorizado ao perfil de aluno.');
        }

        // Busca o registro do aluno associado ao usuário logado
        // Eager load as notas (com as disciplinas) e os pagamentos
        $aluno = Aluno::where('user_id', $user->id)
                      ->with(['notas.disciplina', 'pagamentos']) // Carrega notas e pagamentos
                      ->first();

        // Se por algum motivo o aluno não for encontrado (embora não deva acontecer se o user_id estiver correto)
        if (!$aluno) {
            return redirect()->route('dashboard')->with('error', 'Seu perfil de aluno não foi encontrado.');
        }

        // Autoriza a visualização do próprio perfil do aluno
        // Isso usará o método `view` da AlunoPolicy que acabamos de ajustar.
        $this->authorize('view', $aluno);

        // Retorna a view com os dados do aluno, notas e pagamentos
        return view('aluno.index', compact('aluno'));
    }
}
