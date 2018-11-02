<?php
namespace Siba\QA\Reporte\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Siba\QA\Reporte\ReporteRepo;
use Siba\QA\Error\ErrorRepo;
use App\User;
use Illuminate\Support\Facades\Auth;

use \Siba\QA\Reporte\ExcelReporterCreator;
use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception;


class InformeWebController extends BaseController{

	protected $excelReporter;
	public function __construct(\Siba\QA\Reporte\ExcelReporterCreator $reporter){
		$this->excelReporter = $reporter;
	}


	function index(){
		return view("informe");
	}


	function create(Request $request){

		$user = User::find(Auth::user()->id);
		$params = array(
                'fechaini'=>$request->input('dateini'),
                'fechafin'=>$request->input('datefin'),
            );
        $reportFile = $this->excelReporter->create($params);
		
		try{
            $params = array(
                'fechaini'=>$request->input('dateini'),
                'fechafin'=>$request->input('datefin'),
            );
            $reportFile = $this->excelReporter->create($params);
            
            $mailer = new PHPMailer();
            $mailer->isSMTP();
            $mailer->Host = "port80.smtpcorp.com";
            $mailer->Username = 'mmuriel@siba.com.co';                 // SMTP username
            $mailer->Password = '49421702Mm!';                           // SMTP password
            $mailer->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
            $mailer->Port = '443';  
            $mailer->SMTPAuth = "true";

            $mailer->From = 'mmuriel@siba.com.co';
            $mailer->FromName = 'Mauricio Muriel';
            $mailer->addAddress(trim($user->email));               // Name is optional
            $mailer->addAttachment($reportFile);
            $mailer->Subject = 'Reporte SIBA QA ('.date("Y-m-d H:i:s").")";
            $mailer->Body    = 'Se adjunta el reporte de QA para el periodo: '.$request->input('dateini').' al '.$request->input('datefin');
            if(!$mailer->send()) {
            //if(false){
                //$this->error('Se ha producido un error generando el reporte: '.$mailer->ErrorInfo);
                return response()->json([
                			'data'=>[
                				'error'=>true,
                				'msg'=>'Se ha producido un error despachando el informe a la dirección de correo '.$user->email.' el informe: ',//.$mailer->ErrorInfo,
                			]
            			]);
            } 
            else {

                return response()->json([
                			'data'=>[
                				'success'=>true,
                				'msg'=>'Se ha despachado el informe al correo electrónico: '.$user->email,
                			]
            			]);
            }
        }
        catch(\Exception $e){

            return response()->json([
                			'data'=>[
                				'error'=>true,
                				'msg'=>'Se ha producido un error generando el informe, '.$e->getMessage(),
                			]
            			]);
        }

	}


}