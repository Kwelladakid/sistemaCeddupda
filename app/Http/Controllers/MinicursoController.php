<?php

namespace App\Http\Controllers;

use App\Models\Minicurso;
use App\Models\MinicursoParticipante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class MinicursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $minicursos = Minicurso::orderBy('created_at', 'desc')->get();
        return view('minicursos.index', compact('minicursos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('minicursos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'carga_horaria' => 'required|integer|min:1',
            'professor_responsavel' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_inicio' => 'nullable|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $minicurso = Minicurso::create($request->all());

        return redirect()->route('minicursos.show', $minicurso)
            ->with('success', 'Minicurso criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Minicurso $minicurso)
    {
        $participantes = $minicurso->participantes;
        return view('minicursos.show', compact('minicurso', 'participantes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Minicurso $minicurso)
    {
        return view('minicursos.edit', compact('minicurso'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Minicurso $minicurso)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'carga_horaria' => 'required|integer|min:1',
            'professor_responsavel' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_inicio' => 'nullable|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $minicurso->update($request->all());

        return redirect()->route('minicursos.show', $minicurso)
            ->with('success', 'Minicurso atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Minicurso $minicurso)
    {
        $minicurso->delete();

        return redirect()->route('minicursos.index')
            ->with('success', 'Minicurso excluído com sucesso!');
    }

    /**
     * Show the form for adding a participant to the minicurso.
     */
    public function addParticipanteForm(Minicurso $minicurso)
    {
        return view('minicursos.add_participante', compact('minicurso'));
    }

    /**
     * Store a newly created participant in storage.
     */
    public function storeParticipante(Request $request, Minicurso $minicurso)
    {
        $validator = Validator::make($request->all(), [
            'nome_participante' => 'required|string|max:255',
            'cpf_participante' => 'nullable|string|max:14',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $minicurso->participantes()->create([
            'nome_participante' => $request->nome_participante,
            'cpf_participante' => $request->cpf_participante,
            'data_conclusao' => now(), // Por padrão, consideramos que o participante concluiu o minicurso ao ser adicionado
        ]);

        return redirect()->route('minicursos.show', $minicurso)
            ->with('success', 'Participante adicionado com sucesso!');
    }

    /**
     * Generate a certificate for a participant.
     */
    public function gerarCertificado(Minicurso $minicurso, MinicursoParticipante $participante)
    {
        // Gerar código de autenticação se ainda não existir
        if (!$participante->codigo_autenticacao) {
            $participante->codigo_autenticacao = Str::uuid();
            $participante->save();
        }

        $data = [
            'minicurso' => $minicurso,
            'participante' => $participante,
            'data_emissao' => now()->format('d/m/Y'),
        ];

        $pdf = PDF::loadView('minicursos.certificado', $data);

        return $pdf->download('certificado_' . Str::slug($participante->nome_participante) . '.pdf');
    }

    public function gerarCertificadoVerso($minicursoId, $participanteId)
{
    $minicurso = Minicurso::findOrFail($minicursoId);
    $participante = MinicursoParticipante::findOrFail($participanteId);

    // Verificar se o participante pertence ao minicurso
    if ($participante->minicurso_id != $minicurso->id) {
        abort(404);
    }

    $data = [
        'minicurso' => $minicurso,
        'participante' => $participante,
        'data_emissao' => now()->format('d/m/Y'),
    ];

    // Carregar APENAS a view do verso do certificado
    $pdf = PDF::loadView('minicursos.certificado_verso', $data);

    // Configurar para paisagem e sem margens
    $pdf->setPaper('a4', 'landscape');
    $pdf->setOptions(['dpi' => 300, 'defaultFont' => 'sans-serif']);

    return $pdf->download('certificado_verso_' . Str::slug($participante->nome_participante) . '.pdf');
}

    /**
     * Verify a certificate by its authentication code.
     */
    public function verificarCertificado(Request $request)
    {
        $codigo = $request->codigo;

        if (!$codigo) {
            return view('minicursos.verificar');
        }

        $participante = MinicursoParticipante::where('codigo_autenticacao', $codigo)->first();

        if (!$participante) {
            return view('minicursos.verificar', ['mensagem' => 'Certificado não encontrado.', 'valido' => false]);
        }

        $minicurso = $participante->minicurso;

        return view('minicursos.verificar', [
            'mensagem' => 'Certificado válido!',
            'valido' => true,
            'participante' => $participante,
            'minicurso' => $minicurso,
        ]);
    }
}
