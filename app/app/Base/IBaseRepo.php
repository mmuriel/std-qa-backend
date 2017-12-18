<?php

namespace App\Base;

Interface IBaseRepo{
	
	/* 
		Define las mismas funciones básicas tipo CRUD
	*/
	public function create($data);	
	public function get($id);
	public function find($data);
	public function save($entity);

}