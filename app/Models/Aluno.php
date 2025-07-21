<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
    use HasFactory;

    protected $fillable = [

            'nome',
            'cpf',
            'data_nascimento',
            'endereco',
            'telefone',
            'email',
            'status',
            'user_id', // Para vincular o aluno ao usuário

    ];

    /**
     * As turmas em que este aluno está matriculado.
     */
    public function turmas()
    {
        return $this->belongsToMany(Turma::class, 'aluno_turma')
                    ->withPivot('data_matricula', 'status')
                    ->withTimestamps();
    }

    /**
     * Os cursos em que este aluno está matriculado (através das turmas).
     */
    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'aluno_curso', 'aluno_id', 'curso_id')
                    ->withPivot('data_matricula', 'status')
                    ->withTimestamps();
    }

    /**
     * Obtém todas as notas deste aluno.
     */
    public function notas()
    {
        return $this->hasMany(Nota::class);
    }

    /**
     * Calcula a média geral das notas do aluno.
     *
     * @return float|null A média das notas ou null se não houver notas
     */
    public function calcularMediaGeral()
    {
        $notas = $this->notas;

        if ($notas->isEmpty()) {
            return null;
        }

        $soma = $notas->sum('nota');
        $quantidade = $notas->count();

        return $soma / $quantidade;
    }

    /**
     * Verifica se o aluno está aprovado em todas as disciplinas.
     *
     * @param float $notaMinima Nota mínima para aprovação (padrão: 7.0)
     * @return bool|null true se aprovado em todas, false se reprovado em alguma, null se não tiver notas
     */
    public function isAprovadoGeral($notaMinima = 7.0)
    {
        $notas = $this->notas;

        if ($notas->isEmpty()) {
            return null;
        }

        foreach ($notas as $nota) {
            if ($nota->nota < $notaMinima) {
                return false;
            }
        }

        return true;
    }

    /**
     * Conta quantas disciplinas o aluno está aprovado.
     *
     * @param float $notaMinima Nota mínima para aprovação (padrão: 7.0)
     * @return int Número de disciplinas aprovadas
     */
    public function contarDisciplinasAprovadas($notaMinima = 7.0)
    {
        return $this->notas->filter(function ($nota) use ($notaMinima) {
            return $nota->nota >= $notaMinima;
        })->count();
    }

    /**
     * Conta quantas disciplinas o aluno está reprovado.
     *
     * @param float $notaMinima Nota mínima para aprovação (padrão: 7.0)
     * @return int Número de disciplinas reprovadas
     */
    public function contarDisciplinasReprovadas($notaMinima = 7.0)
    {
        return $this->notas->filter(function ($nota) use ($notaMinima) {
            return $nota->nota < $notaMinima;
        })->count();
    }

    /**
     * Os pagamentos realizados por este aluno.
     */
    public function pagamentos()
    {
        return $this->hasMany(Pagamento::class);
    }

    /**
     * Obtém o número de matrícula do aluno baseado no ID.
     * Pode ser substituído por um campo real de matrícula se existir.
     *
     * @return string
     */
    public function getNumeroMatriculaAttribute()
    {
        // Se você já tiver um campo de matrícula, use-o em vez desta lógica
        return 'MAT' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Obtém o curso atual do aluno (o mais recente).
     *
     * @return string|null
     */
    public function getCursoAtualAttribute()
    {
        $turmaAtual = $this->turmas()
                          ->orderBy('aluno_turma.created_at', 'desc')
                          ->first();

        return $turmaAtual ? $turmaAtual->curso->nome : null;
    }

    /**
     * Verifica se o aluno está com matrícula ativa.
     *
     * @return bool
     */
    public function getStatusAtivoAttribute()
    {
        $matriculaAtiva = $this->turmas()
                              ->wherePivot('status', 'ativo')
                              ->exists();

        return $matriculaAtiva;
    }

    /**
     * As mensalidades deste aluno.
     */
    public function mensalidades()
    {
        return $this->hasMany(Mensalidade::class);
    }

    /**
     * Obtém as mensalidades pendentes do aluno.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMensalidadesPendentes()
    {
        return $this->mensalidades()
            ->whereIn('status', ['pendente', 'atrasada'])
            ->orderBy('data_vencimento')
            ->get();
    }

    /**
     * Obtém as mensalidades atrasadas do aluno.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMensalidadesAtrasadas()
    {
        return $this->mensalidades()
            ->where('status', 'pendente')
            ->where('data_vencimento', '<', now())
            ->orderBy('data_vencimento')
            ->get();
    }

    /**
     * Verifica se o aluno está inadimplente.
     *
     * @return bool
     */
    public function isInadimplente()
    {
        return $this->getMensalidadesAtrasadas()->count() > 0;
    }

    /**
     * Calcula o total devido pelo aluno.
     *
     * @return float
     */
    public function totalDevido()
    {
        return $this->getMensalidadesPendentes()->sum('valor_final');
    }

    public function user()
    {
    return $this->belongsTo(User::class);
    }
}
