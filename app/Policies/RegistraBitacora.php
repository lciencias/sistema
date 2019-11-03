<?php

namespace sistema\Policies;
use Illuminate\Http\Request;
use sistema\Http\Requests;
use Logger;


class RegistraBitacora 
{
    private $antes;
	private $despues;
	private $nombre_registro;
	private $ip;
	private $campoBase;
	private $log;
    
	function __construct($accion,$antes,$despues,$campoBase){
		$path = public_path();
		Logger::configure ( $path.'/xml/config.xml' );
		$this->log = \Logger::getLogger ( 'main' );
		$this->nombre_registro = $this->antes = $this->despues = $this->ip = '';
		$this->procesaInformacion($accion,$antes,$despues,$campoBase);
	}
	
	private function procesaInformacion($accion,$antes,$despues,$campoBase){
		self::obtenIp();
		if($accion == Constantes::ACCION_ALTA)
			self::nuevo($despues,$campoBase);
		else
			self::comparador($antes,$despues,$campoBase);	
	}

	private function comparador($antes,$despues,$campoBase){
		$tmpAnt = $tmpDes = array();
		if(count($despues) > 0){
			foreach($despues as $key => $value){
				if($key == $campoBase){
					$this->nombre_registro = $value;
				}
				if($value != $antes[$key]){
					$tmpDes[] = "{".$key." : ".$value."}";
					$tmpAnt[] = "{".$key." : ".$antes[$key]."}";
				}				
			}
			
			if($this->nombre_registro == null || $this->nombre_registro == '' && $despues[$campoBase] != null && $despues[$campoBase] != '') {
				$this->nombre_registro = $despues[$campoBase];
			}
				
			if(count($tmpDes) == 0){
				$this->despues = Constantes::SINCAMBIOS;
			}else{
				$this->despues = implode(' ',$tmpDes);
			}
			if(count($tmpAnt) == 0){
				$this->antes   = Constantes::SINCAMBIOS;
			}else{
				$this->antes   = implode(' ',$tmpAnt);
			}
		}
	}
	
	private function nuevo($despues,$campoBase){		
		$tmp = array(); 
		if(count($despues) > 0){
			foreach($despues as $key => $value){
				if($key == $campoBase){
					$this->nombre_registro = $value;
				}
				$tmp[] = "{".$key." : ".$value."}";
			}
			$this->despues = implode(' ',$tmp);			
		}
	}
	
	private function obtenIp(){
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$this->ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$this->ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$this->ip = $_SERVER['REMOTE_ADDR'];
		}
	}
	
		
	public function getNombreRegistro(){
		return $this->nombre_registro;
	}
	
	public function getAntes(){
		return $this->antes;
	}
	
	public function getDespues(){
		return $this->despues;
	}	
	
	public function getIp(){
		return $this->ip;
	}	
}
