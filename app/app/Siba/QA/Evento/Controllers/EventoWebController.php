<?php
namespace Siba\QA\Evento\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

use Siba\QA\Evento\EventoRepo;
use Siba\QA\Evento\Evento;
use Siba\QA\Canal\Canal;
use Siba\QA\Canal\CanalRepo;
use Siba\QA\Reporte\ReporteRepo;
use Siba\QA\Reporte\Repo;
use Siba\QA\Error\ErrorRepo;
use Siba\QA\Error\Error;
use Siba\QA\WebApp\Clases\EventoDetailView;

class EventoWebController extends BaseController{

	function __construct(){

		$this->middleware('auth');

	}

	function index($id,Request $request){


		$qback = $request->input("qback");
		$evtRepo = new EventoRepo();
		$repoRepo = new ReporteRepo();
		$evento = $evtRepo->get($id);
		$reportes = $repoRepo->find("evento='".$id."'");
		$evtView = new EventoDetailView($evento);
		return view("evento",['evento'=>$evento,'reportes'=>$reportes,'evtView'=>$evtView,'qback'=>$qback]);
		//return "Hola Mundo...";
	}


}