<?php

namespace sistema\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Logger;
use sistema\Models\Empresa;
use sistema\Policies\Constantes;

class EnviaEmailAltaEmpresaJob extends Job implements ShouldQueue
{
   use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $empresa;
    protected $log;
    protected $pass;
   	
   	public function __construct(Empresa $empresa,$pass)
   	{
   		$this->empresa = $empresa;
   		$this->pass = $pass;
   	}
   	
   	

   	
    public function handle(){
    	try {
    		$path = public_path();
	        $separador= explode('\\',$path);
	        if(count($separador)>1) // si es windows 
	        	Logger::configure ( $path.'\xml\config.xml' );
	        else
	        	Logger::configure ( $path.'/xml/config.xml' );
			$this->log = \Logger::getLogger ( 'main' );
    		$this->log->debug ("envia mail por alta de empresa");
    		$data = array('name' =>$this->empresa->nombre_representante . " ". $this->empresa->paterno_representante . " " . $this->empresa->materno_representante , 'password'=>$this->pass, 'email' => $this->empresa->email_representante, 'url' => Constantes::URL_ACCESO);
    		$template = 'seguridad.empresa.emailTemplates.nuevo';
    		$this->log->debug ("aca");
    		Mail::send($template, $data, function($message) {
    			try {
    				$titulo   = 'Alta de Empresa.';
    				$message->to( $this->empresa->email_representante, $this->empresa->nombre_representante . " ". $this->empresa->paterno_representante . " " . $this->empresa->materno_representante)->subject($titulo);
	    		$message->from(Constantes::MAIL_FROM,Constantes::MAIL_REMITENTE);
    				$this->log->info("Exito al enviar mail de acceso al usuario: ". $this->empresa->email_representante);
    			} catch ( \Exception $e ) {
    				$this->log->error("Error al enviar mail de acceso al usuario: ". $e->getMessage());
    				throw $e;
    			}	
	    	});
    	} catch ( \Exception $e ) {
    		$this->log->error ($e);
    		throw new \Exception($e);
    	}
    }
    
    public function failed()
    {
    	$this->log->error("Error en job");
    }
}