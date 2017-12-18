<?php

namespace Sleefs\Test;

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

    }

	/* Preparing the Test */
	public function createApplication(){
        $app = require __DIR__.'/../../../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();
        return $app;
    }

}