<?php

namespace Siba\QA\UsuarioCanales;

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

class UsuarioCanales extends Model
{
    //
    protected $table='usuarios_canales';
    protected $primaryKey = 'id';
    protected $fillable = array('usuario','canal');
    
}
