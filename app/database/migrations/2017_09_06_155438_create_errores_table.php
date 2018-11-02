<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErroresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('errores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idmd5',32);
            /*
                Enum codigos de tipo de error
                1 = Desfase horario
                2 = Programacion no coincide
                3 = Otros
            */            
            $table->enum('tipo',['1','2','3'])->comment("Codigos del tipo de error: 1 = Desfase horario; 2 =Programacion no coincide; 3 = Otros");
            /*
                Enum codigos de tipo de error
                1 = Falla Origen
                2 = Error humano
                3 = Evento en vivo
            */            
            $table->enum('motivo',['1','2','3'])->comment("Codigos de motivo de error: 1. Falla Origen; 2. Error humano; 3. Evento en vivo");
            $table->text('detalle')->comment('Tiempo en minutos si el tipo es Desfase horario');
            /*
                Tiempo en minutos si el tipo es Desfase horario
            */
            $table->integer('desfase');
            $table->timestamps();
            $table->string('transmitiendo',250);
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
        Schema::dropIfExists('errores');
    }
}
