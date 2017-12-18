<?php

namespace Siba\QA\Canal;

use Siba\QA\Canal\Canal;
use App\Base\IBaseRepo;
use App\Helpers\Curl\Curl;
use Illuminate\Database\Eloquent\Collection;

class CanalRepo implements IBaseRepo{


	protected $apiUrl = 'https://apistd.siba.com.co/api/channels';
	
	public function create($data){

		$canal = new Canal ($data);
		return $canal;
	}


	public function get($id){

		$data = (array) json_decode(Curl::urlGet($this->apiUrl.'/'.$id));
		//var_dump($data);
		if (isset($data["code"]) && $data["code"] == '404'){
			return null;
		}
		if (count($data)>0){
			$canal = $this->create($data);
			return $canal;
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
		$data = (array) json_decode(Curl::urlGet($this->apiUrl.'?'.$reqQuery));

		if (count($data['channels']) > 0){
			for ($i=0;$i<count($data['channels']);$i++){
				$chnRaw = (array) $data['channels'][$i];
				$chn = $this->create($chnRaw);
				$collection->add($chn);
			}
		}
		return $collection;

	}

	public function save($canal){

	}

}