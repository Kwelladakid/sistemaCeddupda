<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    use HasFactory;

    protected $fillable = [
        'aluno_id',
        'disciplina_id',
        'nota',
        'faltas'
    ];

    /**
     * O aluno ao qual esta nota pertence.
     */
    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    /**
     * A disciplina à qual esta nota pertence.
     */
    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }

    /**
     * Retorna a classe CSS com base no status da nota.
     *
     * @param float $notaMinima Nota mínima para aprovação (padrão: 7.0)
     * @return string
     */
    public function getStatusCssClass($notaMinima = 7.0)
    {
        if ($this->nota === null) {
            return 'text-muted'; // Nota não atribuída
        }

        if ($this->nota >= $notaMinima) {
            return 'text-success'; // Aprovado
        } else {
            return 'text-danger'; // Reprovado
        }
    }

    /**
     * Retorna o status da nota em texto.
     *
     * @param float $notaMinima Nota mínima para aprovação (padrão: 7.0)
     * @return string
     */
    public function getStatusText($notaMinima = 7.0)
    {
        if ($this->nota === null) {
            return 'Não avaliado';
        }

        if ($this->nota >= $notaMinima) {
            return 'Aprovado';
        } else {
            return 'Reprovado';
        }
    }

    /**
     * Retorna o status de aprovação da nota.
     *
     * @param float $notaMinima Nota mínima para aprovação (padrão: 7.0)
     * @return string
     */
    public function getStatusAprovacao($notaMinima = 7.0)
    {
        if ($this->nota === null) {
            return 'Não avaliado';
        }

        if ($this->nota >= $notaMinima) {
            return 'Aprovado';
        } else {
            return 'Reprovado';
        }
    }
}
