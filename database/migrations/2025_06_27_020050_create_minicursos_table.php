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
        Schema::create('minicursos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->integer('carga_horaria'); // Em horas
            $table->string('professor_responsavel'); // Nome do professor responsável pelo minicurso
            $table->text('descricao')->nullable(); // Uma breve descrição do minicurso
            $table->date('data_inicio')->nullable();
            $table->date('data_fim')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('minicursos');
    }
};
