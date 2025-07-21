<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('professores', function (Blueprint $table) {
            // Adiciona a coluna 'formacao' como string e permite que seja nula (nullable)
            // Se você quiser que ela seja obrigatória, remova ->nullable()
            $table->string('formacao')->nullable()->after('email'); // Adiciona após a coluna 'email'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('professores', function (Blueprint $table) {
            // Remove a coluna 'formacao' se a migração for revertida
            $table->dropColumn('formacao');
        });
    }
};
