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
        	$filtro = array('filter'=>$filtroChnUrlEncoded,"limit"=>"0,50",'fields'=>'id,name');
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
        		$filtro = array("limit"=>"0,50",'fields'=>'id,name');
        	else
        		$filtro = array("limit"=>"0,50",'fields'=>'id,name','filter'=>$filterIdCanalesEncoded);
        }
        
        /* Determina los canales sobre los que debe realizar la búsqueda */
		$canales = $canalRepo->find($filtro);


		/* Valida si existen canales */
		if ($canales->count() == 0){

			$programacion = array('nodata'=>'no existen canales');
			return view("index",["programacion"=>$programacion,"timebase"=>$timeBase,"linksArrow"=>$linksArrow,"q"=>$qString]);

		}


		//$dateIni = strtotime("-1h",$timeBase);
		//$dateEnd = strtotime("+1h",$timeBase);

		$dateIni = $timeBase - 7280;
		$dateEnd = $timeBase + 7280;

		$dateIniStr = date("Y-m-d H:i:s",$dateIni);
		$dateEndStr = date("Y-m-d H:i:s",$dateEnd);


		//print_r("Inicio: ".date("Y-m-d H:i:s",$dateIni)."\n<br />");
		//print_r("End: ".date("Y-m-d H:i:s",$dateEnd)."\n<br />");
		//print_r("Inicio: ".$dateIni."\n<br />");
		//print_r("End: ".$dateEnd."\n<br />");
		//return("\n<br />MMMM...");

		$programacion = array();
		//Instancia el objeto que sirve de generador de objetos tipo Reporte
		$reporteRepo = new ReporteRepo();
		
		/* Genera la información de los eventos por cada canal */
		$filter = '[{"lc":"","ele":[{"field": "fecha_hora","operator": ">=","value": "'.$dateIniStr.'"}, {"field": "begin","operator": "<","value": "'.$dateEndStr.'","lc" : "and"}]},{"lc": "&&","ele":[';
		$ctrlChn = 0;
		foreach ($canales as $canal){
			$evts = array();
			if ($ctrlChn == 0)
				$filter .= '{"field": "channel","operator":"=","value":"'.$canal->id.'"}';
			else
				$filter .= ',{"lc":"or","field":"channel","operator": "=","value": "'.$canal->id.'"}';
			$ctrlChn++;
		}
		$filter .= ']}]';
		//print_r($filter);
		//return "MMM";
		$filterUrlEncoded = urlencode ($filter);
		$eventos = $eventoRepo->find(array('filter'=>$filterUrlEncoded,'limit'=>'0,350'));
		$evts = array();
		foreach ($eventos as $evt){
			$reportes = array();
			$reportes = $reporteRepo->find(" evento='".$evt->id."' ");
			$evt->setReportes($reportes);
			$evtView = new EventoView($evt,$timeBase,$qBack);
			//$reportes = 
			//print_r($evt->channel()->id);
			if (isset($evts[$evt->channel()->id]))
				array_push($evts[$evt->channel()->id],$evtView);
			else
				$evts[$evt->channel()->id] = array($evtView);
		}

		foreach ($canales as $canal){

			if (isset($evts[$canal->id]) && count($evts[$canal->id]) > 0){
				array_push($programacion,array('chn'=>$canal,'evts'=>$evts[$canal->id]));
			}
			
		}
		//print_r($programacion);
		//return ("");
		return view("index",["programacion"=>$programacion,"timebase"=>$timeBase,"linksArrow"=>$linksArrow,"q"=>$qString]);
	}


}