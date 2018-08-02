<?php

namespace Siba\QA\UsuarioCanales;
use Siba\QA\UsuarioCanales\UsuarioCanales;
use App\Base\IBaseRepo;

class UsuarioCanalesRepo implements IBaseRepo{	
	
	public function create($data){

		$usuarioCanales = new UsuarioCanales($data);
		return $usuarioCanales;

	}

	public function get($id){

		$usuarioCanales= UsuarioCanales::find($id);
		return $usuarioCanales;

	}

	public function find($data){

		$usuarioCanales = UsuarioCanales::whereRaw($data)->get();
		return $usuarioCanales;

	}

	public function save($usuarioCanales){

		$usuarioCanales->save();
		return $usuarioCanales;
	}

}