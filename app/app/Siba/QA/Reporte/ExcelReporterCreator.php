<?php

namespace Siba\QA\Reporte;
use Siba\QA\Reporte\Interfaces\IReporterCreator;
use Siba\QA\Reporte\ReporterDataGenerator;
use Siba\QA\Reporte\ExcelReporterData;
use Siba\QA\Reporte\ExcelReporter;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelReporterCreator implements IReporterCreator{
    //
    public function create($params){

    	$query = '';
    	if (!isset($params['fechaini'])){
    		throw new \Exception("El campo de fecha inicial es obligatorio");
    	}

		if (!isset($params['fechafin'])){
    		throw new \Exception("El campo de fecha final es obligatorio");
    	}

    	$query = " (created_at >= '".$params['fechaini']." 00:00:00' and created_at <='".$params['fechafin']." 23:59:59') ";


    	if (!isset($params['tiporepote'])){
    		$query .= " and (tipo='0')";
    	}
    	else{

    		switch ($params['tiporepote']){

    			case 'error': $query .= " and (tipo='0')";
    					break;
    			case 'ok': $query .= " and (tipo='1')";
    					break;
    			default: $query .= "";
    		}
    	}

    	$reportes = ReporterDataGenerator::createDataReporter($query);
        $fileName = "reporte_".md5(date("Y-m-d H:i:s")).".xlsx";
        $excelReporterData = new ExcelReporterData();
        $reportes = $excelReporterData->normalizeData($reportes);
        $spreadSheet = new Spreadsheet();
        $excelMemory = new ExcelReporter($reportes);
        $spreadSheet = $excelMemory->createExcelReport($spreadSheet);
        //================================================================
        //print_r($spreadSheet);
        $writer = new Xlsx($spreadSheet);
        $pathToFile = storage_path().'/reports/'.$fileName;
        $writer->save($pathToFile);
        return $pathToFile;
    }
}
