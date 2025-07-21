<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // Importar DB para transações

class UserController extends Controller
{
    /**
     * Exibe a lista de usuários.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Autoriza a ação 'viewAny' na UserPolicy.
        $this->authorize('viewAny', User::class);

        // Busca todos os usuários
        $usuarios = User::orderBy('name')->paginate(10);

        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Mostra o formulário para criar um novo usuário.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Autoriza a ação 'create' na UserPolicy.
        $this->authorize('create', User::class);

        return view('usuarios.create');
    }

    /**
     * Salva um novo usuário no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Autoriza a ação 'create' na UserPolicy.
        $this->authorize('create', User::class);

        // *** REMOVIDO: str_replace para CPF. O CPF será tratado com pontos e traços.

        // Validação dos dados
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'cpf' => 'required|string|max:14|unique:users,cpf', // CPF com pontos e traços
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:administrador,secretaria,professor,aluno',
        ]);

        // Inicia uma transação de banco de dados para garantir atomicidade
        DB::beginTransaction();

        try {
            // Cria o usuário
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'cpf' => $validatedData['cpf'], // Salva o CPF como recebido (com pontos e traços)
                'password' => Hash::make($validatedData['password']),
                'role' => $validatedData['role'],
            ]);

            DB::commit(); // Confirma a transação

            // Mensagem de sucesso aprimorada
            $successMessage = "Usuário '{$user->name}' ({$user->role}) criado com sucesso! ";
            $successMessage .= "Login: {$user->cpf}. A senha é a que foi definida no cadastro.";

            return redirect()->route('usuarios.index')
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack(); // Reverte a transação em caso de erro
            return redirect()->back()->withInput()->with('error', 'Erro ao criar usuário: ' . $e->getMessage());
        }
    }

    /**
     * Mostra o formulário para editar um usuário.
     *
     * @param  \App\Models\User  $usuario
     * @return \Illuminate\View\View
     */
    public function edit(User $usuario)
    {
        // Autoriza a ação 'update' na UserPolicy.
        $this->authorize('update', $usuario);

        return view('usuarios.edit', compact('usuario'));
    }

    /**
     * Atualiza um usuário no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $usuario
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $usuario)
    {
        // Autoriza a ação 'update' na UserPolicy.
        $this->authorize('update', $usuario);

        // *** REMOVIDO: str_replace para CPF. O CPF será tratado com pontos e traços.

        // Validação dos dados
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $usuario->id,
            'cpf' => 'required|string|max:14|unique:users,cpf,' . $usuario->id, // CPF com pontos e traços
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:administrador,secretaria,professor,aluno',
        ]);

        // Inicia uma transação de banco de dados para garantir atomicidade
        DB::beginTransaction();

        try {
            // Atualiza os dados do usuário
            $usuario->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'cpf' => $validatedData['cpf'], // Salva o CPF como recebido (com pontos e traços)
                'password' => $validatedData['password'] ? Hash::make($validatedData['password']) : $usuario->password,
                'role' => $validatedData['role'],
            ]);

            DB::commit(); // Confirma a transação

            // Mensagem de sucesso aprimorada
            $successMessage = "Usuário '{$usuario->name}' ({$usuario->role}) atualizado com sucesso! ";
            $successMessage .= "Login: {$usuario->cpf}."; // A senha pode não ter sido alterada

            return redirect()->route('usuarios.index')
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack(); // Reverte a transação em caso de erro
            return redirect()->back()->withInput()->with('error', 'Erro ao atualizar usuário: ' . $e->getMessage());
        }
    }

    /**
     * Remove um usuário do banco de dados.
     *
     * @param  \App\Models\User  $usuario
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $usuario)
    {
        // Autoriza a ação 'delete' na UserPolicy.
        $this->authorize('delete', $usuario);

        $usuario->delete();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuário removido com sucesso!');
    }
}
