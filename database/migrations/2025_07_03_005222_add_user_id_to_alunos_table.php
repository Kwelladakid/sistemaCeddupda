<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// class AddUserIdToAlunosTable extends Migration
// {
//     /**
//      * Run the migrations.
//      *
//      * @return void
//      */
//     public function up()
//     {
//         Schema::table('alunos', function (Blueprint $table) {
//             $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
//         });
//     }

//     /**
//      * Reverse the migrations.
//      *
//      * @return void
//      */
//     public function down()
//     {
//         Schema::table('alunos', function (Blueprint $table) {
//             $table->dropForeign(['user_id']);
//             $table->dropColumn('user_id');
//         });
//     }
// }
