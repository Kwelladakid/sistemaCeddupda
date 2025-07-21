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
        Schema::create('minicurso_participantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('minicurso_id')->constrained('minicursos')->onDelete('cascade'); // Chave estrangeira para a nova tabela minicursos
            $table->string('nome_participante'); // Nome completo do participante
            $table->string('cpf_participante')->nullable(); // CPF do participante (opcional, mas bom para identificação única)
            $table->string('codigo_autenticacao')->unique()->nullable(); // ID de autenticação do certificado
            $table->timestamp('data_conclusao')->nullable(); // Data em que o participante concluiu o minicurso
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('minicurso_participantes');
    }
};
