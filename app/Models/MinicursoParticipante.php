<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MinicursoParticipante extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'minicurso_participantes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'minicurso_id',
        'nome_participante',
        'cpf_participante',
        'codigo_autenticacao',
        'data_conclusao',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data_conclusao' => 'datetime',
    ];

    /**
     * Get the minicurso that owns the participante.
     */
    public function minicurso(): BelongsTo
    {
        return $this->belongsTo(Minicurso::class);
    }
}
