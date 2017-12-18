<?php

namespace Sleefs\Test;

use Illuminate\Foundation\Testing\TestCase ;
use Illuminate\Contracts\Console\Kernel;

use Siba\QA\Evento\Evento;
use Siba\QA\Evento\EventoRepo;



class EventoAPITest extends TestCase {

	public function setUp(){
        parent::setUp();
    }


    public function testGetEventoFromAPI(){
        $eventoRepo = new EventoRepo();
        $evt = $eventoRepo->get('271191433');
        $this->assertEquals("Eventos",$evt->title);
        $this->assertEquals("968",$evt->channel()->id);

    }



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

	/* Preparing the Test */
	public function createApplication(){
        $app = require __DIR__.'/../../../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();
        return $app;
    }

}