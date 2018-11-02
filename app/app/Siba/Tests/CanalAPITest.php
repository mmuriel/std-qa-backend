<?php

namespace Siba\Test;

use Illuminate\Foundation\Testing\TestCase ;
use Illuminate\Contracts\Console\Kernel;

use Siba\QA\Canal\CanalRepo;
use Siba\QA\Canal\Canal;


class CanalAPITest extends TestCase {

	public function setUp(){
        parent::setUp();
    }


    public function testGetCanalFromAPI(){

        $canalRepo = new CanalRepo();
        $canal = $canalRepo->get('380');
        $this->assertEquals("AE MUNDO ANDES",$canal->name);
        $this->assertEquals("HBO",$canal->prod_company->name,"Error en la lectura de la casa productora");

    }


    public function testGetAllCanalesFromAPI(){

        $canalRepo = new CanalRepo();
        $canales = $canalRepo->find(array(
            'limit' => '0,1000'
        ));
        $this->assertGreaterThan(200,$canales->count());

    }


    public function testGettingACertainChannelByID(){

        $canalRepo = new CanalRepo();
        $canales = $canalRepo->find(array(
            'limit' => '0,10'
        ));
        $canal = $canales->where('id','380')->first();
        //print_r($canal);
        $this->assertEquals('AE MUNDO ANDES',$canal->name,"No es el canal AE MUNDO ANDES");

    }

    public function testSortingByChannelName(){

        $canalRepo = new CanalRepo();
        $canales = $canalRepo->find(array(
            'limit' => '200,20'
        ));
        /*
        echo "Antes del ordenamiento\n";
        
        foreach ($canales as $canal){
            echo $canal->id.": ".$canal->name."\n";
        }
        */
        $sortedCanales = $canales->sortBy('name');
        /*
        echo "Depues del ordenamiento\n";
        foreach ($sortedCanales as $canal){
            echo $canal->id.": ".$canal->name."\n";
        }
        */
        $this->assertFalse(($canales->first()->id === $sortedCanales->first()->id),"Es el mismo canal");

    }

	/* Preparing the Test */
	public function createApplication(){
        $app = require __DIR__.'/../../../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();
        return $app;
    }

}