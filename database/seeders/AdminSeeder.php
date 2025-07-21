<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'kwelladakid@gmail.com', // Substitua pelo e-mail desejado
            'cpf' => '287.538.628-05', // Substitua pelo CPF desejado
            'password' => Hash::make('28753862805'), // Substitua pela senha desejada
            'role' => 'administrador', // Define o papel como administrador
        ]);
    }
}
