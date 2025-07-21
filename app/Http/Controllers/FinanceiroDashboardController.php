<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FinanceiroDashboardController extends Controller
{
    /**
     * Exibe o dashboard do módulo financeiro.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Retorna a view do dashboard financeiro
        return view('dashboards.financeiro.index');
    }
}
