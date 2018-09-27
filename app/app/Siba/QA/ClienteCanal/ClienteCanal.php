<?php

namespace Siba\QA\ClienteCanal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;


class ClienteCanal extends Model
{
    //
    protected $fillable = ['channel','name','frequency'];


    public function __construct(array $attributes = []){
    	parent::__construct($attributes);
    }

    
}
