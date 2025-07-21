<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Mensalidade extends Model
{
    use HasFactory;

    protected $table = 'mensalidades';

    protected $fillable = [
        'aluno_id',
        'valor_base',
        'desconto',
        'valor_final',
        'data_vencimento',
        'status',
        'pagamento_id',
        'mes_referencia',
        'observacao'
    ];

    protected $casts = [
        'data_vencimento' => 'date',
    ];

    // Relacionamentos

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    public function pagamento()
    {
        return $this->belongsTo(Pagamento::class);
    }

    // Métodos auxiliares

    public function marcarComoPaga($pagamento_id)
    {
        $this->status = 'paga';
        $this->pagamento_id = $pagamento_id;
        $this->save();
    }

    public function estaAtrasada()
    {
        return $this->status === 'pendente' && $this->data_vencimento < Carbon::today();
    }

    public function getMesReferencia()
    {
        // Converter YYYY-MM para MM/YYYY
        if (strlen($this->mes_referencia) === 7) { // Formato YYYY-MM
            $partes = explode('-', $this->mes_referencia);
            return $partes[1] . '/' . $partes[0];
        }

        return $this->mes_referencia;
    }

    public function getStatusFormatado()
    {
        $status = [
            'pendente' => 'Pendente',
            'paga' => 'Paga',
            'atrasada' => 'Atrasada',
            'cancelada' => 'Cancelada'
        ];

        return $status[$this->status] ?? ucfirst($this->status);
    }
}
