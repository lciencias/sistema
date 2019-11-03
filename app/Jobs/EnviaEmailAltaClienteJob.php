<?php

namespace sistema\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Logger;
use sistema\Models\Cliente;
use sistema\Policies\Constantes;
use sistema\Models\User;

class EnviaEmailAltaClienteJob extends Job implements ShouldQueue
{
   use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $cliente;
    protected $usuario;
    protected $log;
    protected $pass;
   	
   	public function __construct(Cliente $cliente,$pass)
   	{
   		$this->cliente = $cliente;
   		$this->pass = $pass;
   		$usuario = User::where('idcliente','=',$this->cliente->idcliente)->get()->first();
   		$this->usuario = $usuario;
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
    		$this->log->debug ("envia mail por alta de cliente: " );
    		
    		
    		$data = array('name' =>$this->usuario->name . " ". $this->usuario->paterno . " " . $this->usuario->materno , 'password'=>$this->pass, 'email' => $this->usuario->email, 'url' => Constantes::URL_ACCESO);
    		$template = 'gestion.cliente.emailTemplates.nuevo';
    		$this->log->debug ("aca");
    		Mail::send($template, $data, function($message) {
    			try {
    				$titulo   = 'Alta de cliente.';
    				$message->to( $this->usuario->email, $this->usuario->name  . " ".  $this->usuario->paterno  . " " .  $this->usuario->materno )->subject($titulo);
	    		     $message->from(Constantes::MAIL_FROM,Constantes::MAIL_REMITENTE);
	    		     $this->log->info("Exito al enviar mail de acceso al usuario: ". $this->usuario->email);
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