<?php

namespace Siba\Test;

use Illuminate\Foundation\Testing\TestCase ;
use Illuminate\Contracts\Console\Kernel;

use Siba\QA\Canal\CanalRepo;
use Siba\QA\Canal\Canal;

use Siba\QA\Reporte\ReporterDataGenerator;
use Siba\QA\Reporte\Reporte;
use Siba\QA\Reporte\ReporteRepo;
use Siba\QA\Reporte\ExcelReporterData;
use Siba\QA\Reporte\ExcelReporter;
use Siba\QA\Reporte\ExcelReporterCreator;


use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Siba\QA\Error\Error;
use Siba\QA\Error\ErrorRepo;

use \Faker\Factory;


class ReporterTest extends TestCase {


    private $eventMultipleReports;

	public function setUp(){
        parent::setUp();
        $this->prepareForTests();
    }


    public function testReporterDataBasic(){


        $reportes = ReporterDataGenerator::createDataReporter(" tipo='0' ");
        $this->assertEquals(50,$reportes->count());

    }


    public function testReporterDataGetErrorReportsAndDateTime(){
        $limitTime = strtotime("+24hour");
        $limitDate = date("Y-m-d H:i:s",$limitTime);
        $reportes = ReporterDataGenerator::createDataReporter(" tipo='0' and evento_fechahora >= '".$limitDate."'");
        //print_r($reportes);
        $this->assertEquals('XX-48',$reportes->first()->evento_titulo);
    }



    public function testGetReportsByIdEvent(){

        $reportes = ReporterDataGenerator::createDataReporter(" 1 ");
        $reportes = $reportes->where('evento',$this->eventMultipleReports);
        $this->assertEquals(4,$reportes->count());
    }


    public function testReporterGroupingByEvent(){

        $reportes = ReporterDataGenerator::createDataReporter(" 1 ");
        $reportes = $reportes->groupBy('evento');
        foreach ($reportes as $eventCollection){

            if ($eventCollection->count() > 1){
                $this->assertEquals(4,$eventCollection->count());
                
                //$sortedEvtCollection = $eventCollection->sortByDesc('evento_fechahora');
                //$this->assertEquals('XX-50',$sortedEvtCollection->first()->evento_titulo);
            }

        }
    }


    public function testExcelReporter(){
        $reportes = ReporterDataGenerator::createDataReporter(" tipo='0' ");
        $excelReporterData = new ExcelReporterData();
        $reportes = $excelReporterData->normalizeData($reportes);


        $ctrlTotal = 1;
        
        $ctrlTipoDesfase = 0;
        $ctrlTipoNoCoincide = 0;
        $ctrlTipoOtros = 0;

        $ctrlMotivoOrigen = 0;
        $ctrlMotivoHumano = 0;
        $ctrlMotivoVivo = 0;
        
        //echo "\n\n";
        //echo "No.\t\tID EVENTO \t\tTitulo \t\tFecha Hora \t\t\tTipo \t\tMotivo \n";
        foreach($reportes as $reporte){
            $canal = $reporte->canal;
            $error = $reporte->errores->first();
            //echo $ctrlTotal."\t\t".$reporte->evento."\t\t".$reporte->evento_titulo."\t\t".$reporte->evento_fechahora."\t\t".$error->tipo."\t\t".$error->motivo."\n";
            $ctrlTotal++;

            if ($error->motivo == 1)
                $ctrlMotivoOrigen++;
            elseif ($error->motivo == 2)
                $ctrlMotivoHumano++;
            elseif ($error->motivo == 3)
                $ctrlMotivoVivo++;

            if ($error->tipo == 1)
                $ctrlTipoDesfase++;
            elseif ($error->tipo == 2)
                $ctrlTipoNoCoincide++;
            elseif ($error->tipo == 3)
                $ctrlTipoOtros++;
        }

        $spreadSheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        //$excelReporter = new ExcelReporter($spreadSheet);
        $excelReporter = new ExcelReporter($reportes);
        $totals = $excelReporter->calculateTotals();
        
        $this->assertEquals($ctrlMotivoOrigen,$totals["total_motivo_origen"]);
        $this->assertEquals($ctrlMotivoHumano,$totals["total_motivo_humano"]);
        $this->assertEquals($ctrlMotivoVivo,$totals["total_motivo_vivo"]);

        $this->assertEquals($ctrlTipoDesfase,$totals["total_tipo_desfase"]);
        $this->assertEquals($ctrlTipoNoCoincide,$totals["total_tipo_nocoincide"]);
        $this->assertEquals($ctrlTipoOtros,$totals["total_tipo_otros"]);
    }


