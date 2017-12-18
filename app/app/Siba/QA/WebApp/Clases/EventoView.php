<?php

namespace Siba\QA\WebApp\views;
use Siba\QA\Evento\Evento;


class EventoView {

	private $evt,$timeBase,$duration,$timIni;
	private $styleBox = array(
		'width'=>10,
		'left'=>0,
		'childBoxWidth' => 10,
		'childBoxleft' => 0,
	);

	public function __construct(Evento $evt,$timeBase){

		$this->timeBase = $timeBase;
		$this->evt = $evt;
		$this->timIni = strtotime($this->evt->begin);

	}



	public function defineComponentWidth(){

		$duraEvt = $this->evt->duration;
		$duraEvt =  (int)($duraEvt / 60);
		//Regla de tres para determinar el ancho de la caja
		$this->styleBox['width'] = (int)(($duraEvt * 100) / 30);
	}


	public function defineComponentPosition(){
		$diffFromStart = ($this->timeBase - $this->timIni) * (-1);
		//echo date("H:i",$this->timeBase)." - ".date("H:i",$this->timIni).": ".$diffFromStart."<br />";
		$diffFromStart = (int)(($diffFromStart)/60);//Convierte la diferencia en minutos
		$this->styleBox['left'] = (($diffFromStart * 100) / 30);
	}


	public function adjustChildBoxes(){

		//Si el evento finaliza en el tiempo disponible de "pantalla" pero ya ha iniciado.
		if (($this->styleBox['left'] + $this->styleBox['width']) > 0 && $this->styleBox['left'] < 0){
			$this->styleBox['childBoxWidth'] = $this->styleBox['width'] + $this->styleBox['left'];
			$this->styleBox['childBoxleft'] = $this->styleBox['width'] - $this->styleBox['childBoxWidth'];
		}
		//Si el evento finaliza más allá del tiempo de pantalla y ya ha iniciado.
		else if (($this->styleBox['left'] + $this->styleBox['width']) > 100 && $this->styleBox['left'] < 100 && $this->styleBox['left'] > 0){

			$this->styleBox['childBoxWidth'] = 100 - $this->styleBox['left'];

		}
		//En cualquier otro caso, que sería inicia y termina antes de la pantalla, o inicia en el tiempo de pantalla
		else{

			$this->styleBox['childBoxWidth'] = $this->styleBox['width'];
		}
		//print_r($this->styleBox);
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

		$this->defineComponentWidth();
		$this->defineComponentPosition();
		$this->adjustChildBoxes();
		$qtyRep = count($this->evt->reportes);

		$classType = 'canal__programas__programa';
		$btns = '<div class="canal__programas__programa__btns" style="float:left; width: '.$this->styleBox['childBoxWidth'].'vw; left: '.$this->styleBox['childBoxleft'].'vw"><button class="canal__programas__programa__btns__btnok" data-idprg="'.$this->evt->id.'">Ok</button> <button class="canal__programas__programa__btns__btnerror">Error</button></div>';

		if ($qtyRep > 0){

			if ($this->evt->reportes[($qtyRep - 1)]->tipo == 1){

				$btns = '';
				$classType = 'canal__programas__programa canal__programas__programa--reportedok';

			}
			elseif ($this->evt->reportes[($qtyRep - 1)]->tipo == 0){

				$btns = '';
				$classType = 'canal__programas__programa canal__programas__programa--reportederror';

			}

		}

		$htmlToRet = '<li class="'.$classType.'" style="width: '.$this->styleBox['width'].'vw; left: '.$this->styleBox['left'].'vw" id="box-'.$this->evt->id.'">
					<div class="canal__programas__programa__details" style="float:left; width: '.$this->styleBox['childBoxWidth'].'vw; left: '.$this->styleBox['childBoxleft'].'vw">
						
							<a href="/evento/'.$this->evt->id.'">'.$this->legibleTime().' - '.$this->evt->title.'</a>
						
					</div>
					'.$btns.'
					<div class="clearFloat"></div>
				</li>';

		return $htmlToRet;
	}


}