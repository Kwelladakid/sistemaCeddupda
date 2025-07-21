<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanceiroAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('financeiro.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('financeiro')->attempt($credentials)) {
            return redirect()->route('financeiro.index');
        }

        return back()->withErrors(['email' => 'Credenciais inválidas.']);
    }

    public function logout()
    {
        Auth::guard('financeiro')->logout();
        return redirect()->route('financeiro.login');
    }
}
