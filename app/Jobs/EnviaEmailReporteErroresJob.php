<?php

namespace sistema\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Logger;
use sistema\Policies\Constantes;

class EnviaEmailReporteErroresJob  implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $errores;
    private $log; 
    /**
     * Create a new job instance.
     *
     * @return void
     */
	public function __construct($errores)
   	{
   		$this->errores = $errores;
   	}
   	

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){
    	$path = public_path();
        $separador= explode('\\',$path);
        if(count($separador)>1) // si es windows 
        Logger::configure ( $path.'\xml\config.xml' );
        else
        Logger::configure ( $path.'/xml/config.xml' );
    	$this->log = \Logger::getLogger ( 'main' );
    	
    try {

    		$this->log->info("Se envia mail de los errores generadsos");
           	
           	$data = array('errores' =>$this->errores);
           	$template = 'procesos.jobs.reporteErrores';
           	
           	Mail::send($template, $data, function($message) {
           		try {
           			$titulo   = 'Errores';
           	
           			$message->to(Constantes::EMAIL_SOPORTE);
           	
           			$message->subject($titulo);
           			$message->from(Constantes::MAIL_FROM,Constantes::MAIL_REMITENTE);
           	
           			$this->log->info("Exito al enviar reporte de errores");
           		} catch ( \Exception $e ) {
           			$this->log->error("Error al enviar el reporte de errores: ". $e->getMessage());
           			throw $e;
           		}
           		 
           	});

	      


    	} catch ( \Exception $e ) {
    		$this->log->error($e);
			throw new \Exception('Error al enviar el reporte de errores: '.$e);
    	}
    }
    
    public function failed(\Exception $exception)
    {
    	throw new \Exception('Error en job '.$e);
    }
}