<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFaltasToNotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notas', function (Blueprint $table) {
            // Adiciona a coluna 'faltas' após a coluna 'nota'
            $table->integer('faltas')->default(0)->after('nota');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notas', function (Blueprint $table) {
            // Remove a coluna 'faltas' se a migração for revertida
            $table->dropColumn('faltas');
        });
    }
}
