<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ErroresAddForeignKeyForReportes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('errores', function (Blueprint $table) {
            //
            $table->integer('reporte')->unsigned()->index();
            $table->foreign('reporte')->references('id')->on('reportes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('errores', function (Blueprint $table) {
            //
            $table->dropForeign(['reporte']);
            $table->dropColumn('reporte');
        });
    }
}
