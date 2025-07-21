<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Minicurso extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'carga_horaria',
        'professor_responsavel',
        'descricao',
        'data_inicio',
        'data_fim',
    ];

    /**
     * Get the participantes for the Minicurso.
     */
    public function participantes(): HasMany
    {
        return $this->hasMany(MinicursoParticipante::class);
    }
}
