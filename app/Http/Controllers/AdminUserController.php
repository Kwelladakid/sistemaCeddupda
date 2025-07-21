<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Certifique-se de que o modelo User está importado
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    /**
     * Exibe o formulário para criar um novo usuário.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('usuarios.create'); // Aponta para resources/views/usuarios/create.blade.php
    }

    /**
     * Armazena um novo usuário no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:administrador,secretaria,professor,aluno'], // Valida o papel
        ]);

        // Criação do usuário
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Redireciona com mensagem de sucesso
        return redirect()->route('usuarios.create')->with('success', 'Usuário criado com sucesso!');
    }
}
