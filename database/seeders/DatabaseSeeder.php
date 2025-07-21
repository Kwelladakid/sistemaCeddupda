<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserRoleSeeder::class); // <-- ADICIONE ESTA LINHA
        // \App\Models\User::factory(10)->create(); // Se você tiver factories e quiser criar mais usuários
    }
}
