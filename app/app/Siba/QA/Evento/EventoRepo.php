<?php

namespace Siba\QA\Evento;

use Siba\QA\Evento\Evento;
use Siba\QA\Canal\Canal;
use App\Base\IBaseRepo;
use App\Helpers\Curl\Curl;
use Illuminate\Database\Eloquent\Collection;

class EventoRepo implements IBaseRepo{	

	//protected $apiUrl = 'https://apistd.siba.com.co/api/events';//Production
	protected $apiUrl = 'http://devstd.siba.com.co/api/events';//Dev
	
	public function create($data){

		$evento = new Evento ($data);
		$dataChn = (array) $data['channel'];
		$chn = new Canal($dataChn);
		$evento->setChannel($chn);
		return $evento;
	}

	public function get($id){

		$data = (array) json_decode(Curl::urlGet($this->apiUrl.'/'.$id));
		if (isset($data["code"]) && $data["code"] == '404'){
			return null;
		}
		$evento = $this->create($data);
		return $evento;
	}

	public function find($data){
		$collection = Collection::make();
		$reqQuery = '';
		if (count($data)>0){
			foreach ($data as $key=>$val){
				$reqQuery .= $key."=".$val."&";
			}
			$reqQuery = preg_replace("/&$/","",$reqQuery);
		}
		

		//clock()->startEvent('get-eventos-ws', "Llamando microservicio eventos");
		//clock()->info("Eventos URL: ".$this->apiUrl.'?'.$reqQuery);
		$data = (array) json_decode(Curl::urlGet($this->apiUrl.'?'.$reqQuery));
		//clock()->endEvent('get-eventos-ws');


		if (count($data['events']) > 0){
			for ($i=0;$i<count($data['events']);$i++){
				$evtRaw = (array) $data['events'][$i];
				$evt = $this->create($evtRaw);
				$collection->add($evt);
			}
		}
		return $collection;
	}

	public function save($canal){



	}

}
