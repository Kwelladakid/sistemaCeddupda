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
        Schema::create('minicurso_aluno', function (Blueprint $table) {
            $table->id();
            $table->foreignId('minicurso_id')->constrained('minicursos')->onDelete('cascade');
            $table->foreignId('aluno_id')->constrained('alunos')->onDelete('cascade');
            $table->string('codigo_autenticacao')->unique()->nullable(); // ID de autenticação do certificado
            $table->timestamp('data_conclusao')->nullable(); // Data em que o aluno concluiu o minicurso
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('minicurso_aluno');
    }
};
