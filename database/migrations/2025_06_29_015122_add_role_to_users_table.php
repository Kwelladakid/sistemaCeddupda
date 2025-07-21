<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Adiciona a coluna 'role' como string, com um valor padrão 'aluno'
            // e a posiciona após a coluna 'email' para melhor organização.
            $table->string('role')->default('aluno')->after('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove a coluna 'role' caso a migration seja revertida.
            $table->dropColumn('role');
        });
    }
};
