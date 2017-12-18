<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReportesModifyFielduser extends Migration
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
            $table->dropColumn('usuario');
            
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
            $table->integer('usuario')->unsigned()->after("tipo")->index();
        });
    }
}
