<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained()->onDelete('cascade');
            $table->decimal('valor', 10, 2);
            $table->string('metodo_pagamento');
            $table->string('status');
            $table->text('observacao')->nullable();
            $table->date('data_pagamento');
            $table->foreignId('user_id')->constrained(); // Usuário que registrou o pagamento
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagamentos');
    }
};
