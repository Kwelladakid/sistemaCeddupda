<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToTurmasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('turmas', function (Blueprint $table) {
            // Adiciona a coluna professor_id após a coluna curso_id, se não existir
            if (!Schema::hasColumn('turmas', 'professor_id')) {
                $table->unsignedBigInteger('professor_id')->nullable()->after('curso_id');
                $table->foreign('professor_id')->references('id')->on('professores')->onDelete('set null');
            }

            // Adiciona a coluna periodo após a coluna ano, se não existir
            if (!Schema::hasColumn('turmas', 'periodo')) {
                $table->string('periodo')->nullable()->after('ano');
            }

            // Adiciona a coluna status após a coluna periodo, se não existir
            if (!Schema::hasColumn('turmas', 'status')) {
                $table->string('status')->default('ativa')->after('periodo');
            }
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
            // Remove as colunas na ordem inversa
            if (Schema::hasColumn('turmas', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('turmas', 'periodo')) {
                $table->dropColumn('periodo');
            }

            if (Schema::hasColumn('turmas', 'professor_id')) {
                $table->dropForeign(['professor_id']);
                $table->dropColumn('professor_id');
            }
        });
    }
}
