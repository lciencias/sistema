<?php

namespace sistema\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Logger;
use sistema\Models\User;
use sistema\Policies\Constantes;

class EnviaEmailAltaUsuarioJob  implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $pass;
    private $log; 
    /**
     * Create a new job instance.
     *
     * @return void
     */
	public function __construct(User $user, $pass)
   	{
   		$this->user = $user;
   		$this->pass = $pass;
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
    	$this->log->info("Se envia mail de acceso al usuario: ". $this->user->email);
    try {
    		
	    	$data = array('name' =>$this->user->name, 'password'=>$this->pass, 'email' => $this->user->email, 'url' => Constantes::URL_ACCESO);
	    	$template = 'seguridad.usuario.emailTemplates.nuevo';	    	
	    	Mail::send($template, $data, function($message) {
	    		try {
	    			$titulo   = 'Alta de Usuario.';
	    			$message->to($this->user->email, $this->user->name)->subject($titulo);
	    			$message->from(Constantes::MAIL_FROM,Constantes::MAIL_REMITENTE);
	    			$this->log->info("Exito al enviar mail de acceso al usuario: ". $this->user->email);
	    		} catch ( \Exception $e ) {
    				$this->log->error("Error al enviar mail de acceso al usuario: ". $e->getMessage());
    				throw $e;
    			}
	    		
	    	});
    	} catch ( \Exception $e ) {
    		$this->log->error($e->getMessage());
			throw new \Exception('Error al enviar el Email '.$e);
    	}
    }
    
    public function failed(\Exception $exception)
    {
    	throw new \Exception('Error en job '.$e);
    }
}