    public function testExcelReporterData(){
        $reportes = ReporterDataGenerator::createDataReporter(" 1 ");
        $this->assertEquals(100,$reportes->count());
        $excelReporterData = new ExcelReporterData();
        $reportes = $excelReporterData->normalizeData($reportes);
        $this->assertEquals(97,$reportes->count());
        foreach ($reportes as $key => $reporte){
            if ($reporte->errores->count()>=1){

                if (isset($reporte->canal->prod_company->name) && $reporte->evento == 295431369){
                    //print_r($reporte->canal->prod_company);
                    //echo $key.": ".$reporte->errores->first()->tipo." - ".$reporte->canal->prod_company->name."\n";
                    $this->assertEquals('HBO',$reporte->canal->prod_company->name);
                }
            }
        }
    }


    public function testExcelReporterToExcelObject(){
        
        $reportes = ReporterDataGenerator::createDataReporter(" tipo='0' ");
        $fileName = "testfile_".md5(date("Y-m-d H:i:s")).".xlsx";
        $excelReporterData = new ExcelReporterData();
        $reportes = $excelReporterData->normalizeData($reportes);
        $spreadSheet = new Spreadsheet();
        $excelMemory = new ExcelReporter($reportes);
        $spreadSheet = $excelMemory->createExcelReport($spreadSheet);
        //================================================================
        //print_r($spreadSheet);
        $writer = new Xlsx($spreadSheet);
        $pathToFile = storage_path().'/testing/'.$fileName;
        $writer->save($pathToFile);
        $this->assertFileExists($pathToFile);
    }


    public function testExcelReporterCreator(){
        
        $reporterCreator = new ExcelReporterCreator();
        
        $reportPath = $reporterCreator->create(array(
                'fechaini'=>date("Y-m-d"),
                'fechafin'=>date("Y-m-d"),
            )
        );
        $this->assertFileExists($reportPath);
    }


    public function testExcelReporterCreatorError(){
        
        $reporterCreator = new ExcelReporterCreator();
        
        try {
            $reportPath = $reporterCreator->create(array(
                    'fechaini'=>date("Y-m-d")
                )
            );
        }
        catch (\Exception $e){
            $this->assertEquals('El campo de fecha final es obligatorio',$e->getMessage());
        }
        
        try {
            $reportPath = $reporterCreator->create(array(
                    'fechafin'=>date("Y-m-d")
                )
            );
        }
        catch (\Exception $e){
            $this->assertEquals('El campo de fecha inicial es obligatorio',$e->getMessage());
        }
    }

	/* Preparing the Test */
	public function createApplication(){
        $app = require __DIR__.'/../../../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();
        return $app;
    }

    private function prepareForTests(){
        \Artisan::call('migrate');

        /* Registra los datos para simular la información */
        $faker = \Faker\Factory::create();
        $reporteRepo = new ReporteRepo();
        $errorRepo = new ErrorRepo();
        for ($i = 1 ; $i <= 100 ; $i++){

            $tipoReporte = 1;
            if (($i % 2) == 0){
                $tipoReporte = 0;
            }


            if ($i==2){
                $idevt = 295431369;
                $idChn = $faker->numberBetween(380,1100);
                $idEvtFixed = $idevt;
                $idChnFixed = 380;
                $this->eventMultipleReports = $idEvtFixed;
            }
            elseif (($i==48) || ($i==49) || ($i==50)){
                $idevt = $idEvtFixed;
                $idChn = $idChnFixed;
            }
            else{
                $idevt = $faker->numberBetween(267439966,360162877);
                $idChn = $faker->numberBetween(380,1100);
            }
            $reporte = $reporteRepo->create([
                'idmd5' => $faker->md5,
                'usuario' => 'mmuriel@siba.com.co',
                'tipo' => $tipoReporte,
                'evento' => $idevt,
                'canal' => $idChn,
                'evento_titulo' => 'XX-'.$i,
                'evento_fechahora' => date("Y-m-d H:i:s",strtotime("+".($i*30)."min"))
            ]);
            $reporteRepo->save($reporte);

            if ($tipoReporte == 0){

                $tipoError = $faker->numberBetween($min = 1, $max = 2);
                if ($tipoError==1){

                    //Desfase horario
                    $desfase = $faker->numberBetween($min = 1, $max=120);
                    $transmitiendo = '';

                }
                else{

                    //Programacion no coincide
                    $desfase = 0;
                    $transmitiendo = $faker->sentence($nbWords = 6, $variableNbWords = true);

                }

                $error = $errorRepo->create([
                    'idmd5' => $faker->md5,
                    'tipo' => $tipoError,
                    'motivo' => $faker->numberBetween($min=1,$max=3),
                    'detalle' => $faker->text($maxNbChars = 10),
                    'desfase'=>$desfase,
                    'transmitiendo'=>$transmitiendo,
                    'reporte'=>$reporte->id
                ]);
                $errorRepo->save($error);
            }
        }
    }
}