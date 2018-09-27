<?php

namespace Siba\QA\Cliente;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;


class Cliente extends Model
{
    //
    protected $fillable = ['id','name','pais'];
    protected $clienteCanales = '';


    public function __construct(array $attributes = [],Collection $clienteCanales = null){

    	parent::__construct($attributes);
       	$this->clienteCanales = $clienteCanales;

    }


    public function setClienteCanales(Collection $clienteCanales){
    	$this->clienteCanales = $clienteCanales;
    }

    public function getClientesCanales(){
    	return $this->clienteCanales;
    }
    
}
