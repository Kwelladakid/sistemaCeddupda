<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome', 'curso_id', 'professor_id', 'ano', 'periodo', 'status'
    ];

    /**
     * Obtém o curso ao qual esta turma pertence.
     */
    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    /**
     * Obtém o professor responsável por esta turma.
     */
    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }

    /**
     * Os alunos matriculados nesta turma.
     */
    public function alunos()
    {
        return $this->belongsToMany(Aluno::class, 'aluno_turma')
                    ->withPivot('data_matricula', 'status')
                    ->withTimestamps();
    }
}
