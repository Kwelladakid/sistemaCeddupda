<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    use HasFactory;

    protected $table = 'pagamentos';

    protected $fillable = [
        'nome',
        'cpf',
        'especialidade',
        'telefone',
        'email',
        'formacao',  // ADICIONE ESTA LINHA
        // ... outras colunas que você já tem no fillable
    ];

    protected $casts = [
        'data_pagamento' => 'date',
    ];

    // Relacionamentos

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mensalidades()
    {
        return $this->hasMany(Mensalidade::class);
    }

    // Métodos auxiliares

    public function getMetodoPagamentoFormatado()
    {
        $metodos = [
            'dinheiro' => 'Dinheiro',
            'cartao_credito' => 'Cartão de Crédito',
            'cartao_debito' => 'Cartão de Débito',
            'pix' => 'PIX',
            'transferencia' => 'Transferência Bancária',
            'boleto' => 'Boleto'
        ];

        return $metodos[$this->metodo_pagamento] ?? ucfirst(str_replace('_', ' ', $this->metodo_pagamento));
    }

    public function getStatusFormatado()
    {
        $status = [
            'confirmado' => 'Confirmado',
            'pendente' => 'Pendente',
            'cancelado' => 'Cancelado'
        ];

        return $status[$this->status] ?? ucfirst($this->status);
    }
}
