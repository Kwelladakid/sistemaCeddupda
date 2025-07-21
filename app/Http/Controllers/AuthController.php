<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Exibe o formulário de login.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Exibe o formulário de registro.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Lida com o registro de um novo usuário.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'cpf' => ['required', 'string', 'unique:users,cpf'], // Valida o CPF
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'cpf' => $request->cpf, // Salva o CPF
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user); // Loga o usuário automaticamente após o registro

        return redirect()->intended('/dashboard'); // Redireciona para o dashboard
    }

    /**
     * Lida com a tentativa de login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        // Valida o CPF e a senha
        $credentials = $request->validate([
            'cpf' => ['required', 'string', 'exists:users,cpf'], // Valida o CPF
            'password' => ['required'],
        ]);

        // Tenta autenticar o usuário com CPF e senha
        // CORREÇÃO AQUI: Ajuste na sintaxe do array de credenciais
        if (Auth::attempt(['cpf' => $credentials['cpf'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirecionar com base no papel do usuário
            $user = Auth::user();

            switch ($user->role) {
                case 'administrador':
                    return redirect()->route('dashboard.administrador');
                case 'aluno':
                    return redirect()->route('dashboard.aluno');
                case 'professor':
                    return redirect()->route('dashboard.professor');
                case 'financeiro':
                    return redirect()->route('dashboard.financeiro');
                default:
                    return redirect()->route('dashboard');
            }
        }

        // Lança uma exceção se as credenciais forem inválidas
        throw ValidationException::withMessages([
            'cpf' => 'As credenciais fornecidas não correspondem aos nossos registros.',
        ]);
    }

    /**
     * Lida com o logout do usuário.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/'); // Redireciona para a página inicial após o logout
    }
}
