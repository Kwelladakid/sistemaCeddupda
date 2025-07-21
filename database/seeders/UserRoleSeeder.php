<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Importa o modelo User
use Illuminate\Support\Facades\Hash; // Importa a facade Hash para criptografar senhas

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Cria um usuário Administrador
        User::create([
            'name' => 'Administrador Geral',
            'email' => 'admin@escola.com',
            'password' => Hash::make('password'), // Senha 'password' criptografada
            'role' => User::ROLE_ADMIN, // Atribui o papel de Administrador
        ]);

        // Cria um usuário Secretária
        User::create([
            'name' => 'Secretária Maria',
            'email' => 'secretaria@escola.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_SECRETARY,
        ]);

        // Cria um usuário Professor
        User::create([
            'name' => 'Professor João',
            'email' => 'professor@escola.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_TEACHER,
        ]);

        // Cria um usuário Aluno (padrão)
        User::create([
            'name' => 'Aluno Pedro',
            'email' => 'aluno@escola.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_STUDENT,
        ]);

        // Opcional: Atualizar um usuário existente (ex: o usuário 'teste@example.com' criado anteriormente)
        // User::where('email', 'teste@example.com')->update(['role' => User::ROLE_ADMIN]);
    }
}
