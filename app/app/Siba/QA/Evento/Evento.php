<?php

namespace Siba\QA\Evento;

use Illuminate\Database\Eloquent\Model;
use Siba\QA\Canal\Canal;

/*

Evento contiene datos que en json tienen esta forma:

{
            "id": 197417632,
            "title": "France 24",
            "begin": "2017-11-01 10:00:00",
            "sinop": "Programación France 24",
            "channel": {
                "id": 1031,
                "name": "FRANCE 24"
            },
            "duration": "3600"
        }


*/

class Evento extends Model
{
    //
    protected $fillable = ['id','title','begin','sinop','duration'];
    protected $channel = '';
    public $reportes = array();


    public function setChannel(Canal $canal){
    	$this->channel = $canal;
    }

    public function channel(){
    	return $this->channel;
    }


    public function setReportes($reportes){

        $this->reportes = $reportes;

    }

}
