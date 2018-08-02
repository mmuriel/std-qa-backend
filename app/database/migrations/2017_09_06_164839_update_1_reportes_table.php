<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update1ReportesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reportes', function (Blueprint $table) {
            //
            $table->string('idmd5',32);
            $table->integer('usuario')->unsigned()->index();
            $table->enum('tipo',['1','0'])->comment('Código del tipo de error: 1 = Reporte evento OK; 0 = Reporte evento Error');//1 = Reporte evento OK; 0 = Reporte evento Error
            $table->integer('evento')->unsigned()->index();
            $table->integer('canal')->unsigned()->index();
            $table->integer('error')->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reportes', function (Blueprint $table) {
            //
            $tables->dropColumn('idmd5');
            $tables->dropColumn('usuario');
            $tables->dropColumn('tipo');
            $tables->dropColumn('evento');
            $tables->dropColumn('canal');
            $tables->dropColumn('error');
        });
    }
}
