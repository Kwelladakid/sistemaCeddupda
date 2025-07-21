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
        Schema::create('aluno_curso', function (Blueprint $table) {
            $table->id(); // ID primário para a tabela pivot
            $table->foreignId('aluno_id')->constrained('alunos')->onDelete('cascade');
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade');
            $table->timestamps(); // Opcional, mas bom para auditoria (quando o aluno foi matriculado no curso)

            // Garante que um aluno só pode ser matriculado uma vez no mesmo curso
            $table->unique(['aluno_id', 'curso_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aluno_curso');
    }
};
