<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlunosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
        {
            Schema::create('alunos', function (Blueprint $table) {
                $table->id();
                $table->string('nome');
                $table->string('cpf')->unique();
                $table->date('data_nascimento');
                $table->string('endereco');
                $table->string('telefone');
                $table->string('email')->unique();
                $table->enum('status', ['ativo', 'inativo', 'trancado', 'formado']);
                $table->timestamps();
            });
        }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alunos');
    }
}
