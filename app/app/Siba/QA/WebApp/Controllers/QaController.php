<?php
namespace Siba\QA\WebApp\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Siba\QA\Canal\Canal;
use Siba\QA\Canal\CanalRepo;
use Siba\QA\Evento\EventoRepo;
use Siba\QA\Evento\Evento;
use Siba\QA\Reporte\ReporteRepo;
use Siba\QA\Reporte\Reporte;
use Siba\QA\Error\ErrorRepo;
use Siba\QA\Error\Error;
use Siba\QA\WebApp\views\EventoView;
use App\Helpers\LastOclockTimeDefiner;
use Siba\QA\UsuarioCanales\UsuarioCanales;
use Siba\QA\UsuarioCanales\UsuarioCanalesRepo;

use App\User;

class QaController extends BaseController{


	function __construct(){

		$this->middleware('auth');

	}


	function index(Request $request){


		$strTimeBase = $request->input("tb","now");	
		$qString = $request->input("q","all");
		$qBack = base64_encode('tb='.$strTimeBase."&q=".$qString);
		$user = Auth()->user();
		//print_r($user->id);
		//return "";


		$lastOclockTimeDefiner	= new LastOclockTimeDefiner();
		$timeBase = strtotime($strTimeBase);
		$timeBase = $lastOclockTimeDefiner->defineLastOclockTime($timeBase);

		/* Define los links de "adelante y atrás" */
		if ($strTimeBase == 'now'){

			$linksArrow = array('back'=>'tb=-15mins&q='.$qString,'next'=>'tb=15mins&q='.$qString);

		}
		else{

			preg_match("/^([\-0-9]{2,4})/",$strTimeBase,$actualMins);
			$linksArrow = array('back'=>($actualMins[0] - 15),'next'=>($actualMins[0] + 15));
			if ($linksArrow['back'] == 0){
				$linksArrow['back'] = 'tb=now&q='.$qString;
			}
			else {
				$linksArrow['back'] = "tb=".$linksArrow['back']."mins&q=".$qString;	
			}
			if ($linksArrow['next'] == 0){
				$linksArrow['next'] = 'tb=now&q='.$qString;
			}
			else{
				$linksArrow['next'] = "tb=".$linksArrow['next']."mins&q=".$qString;	
			}
		}
		//echo date("Y-m-d H:i:s",$timeBase)."<br />";
		//echo $timeBase."<br /><br />";
		//return "";
		$canalRepo = new CanalRepo();
        $eventoRepo = new EventoRepo();
        $canalFilters = array('filter'=>'');
        if ($qString != 'all'){        	

			$filtroCanales = '[{"lc":"","ele":[{"field":"nombre","operator":"like","value":"%'.$qString.'%"}]}]';
			$filtroChnUrlEncoded = urlencode ($filtroCanales);
        	$filtro = array('filter'=>$filtroChnUrlEncoded,"limit"=>"0,5",'fields'=>'id,name');
        }
        else{

        	/* Determina si el usuario tiene canales asociados */
        	$ucRepo = new UsuarioCanalesRepo();
        	$ucanales = $ucRepo->find(" usuario='".$user->id."' ");
        	$filterIdCanalesEncoded = '';
        	if ($ucanales->count()>0){

        		$filterIdCanales = '[{"lc"  : "","ele" :[';
        		$ctrlFirstField = 1;
        		foreach ($ucanales as $usuarioCanal){

        			if ($ctrlFirstField == 1)
        				$filterIdCanales .= '{"field":"idcanal","operator":"=","value":"'.$usuarioCanal->canal.'"},';
        			else
        				$filterIdCanales .= '{"field":"idcanal","operator":"=","value":"'.$usuarioCanal->canal.'","lc":"or"},';
        			$ctrlFirstField++;
        		}
        		$filterIdCanales = preg_replace("/,$/","",$filterIdCanales);
        		$filterIdCanales .= ']}]';
        		$filterIdCanalesEncoded = urlencode ($filterIdCanales);

        	}

        	if ($filterIdCanalesEncoded == '')
        		$filtro = array("limit"=>"0,5",'fields'=>'id,name');
        	else
        		$filtro = array("limit"=>"0,5",'fields'=>'id,name','filter'=>$filterIdCanalesEncoded);
        }
        
		$canales = $canalRepo->find($filtro);


		//$dateIni = strtotime("-1h",$timeBase);
		//$dateEnd = strtotime("+1h",$timeBase);

		$dateIni = $timeBase + 10800;
		$dateEnd = $timeBase - 10800;

		$dateIniStr = date("Y-m-d H:i:s",$dateIni);
		$dateEndStr = date("Y-m-d H:i:s",$dateEnd);

		$programacion = array();

		//print_r($canales);
		//return "";

		//Instancia el objeto que sirve de generador de objetos tipo Reporte
		$reporteRepo = new ReporteRepo();
		
		foreach ($canales as $canal){
			//$eventos = '';
			//echo $canal->id."\n<br />";
			//echo $canal->name."\n<br />";
			
			
			$evts = array();
			$filter = '[{"lc":"","ele":[{"field": "fecha_hora","operator": ">=","value": "'.$dateEndStr.'"}, {"field": "begin","operator": "<","value": "'.$dateIniStr.'","lc" : "and"}]},{"lc": "&&","ele":[{"field": "channel","operator": "=","value": "'.$canal->id.'"}]}]';
			$filterUrlEncoded = urlencode ($filter);
			//print_r(json_decode($filter));
			/*
			$filtro = array('filter'=>'[{"lc":"","ele":[{"field": "fecha_hora","operator": ">=","value": "2017-09-01 00:00:00"}, {"field": "fecha_hora","operator": "<","value": "2017-09-01 23:59:59","lc" : "and"}]},{"lc": "&&","ele":[{"field": "idcanal","operator": "=","value": "380"}]}]','limit'=>'0,5');
			*/
			$eventos = $eventoRepo->find(array('filter'=>$filterUrlEncoded));
			
			//print_r($eventos);


			//echo '%5B%7B%22lc%22%3A%22%22%2C%22ele%22%3A%5B%7B%22field%22%3A+%22fecha_hora%22%2C%22operator%22%3A+%22%3E%3D%22%2C%22value%22%3A+%22'+$dateIniStr+'%22%7D%2C+%7B%22field%22%3A+%22fecha_hora%22%2C%22operator%22%3A+%22%3C%22%2C%22value%22%3A+%22'+$dateEndStr+'%22%2C%22lc%22+%3A+%22and%22%7D%5D%7D%2C%7B%22lc%22%3A+%22%26%26%22%2C%22ele%22%3A%5B%7B%22field%22%3A+%22idcanal%22%2C%22operator%22%3A+%22%3D%22%2C%22value%22%3A+%22'+$canal->id+'%22%7D%5D%7D%5D';

			//print_r($eventos);
			//echo "\n<br />";
			foreach ($eventos as $evt){

				
				$reportes = array();
				$reportes = $reporteRepo->find(" evento='".$evt->id."' ");
				$evt->setReportes($reportes);
				$evtView = new EventoView($evt,$timeBase,$qBack);
				//$reportes = 
				array_push($evts,$evtView);

			}
			if (count($evts) > 0){
				array_push($programacion,array('chn'=>$canal,'evts'=>$evts));
			}
		}
		
		/*
		print_r($programacion);
		return "MMM";
		*/
		return view("index",["programacion"=>$programacion,"timebase"=>$timeBase,"linksArrow"=>$linksArrow,"q"=>$qString]);
		//return "Hola Mundo...";
	}


}