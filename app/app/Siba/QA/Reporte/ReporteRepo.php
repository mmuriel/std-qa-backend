<?php

namespace Siba\QA\Reporte;
use Siba\QA\Reporte\Reporte;
use App\Base\IBaseRepo;

class ReporteRepo implements IBaseRepo{	
	
	public function create($data){

		$reporte = new Reporte($data);
		return $reporte;

	}

	public function get($id){

		$reporte= Reporte::find($id);
		return $reporte;

	}

	public function find($data){

		$reportes = Reporte::whereRaw($data)->get();
		return $reportes;

	}

	public function save($reporte){

		$reporte->save();
		return $reporte;
	}

}