<?php

namespace Siba\QA\Reporte;

use Illuminate\Database\Eloquent\Model;
use Siba\QA\Error\Error;


/*
	Este objeto modelado como un objeto json tendría la siguiente estructura:
	{
		id : 9392818939,
		idmd5 : hx3b2abb4nadg24m5890qwabd482bsdf,
		usuario : 'mmuriel@siba.com.co',
		tipo: 0,
		evento: 5092898483,
		canal: 380,
	}
*/

class Reporte extends Model{
    //
    protected $table='reportes';
    protected $fillable = array('idmd5','usuario','tipo','evento','canal','evento_titulo','evento_fechahora');
    public function errores(){
    	return $this->hasMany('Siba\QA\Error\Error','reporte');
    }
}
