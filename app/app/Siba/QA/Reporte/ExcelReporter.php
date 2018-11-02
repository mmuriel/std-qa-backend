<?php

namespace Siba\QA\Reporte;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use \PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ExcelReporter{

	private $excel;
	private $reporterData;

	public function __construct($reporterData){
		$this->reporterData = $reporterData;
	}

	public function createExcelReport(\PhpOffice\PhpSpreadsheet\Spreadsheet $spreadSheet){

		//1. Genera los worksheets (hojas)
		$wsResumen = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadSheet, 'Resumen');
		$wsDatos = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadSheet, 'Datos');
		//2. Escribe los datos sobre las hojas de cáculo
		//2.1. Escribe lo datos de la primera hoja
		//2.1.1. Inconsistencias por tipo
		$wsResumen->setCellValue("A3","Inconsistencias por tipo");
		$wsResumen->setCellValue("A4","Desfase horario");
		$wsResumen->setCellValue("A5","Programación no coincide");
		$wsResumen->setCellValue("A6","Otros");
		$wsResumen->setCellValue("A7","Total");
		//2.1.2. Inconsistencias por motivo
		$wsResumen->setCellValue("A9","Inconsistencias por motivo");
		$wsResumen->setCellValue("A10","Origen");
		$wsResumen->setCellValue("A11","Error humano");
		$wsResumen->setCellValue("A12","Evento en vivo");
		$wsResumen->setCellValue("A13","Total");
		//2.1.3. Inconsistencias por programadora
		$wsResumen->setCellValue("A15","Inconsistencias por casa programadora");

		//2.2. Escribe lo datos de la segunda hoja
		$wsDatos->setCellValue("A1","Fecha-Hora Reporte");
		$wsDatos->setCellValue("B1","Fecha-Hora Evento");
		$wsDatos->setCellValue("C1","ID Evento");
		$wsDatos->setCellValue("D1","Título Evento");
		$wsDatos->setCellValue("E1","ID Canal");
		$wsDatos->setCellValue("F1","Nombre Canal");
		$wsDatos->setCellValue("G1","Casa Programadora");
		$wsDatos->setCellValue("H1","Tipo Error");
		$wsDatos->setCellValue("I1","Motivo Error");
		$wsDatos->setCellValue("J1","Desafase");
		$wsDatos->setCellValue("K1","Transmitiendo");
		$wsDatos->setCellValue("L1","Usuario");

		$totals = $this->calculateTotals();
		$wsResumen = $this->setResumenPage($wsResumen,$totals);
		$wsDatos = $this->setDataPage($wsDatos);		
		//Vincula las hojas al documento
		$spreadSheet->addSheet($wsResumen, 0);
		$spreadSheet->addSheet($wsDatos, 1);
		return $spreadSheet;


	}


	public function calculateTotals(){

		$totals = array(
			"total_inconsistencias" => 0,
			"total_tipo_nocoincide" => 0,
			"total_tipo_desfase" => 0,
			"total_tipo_otros" => 0,
			"total_motivo_origen" => 0,
			"total_motivo_humano" => 0,
			"total_motivo_vivo" => 0,
			"inconsistencias_por_casa" => array("otros"=>0),
		);

		foreach ($this->reporterData as $reporte){
			//Verifica el tipo
			if ($reporte->errores->count() >= 1)
				$error = $reporte->errores->first();
			else
				continue;
			$canal = $reporte->canal;
			if ($error->tipo == 1){
				$totals['total_tipo_desfase']++;
			}
			elseif ($error->tipo == 2){
				$totals['total_tipo_nocoincide']++;
			}
			elseif ($error->tipo == 3){
				$totals['total_tipo_otros']++;
			}

			//===================================================
			if ($error->motivo == 1){
				$totals['total_motivo_origen']++;
			}
			elseif ($error->motivo == 2){
				$totals['total_motivo_humano']++;
			}
			elseif ($error->motivo == 3){
				$totals['total_motivo_vivo']++;
			}
			//===================================================
			if (isset($reporte->canal->prod_company->name)){
				if (isset($totals['inconsistencias_por_casa'][$reporte->canal->prod_company->name])){
					$totals['inconsistencias_por_casa'][$reporte->canal->prod_company->name]++;
				}
				else{
					$totals['inconsistencias_por_casa'][$reporte->canal->prod_company->name]=1;	
				}
			}
			else{
				$totals['inconsistencias_por_casa']['otros']++;
			}
		}
		return $totals;
	}

	private function setResumenPage($wsResumen,$data){

		$wsResumen->setCellValue("B4",$data['total_tipo_desfase']);
		$wsResumen->setCellValue("B5",$data['total_tipo_nocoincide']);
		$wsResumen->setCellValue("B6",$data['total_tipo_otros']);
		$wsResumen->setCellValue("B7",($data['total_tipo_desfase'] + $data['total_tipo_nocoincide'] + $data['total_tipo_otros']));
		//2.1.2. Inconsistencias por motivo
		$wsResumen->setCellValue("B10",$data['total_motivo_origen']);
		$wsResumen->setCellValue("B11",$data['total_motivo_humano']);
		$wsResumen->setCellValue("B12",$data['total_motivo_vivo']);
		$wsResumen->setCellValue("B13",($data['total_tipo_desfase'] + $data['total_tipo_nocoincide'] + $data['total_tipo_otros']));
		//2.1.3. Inconsistencias por programadora
		$ctrlLine = 16;
		$casasKeys = array_keys($data['inconsistencias_por_casa']);
		for ($i=0;$i<count($casasKeys);$i++){

			$keyIndex = $casasKeys[$i];
			$wsResumen->setCellValue("A".$ctrlLine,$casasKeys[$i]);
			$wsResumen->setCellValue("B".$ctrlLine,$data['inconsistencias_por_casa'][$keyIndex]);
			$ctrlLine++;

		}

		return $wsResumen;

	}

	private function setDataPage($wsDatos){

		$ctrlCell=1;
		foreach ($this->reporterData as $reporte){
			$ctrlCell++;
			//$wsDatos->setCellValue("A".$ctrlCell,strtotime($reporte->created_at));
			$wsDatos->setCellValue("A".$ctrlCell,$reporte->created_at);
			//print_r($wsDatos);
			/*
			$cellAStyle = $wsDatos->getStyle("A".$ctrlCell)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DATETIME);
			*/
			//$wsDatos->setCellValue("B".$ctrlCell,strtotime($reporte->evento_fechahora));
			$wsDatos->setCellValue("B".$ctrlCell,$reporte->evento_fechahora);
			/*
			$wsDatos->getStyle("B".$ctrlCell)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DATETIME);
			*/
			$wsDatos->setCellValue("C".$ctrlCell,$reporte->evento);
			$wsDatos->setCellValue("D".$ctrlCell,$reporte->evento_titulo);
			
			if (isset($reporte->canal->name))
				$wsDatos->setCellValue("E".$ctrlCell,$reporte->canal->id);
			else
				$wsDatos->setCellValue("E".$ctrlCell,"");




			if (isset($reporte->canal->name))
				$wsDatos->setCellValue("F".$ctrlCell,$reporte->canal->name);
			else
				$wsDatos->setCellValue("E".$ctrlCell,"");




			if (isset($reporte->canal->prod_company)){
				$wsDatos->setCellValue("G".$ctrlCell,$reporte->canal->prod_company->name);	
			}
			else{
				$wsDatos->setCellValue("G".$ctrlCell,"Otro");
			}



			switch ($reporte->errores->first()->tipo){
				case 1: 
						$wsDatos->setCellValue("H".$ctrlCell,"Desfase horario");
						break;
				case 2: 
						$wsDatos->setCellValue("H".$ctrlCell,"Programación no coincide");
						break;
				case 3: 
						$wsDatos->setCellValue("H".$ctrlCell,"Otros");
						break;
			}


			switch ($reporte->errores->first()->motivo){
				case 1: 
						$wsDatos->setCellValue("I".$ctrlCell,"Origen");
						break;
				case 2: 
						$wsDatos->setCellValue("I".$ctrlCell,"Error humano");
						break;
				case 3: 
						$wsDatos->setCellValue("I".$ctrlCell,"Evento en vivo");
						break;
			}

			if ($reporte->errores->first()->tipo == 1)
				$wsDatos->setCellValue("J".$ctrlCell,$reporte->errores->first()->desfase);
			else
				$wsDatos->setCellValue("J".$ctrlCell,0);

			if ($reporte->errores->first()->tipo == 2)
				$wsDatos->setCellValue("K".$ctrlCell,$reporte->errores->first()->transmitiendo);
			else
				$wsDatos->setCellValue("K".$ctrlCell,"");
			$wsDatos->setCellValue("L".$ctrlCell,$reporte->usuario);

		}
		return $wsDatos;
	}

}