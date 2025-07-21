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
            // Adiciona a coluna user_id como chave estrangeira
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            // Opcional: Adiciona um índice para otimização
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('professores', function (Blueprint $table) {
            // Remove a chave estrangeira e a coluna
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
