<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReporteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reportes', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('idmd5',32);
            $table->enum('tipo',['1','0'])->comment('Código del tipo de error: 1 = Reporte evento OK; 0 = Reporte evento Error');
            $table->string('usuario',160)->index();
            $table->integer('evento')->unsigned()->index();
            $table->string('evento_titulo',250);
            $table->dateTime('evento_fechahora')->nullable();
            $table->integer('canal')->unsigned()->index();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reportes');
    }
}
