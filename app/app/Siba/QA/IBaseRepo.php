<?php

namespace Siba\QA;

Interface IBaseRepo{
	
	/* 
		Define las mismas funciones de recuperacion de Eloquent
	*/
	public function create($entity);	
	public function get($id);
	public function find($data);
	public function save($entity);

}