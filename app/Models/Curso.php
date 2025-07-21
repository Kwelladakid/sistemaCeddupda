<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'descricao', 'carga_horaria'];

    /**
     * Obtém as turmas associadas a este curso.
     */
    public function turmas()
    {
        return $this->hasMany(Turma::class);
    }


    /**
     * Obtém as disciplinas associadas a este curso.
     */
    public function disciplinas()
    {
        return $this->hasMany(Disciplina::class);
    }

    public function alunos()
    {
        // Assumindo que você tem uma tabela pivot 'aluno_curso'
        return $this->belongsToMany(Aluno::class, 'aluno_curso');
    }
}

