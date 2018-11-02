<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \Siba\QA\Reporte\ExcelReporterCreator;
use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception;

class CreateAndSendExcelReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sibaqa:excelreport {dateini} {datefin} {emailsto?} {--nosend}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera un informe de los reportes de calidad de contenido, lo guarda en un archivo de excel';

    protected $excelReporter;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(\Siba\QA\Reporte\ExcelReporterCreator $reporter)
    {
        parent::__construct();
        $this->excelReporter = $reporter;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        try{
            $params = array(
                'fechaini'=>$this->argument('dateini'),
                'fechafin'=>$this->argument('datefin'),
            );
            $reportFile = $this->excelReporter->create($params);
            
            $this->info('Se ha generado un nuevo archivo de reporte en '.$reportFile);

            if (($this->options('nosend') != null) && $this->options('nosend')){
                if (null !== $this->argument('emailsto') && $this->argument('emailsto') != ''){

                    $arrEmailsTo = preg_split("/\,/",$this->argument('emailsto'));
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

                    foreach ($arrEmailsTo as $emailTo){

                        $mailer->addAddress(trim($emailTo));               // Name is optional

                    }

                    $mailer->addAttachment($reportFile);
                    $mailer->Subject = 'Reporte SIBA QA ('.date("Y-m-d H:i:s").")";
                    $mailer->Body    = 'Se adjunta el reporte de QA para el periodo: '.$this->argument('dateini').' al '.$this->argument('datefin');

                    
                    
                    if(!$mailer->send()) {
                        $this->error('Se ha producido un error generando el reporte: '.$mailer->ErrorInfo);
                    } else {
                        $this->error('Se ha despachado el email con los reportes');
                    }                                 // TCP port to connect to


                }
            }
        }
        catch(\Exception $e){

            $this->error('Se ha producido un error generando el reporte: '.$e->getMessage());
        }
    }
}
