<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfessorIdToDisciplinasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('disciplinas', function (Blueprint $table) {
            // foreignId cria uma coluna unsignedBigInteger e a foreign key
            // constrained('professores') cria a foreign key para a tabela 'professores'
            // onDelete('set null') define o comportamento se o professor for deletado
            // nullable() permite que a coluna seja nula, caso um professor seja deletado ou não seja atribuído
            $table->foreignId('professor_id')->nullable()->constrained('professores')->onDelete('set null');
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('disciplinas', function (Blueprint $table) {
            // dropConstrainedForeignId remove a foreign key e a coluna
            $table->dropConstrainedForeignId('professor_id');
        });
    }
}
