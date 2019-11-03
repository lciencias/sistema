<?php

namespace sistema\Http\Controllers;

use Illuminate\Http\Request;
use sistema\Http\Requests;
use sistema\Policies\Constantes;

class CargaArchivos extends Controller
{
	private $extensionImages;
	private $request;
	private $exito;
	private $id;
	private $tamano;
	public function __construct(Request $request, $id){
		parent::__construct();
		$this->tamano = Constantes::getMaxUpload();
    	$this->extensionImages = array('jpg','jpeg','png','gif','bmp');
    	$this->exito = false;
    	$this->request = $request;
    	$this->id = $id;
    	$this->carga();
    }
    
    public function carga(){
    	$imagen = "";
    	$imaSize = 0;
   		try{
	    	if(trim($this->request->file('image')->getClientOriginalName()) != ''){
	    		$imageExt = $this->request->file('image')->getClientOriginalExtension();
	    		$imageNom = $this->request->file('image')->getClientOriginalName();
	    		$imaSize  = $this->request->file('image')->getClientSize();
	    		if($imaSize < $this->tamano){
		    		$path     = base_path() . Constantes::UPLOAD_DIR . $this->id;
		    		if (!file_exists($path)) {
						mkdir($path, 0777, true);
					}
	    		//	$imagen   = $imageNom.".".$imageExt;
	    			$imagen   = $this->id . "-" . $imageNom;
	    			if(file_exists($path.$imagen)){
		    			@unlink($path.$imagen);
	    			}
	    			if(in_array($imageExt,$this->extensionImages)){
		    			$this->request->file('image')->move($path, $imagen);
						$this->exito = 1;
	    			}
	    		}else{
					$this->log->debug ("Archivo:  ".$imageNom."  tamano  ".$imaSize);
	    			throw new \Exception('Error el tamaño del archivo es superidor a los 35 megas.');
	    		}
	    	} 
   		}
   		catch(\Exception $e){
   			$this->log->error ($e);
   			throw new \Exception('Error al cargar la Imagen: ' . $e);
   		}
    }
    
	public function obtenExito(){
		return $this->exito;
	}
}
