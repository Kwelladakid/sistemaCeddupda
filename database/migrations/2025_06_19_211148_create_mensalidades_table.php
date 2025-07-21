<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mensalidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained()->onDelete('cascade');
            $table->decimal('valor_base', 10, 2);
            $table->decimal('desconto', 10, 2)->default(0);
            $table->decimal('valor_final', 10, 2);
            $table->date('data_vencimento');
            $table->string('status')->default('pendente'); // pendente, paga, atrasada, cancelada
            $table->foreignId('pagamento_id')->nullable()->constrained();
            $table->string('mes_referencia'); // Formato: YYYY-MM
            $table->text('observacao')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mensalidades');
    }
};
