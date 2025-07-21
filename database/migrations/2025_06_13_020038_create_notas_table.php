<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained('alunos')->onDelete('cascade');
            $table->foreignId('disciplina_id')->constrained('disciplinas')->onDelete('cascade');
            $table->decimal('nota', 5, 2);
            $table->timestamps();

            $table->unique(['aluno_id', 'disciplina_id']); // Garante uma nota por aluno/disciplina
        });
    }

    public function down()
    {
        Schema::dropIfExists('notas');
    }
};
