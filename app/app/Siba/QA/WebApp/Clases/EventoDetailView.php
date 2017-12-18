<?php

namespace Siba\QA\WebApp\Clases;
use Siba\QA\Evento\Evento;


class EventoDetailView {

	private $evt,$timIni;

	public function __construct(Evento $evt){

		$this->evt = $evt;
		$this->timIni = strtotime($this->evt->begin);

	}




	public function legibleTime(){
		
		$mins;
		$mins = date("i",$this->timIni);
		if ($mins <= 9){

			$mins = "0".$mins;

		}
		return date("H:i",$this->timIni);

	}


	/*
	public function defineIfReported(){

		//console.log(this.props.reportesIndexes[]);
		if (typeof this.props.reportesIndexes[this.props.prg.id] != 'undefined' && this.props.reportesIndexes[this.props.prg.id] != null){
			this.isReported = true;
			this.reporte = this.props.state.reportes[this.props.reportesIndexes[this.props.prg.id][1]];
		}
		

	}
	*/



	public function render(){

		$htmlToRet = '<div class="evento-detalle">
					<h3>
						'.$this->evt->channel()->name.'
						<br />
						Hora: '.$this->legibleTime().'
					</h3>
					<h2>'.$this->evt->title.'</h2>
				</div>';

		return $htmlToRet;
	}


}