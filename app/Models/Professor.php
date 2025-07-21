<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    use HasFactory;

    protected $table = 'professores';

    protected $fillable = [
        'nome',
        'data_nascimento',
        'cpf',
        'email',
        'telefone',
        'endereco',
        'formacao',
        'especialidade',
        'user_id' // <--- Certifique-se de que 'user_id' está aqui para mass assignment
    ];

    /**
     * Obtém o usuário associado a este professor.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtém as turmas que este professor leciona.
     */
    public function turmas()
    {
        return $this->hasMany(Turma::class);
    }

    /**
     * Obtém as disciplinas que este professor leciona.
     * Assumindo um relacionamento muitos-para-muitos com a tabela pivô 'disciplina_professor'.
     */
    public function disciplinas()
    {
        return $this->belongsToMany(Disciplina::class, 'disciplina_professor', 'professor_id', 'disciplina_id');
    }
}
