<?php

namespace Sleefs\Test;

use Illuminate\Foundation\Testing\TestCase ;
use Illuminate\Contracts\Console\Kernel;

use Siba\QA\UsuarioCanales\UsuarioCanales;
use Siba\QA\UsuarioCanales\UsuarioCanalesRepo;



class UsuarioCanalesTest extends TestCase {

	public function setUp(){
        parent::setUp();
        $this->prepareForTests();

        for ($i = 1;$i < 3;$i++){

            $this->setNewUserChnRelations($i,10);
        }
    }


    public function testGetEventoFromAPI(){
        
        $ucRepo = new UsuarioCanalesRepo();
        $usrChn = $ucRepo->find(" usuario='1' ");
        

    }


    /*
    public function testFindEventosFromAPI(){
        $eventoRepo = new EventoRepo();
        $evts = $eventoRepo->find(array("channel"=>"380","limit"=>"0,20"));

    }

    public function testFindEventosFromAPIByFilter(){
        $eventoRepo = new EventoRepo();
        $evts = $eventoRepo->find(array("filter"=>"%5B%7B%22lc%22%3A%22%22%2C%22ele%22%3A%5B%7B%22field%22%3A%22nombre%22%2C%22operator%22%3A%22like%22%2C%22value%22%3A%22%25Noticiero+90%25%22%7D%5D%7D%5D","limit"=>"0,20"));
        //var_dump($evts);
        $this->assertEquals(1800,$evts[0]->duration);

    }
    */


    /*

        Private functions

    */

    private function setNewUserChnRelations($idusr,$qtychn){

        $idchn = 380;
        $ucRepo = new UsuarioCanalesRepo();

        for ($i = 1;$i < $qtychn; $i++){

                $uc = $ucRepo->create(array("usuario"=>'1',"canal"=>'380'));
                $ucRepo->save($uc);
                $idchn++;
        }

    }



	/* Preparing the Test */
	public function createApplication(){
        $app = require __DIR__.'/../../../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();
        return $app;
    }


    /**
     * Migrates the database and set the mailer to 'pretend'.
     * This will cause the tests to run quickly.
     */
    private function prepareForTests()
    {

        \Artisan::call('migrate');
    }


}