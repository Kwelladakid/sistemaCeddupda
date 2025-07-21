<?php

// database/migrations/YYYY_MM_DD_HHMMSS_create_disciplina_professor_table.php

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
        Schema::create('disciplina_professor', function (Blueprint $table) {
            // Chaves estrangeiras para os IDs das tabelas 'disciplinas' e 'professores'
            $table->foreignId('disciplina_id')->constrained('disciplinas')->onDelete('cascade');
            $table->foreignId('professor_id')->constrained('professores')->onDelete('cascade');

            // Define uma chave primária composta para garantir a unicidade da combinação
            $table->primary(['disciplina_id', 'professor_id']);

            // Opcional: timestamps se você quiser registrar quando o vínculo foi criado/atualizado
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disciplina_professor');
    }
};
