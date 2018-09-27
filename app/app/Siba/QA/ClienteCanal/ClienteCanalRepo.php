<?php

namespace Siba\QA\ClienteCanal;

use Siba\QA\ClienteCanal\ClienteCanal;
use App\Base\IBaseRepo;
use App\Helpers\Curl\Curl;
use Illuminate\Database\Eloquent\Collection;

class ClienteCanalRepo implements IBaseRepo{


	//protected $apiUrl = 'https://apistd.siba.com.co/api/clients';
	protected $apiUrl = 'http://devstd.siba.com.co/api/clients';
	//protected $apiUrl = 'http://std-dev2:7000/api/clients';
	protected $idClient;


	public function setClient($idClient){
		$this->idClient = $idClient;
	}
	
	public function create($data){

		$canal = new ClienteCanal ($data);
		return $canal;
	}


	public function get($id){

		$data = (array) json_decode(Curl::urlGet($this->apiUrl.'/'.$this->idClient."/channels"));
		//var_dump($data);
		if (isset($data["code"]) && $data["code"] == '404'){
			return null;
		}
		if (count($data)>0){
			$clienteCanal = $this->create($data);
			return $clienteCanal;
		}
		else{
			return null;
		}
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
		clock()->startEvent('get-clientecanales-ws', "Llamando microservicio canales");
		clock()->info("Canales del cliente URL: ".$this->apiUrl.'/'.$this->idClient."/channels".'?'.$reqQuery);
		$data = (array) json_decode(Curl::urlGet($this->apiUrl.'/'.$this->idClient."/channels".'?'.$reqQuery));
		clock()->endEvent('get-clientecanales-ws');
		//print_r($data);
		//return 'MMMMMM';
		
		if (count($data['channels']) > 0){
			for ($i=0;$i<count($data['channels']);$i++){
				$chnRaw = (array) $data['channels'][$i];
				$chn = $this->create($chnRaw);
				$collection->add($chn);
			}
		}		
		return $collection;

	}

	public function save($clienteCanal){

	}

}