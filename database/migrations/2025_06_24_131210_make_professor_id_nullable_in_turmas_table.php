<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeProfessorIdNullableInTurmasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('turmas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('professor_id'); // Remove a foreign key e a coluna
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('turmas', function (Blueprint $table) {
            // Se precisar reverter, adicione a coluna e a foreign key novamente
            $table->foreignId('professor_id')->constrained('professores');
        });
    }
}
