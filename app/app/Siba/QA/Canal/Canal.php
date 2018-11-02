<?php

namespace Siba\QA\Canal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;


class Canal extends Model
{
    //
    protected $fillable = ['id','name','desc','prod_company'];
    protected $events = '';


    public function __construct(array $attributes = [],Collection $events = null){

    	parent::__construct($attributes);
       	$this->events = $events;

    }


    public function setEvents(Collection $events){
    	$this->events = $events;
    }

    public function getEvents(){
    	return $this->events;
    }
    
}
