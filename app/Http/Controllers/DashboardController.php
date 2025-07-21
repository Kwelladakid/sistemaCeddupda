<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Exibe a página principal do dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Futuramente, você pode adicionar lógica aqui para buscar dados
        // para o dashboard, como estatísticas, gráficos, etc.
        return view('dashboard');
    }
}
