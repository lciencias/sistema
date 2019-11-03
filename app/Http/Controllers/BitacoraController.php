<?php

namespace sistema\Http\Controllers;



class BitacoraController extends Controller
{
	private $accion;
	private $tipoObjeto;
	private $idRegistro;
	private $idModulo;
	private $nmModulo;
	private $objetoAnterior;
	private $objetoDespues;
    
	function __construct($accion,$objetoAnterior,$objetoDespues,$idRegistro){
		$this->accion 		  = $accion;
		$this->objetoAnterior = $objetoAnterior;
		$this->objetoDespues  = $objetoDespues;
		$this->idRegistro 	  = $idRegistro;
		$this->procesaInformacion();
	}
	
	private function procesaInformacion(){
		echo"<pre>";
		print_r($this->objetoDespues);
		echo"</pre>";
		die();
	}
	
}
