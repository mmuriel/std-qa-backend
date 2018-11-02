<?php

use Illuminate\Database\Seeder;

use Siba\QA\Reporte\Reporte;
use Siba\QA\Error\Error;

class ReportesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(Reporte::class, 5)->create()->each(function($reporte) {
  			factory(Error::class,1)->create(['reporte'=>$reporte->id]);
		});
    }
}
