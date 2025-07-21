<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Certifique-se de que esta linha está presente
use Symfony\Component\HttpFoundation\Response;

class FinanceiroAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se o usuário está autenticado no guard 'financeiro'
        if (Auth::guard('financeiro')->check()) {
            return $next($request);
        }

        // Verifica se o usuário está autenticado no guard 'web' E tem a role 'administrador'
        if (Auth::guard('web')->check() && Auth::guard('web')->user()->role === 'administrador') {
            return $next($request);
        }

        // Se nenhuma das condições for atendida, redireciona para o login do financeiro
        return redirect()->route('financeiro.login');
    }
}
