<?php

namespace Siba\QA\Error;

use Illuminate\Database\Eloquent\Model;
use Siba\QA\Reporte\Reporte;

class Error extends Model
{
    //
    protected $table = 'errores';
    protected $fillable = ['idmd5','tipo','motivo','detalle','desfase','transmitiendo','reporte'];


    public function reporte(){

    	$this->belongsTo('Siba\QA\Reporte\Reporte','reporte');

    }
    

}
