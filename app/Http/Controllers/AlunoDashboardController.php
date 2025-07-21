<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlunoDashboardController extends Controller
{
    /**
     * Exibe o dashboard do aluno.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Retorna a view do dashboard do aluno
        return view('aluno.index');
    }
}
