<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'carga_horaria',
        'modulo',
        'curso_id',
        'professor_id', // Adicionado: Campo para vincular o professor
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function notas()
    {
        return $this->hasMany(Nota::class);
    }

    /**
     * Define o relacionamento com o Professor.
     */
    public function professor() // NOVO: Este método define o relacionamento!
    {
        return $this->belongsTo(Professor::class);
    }
}
