<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    protected $fillable = ['aluno_id', 'turma_id', 'data', 'status'];

    public function aluno() {
        return $this->belongsTo(Aluno::class);
    }

    public function turma() {
        return $this->belongsTo(Turma::class);
    }
}
