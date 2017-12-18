<?php

namespace Siba\QA\Error;
use App\Base\IBaseRepo;
use Siba\QA\Error\Error;

class ErrorRepo implements IBaseRepo{	
	
	public function create($data){
		$error = new Error($data);
		return $error;
	}

	public function get($id){

		$error = Error::find($id);
		return $error;
	}

	public function find($data){

		$errores = Error::whereRaw($data)->get();
		return $errores;
	}

	public function save($error){

		$error->save();
		return $error;

	}

}