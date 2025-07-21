<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Exibe o dashboard do administrador.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Retorna a view do dashboard do administrador
        return view('administrador.index');
    }
}
