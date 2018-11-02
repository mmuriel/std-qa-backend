<?php

namespace Siba\QA\Reporte;
use Siba\QA\Canal\CanalRepo;

class ReporterDataGenerator{


	public static function createDataReporter($query){

		//Gets all channels data
		$canalRepo = new CanalRepo();
		$canales = $canalRepo->find(array(
			'limit' => '0,1100'
		));
		$reporteRepo = new ReporteRepo();
		$reportes = $reporteRepo->find($query);
		$reportes->transform(function($reporte,$index) use ($canales){
			$reporte->canal = $canales->where('id',$reporte->canal)->first();
			return $reporte;
		});
		return $reportes;
	}

}