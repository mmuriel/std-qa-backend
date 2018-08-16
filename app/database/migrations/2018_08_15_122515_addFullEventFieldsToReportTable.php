<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFullEventFieldsToReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('reportes', function (Blueprint $table) {
            //
            $table->string('evento_titulo',250)->after('evento');
            $table->dateTime('evento_fechahora')->after('evento_titulo');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('reportes', function (Blueprint $table) {
            //
            $table->dropColumn('evento_titulo');
            $table->dropColumn('evento_fechahora');
        });
    }
}
