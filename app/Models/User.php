<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Mantenha se estiver usando Sanctum

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // --- Definição das Constantes de Papéis (Roles) ---
    public const ROLE_ADMIN = 'administrador';
    public const ROLE_SECRETARY = 'secretaria';
    public const ROLE_TEACHER = 'professor';
    public const ROLE_STUDENT = 'aluno';
    // --- Fim das Constantes de Papéis ---

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'cpf', // Adicionado para permitir a atribuição em massa do CPF
        'email',
        'password',
        'role', // Permite atribuição em massa da coluna 'role'
    ];

    /**
     * Os atributos que devem ser ocultados para arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // REMOVA A LINHA ABAIXO SE ESTIVER EM UMA VERSÃO DO LARAVEL < 10
        // 'password' => 'hashed',
    ];

    /**
     * Define o campo que será usado para autenticação.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'cpf'; // Substitui o campo padrão 'email' por 'cpf'
    }

    // --- Métodos Auxiliares para Verificação de Papel ---

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isSecretary(): bool
    {
        return $this->role === self::ROLE_SECRETARY;
    }

    public function isProfessor(): bool
    {
        return $this->role === self::ROLE_TEACHER;
    }

    public function isAluno(): bool
    {
        return $this->role === self::ROLE_STUDENT;
    }
    // --- Fim dos Métodos Auxiliares ---

    /**
     * Define o relacionamento One-to-One com o modelo Professor.
     */
    public function professor()
    {
        return $this->hasOne(Professor::class);
    }
}
