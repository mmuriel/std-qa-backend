<?php
namespace Siba\QA\Reporte\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Siba\QA\Reporte\ReporteRepo;
use Siba\QA\Error\ErrorRepo;
use App\User;
use Illuminate\Support\Facades\Auth;


class ReporteWebController extends BaseController{


	function index(){

		return "";
		//return "Hola Mundo...";
	}


	function add(Request $request){


		$user = User::find(Auth::user()->id);
		$repoData = [
			'idmd5' => md5($request->input("evt")."".$request->input("chn")),
			'usuario' => $user->email,
			'tipo' => $request->input("tipo"),
			'evento' => $request->input("evt"),
			'canal' => $request->input("chn"),
			'evento_titulo' => $request->input('evt_titulo'),
			'evento_fechahora' => $request->input('evt_fechahora')
		];

		//return response()->json($repoData);
		$reporteRepo = new ReporteRepo();
		$reporte = $reporteRepo->create($repoData);
		$reporte = $reporteRepo->save($reporte);

		if ($repoData['tipo'] == 0){
			$errorData = [

				'idmd5' => md5($request->input("evt")."".$reporte->id),
				'tipo' => $request->input("error_tipo"),
				'motivo' => $request->input("error_motivo"),
				'detalle' => $request->input("error_detalle"),
				'desfase' => $request->input("error_desfase"),
				'transmitiendo' => $request->input("error_transmitiendo"),
				'reporte' => $reporte->id,

			];
			$errorRepo = new ErrorRepo();
			$error = $errorRepo->create($errorData);
			$error = $errorRepo->save($error);


			return response()->json(['data'=>['reporte'=>$reporte,'error'=>$error]]);
		}

		
		return response()->json(['data'=>['reporte'=>$reporte]]);

	}


}