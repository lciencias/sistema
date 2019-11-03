<?php namespace sistema\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use sistema\Models\Talento;
use sistema\Policies\Constantes;
use sistema\Repositories\Eloquent\Repository;

class TalentoRepository extends Repository {

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    private $campoBase = "nombre";
    private $controller = Constantes::CONTROLLER_TALENTO;
    private $moduloId = Constantes::MODULO_ID_TALENTO;
	
    function model()
    {
        return 'sistema\Models\Talento';
    }
    
    
    function saveTalento(Request $request) {
    	DB::beginTransaction ();
    	try {
    		 $talento = new Talento;
    		 $talento->nombre =  trim($request->get('nombreTalento')); 
    		 $talento->definicion = trim($request->get('definicionTalento')); 
       		 $talento->activo = true;
       		 $talento->save();         		 
       		 
       		 //Bitacora
       		 $this->insertaBitacora(Constantes::ACCION_ALTA, null, $talento->getAttributes(), $talento->idtalento,$this->controller,$this->moduloId,Session::get('idUser'),array(),$this->campoBase);
       		 
    		DB::commit ();
    	} catch ( \Exception $e ) {
    		DB::rollback ();
    		throw new \Exception('Error al guardar Talento: ' . $e);
    		 
    	}
    	 return $talento;
    }
    
    function updateTalento(Request $request,$id) {
    	DB::beginTransaction ();
    	try {
    		 $talento  = $this->getTalento($id);
    		 $talentoA = clone $talento;
       		 $talento->nombre 				 = $request->get('nombreTalento');
       		 $talento->definicion 			 = $request->get('definicionTalento');
       		 $talento->update();    
       		 //Bitacora
       		 $this->insertaBitacora(Constantes::ACCION_ACTUALIZAR, $talentoA->getAttributes(), $talento->getAttributes(), $talento->idtalento,$this->controller,$this->moduloId,Session::get('idUser'),array(),$this->campoBase);
       		 
       		 
    		 DB::commit ();
    	} catch ( \Exception $e ) {
    		DB::rollback ();
    		throw new \Exception('Error al actualizar Talento: ' . $e);
    	}    
    }
    
    /**
     * Metodo para eliminar una talento
     * @param  $id
     * @throws \Exception
     */
     function eliminaTalento($id){
     	DB::beginTransaction ();
     	try {
     		$talento = $this->getTalento($id);
     		$talentoA = clone $talento;
     		$talento->activo = 0;
     		$talento->update();
     		//Bitacora
     		$this->insertaBitacora(Constantes::ACCION_ELIMINAR, $talentoA->getAttributes(), $talento->getAttributes(), $talento->idtalento,$this->controller,$this->moduloId,Session::get('idUser'),array(),$this->campoBase);     		 
     		DB::commit ();
    	} catch ( \Exception $e ) {
    		DB::rollback ();
    		throw new \Exception('Error al actualizar Talento: ' . $e);
    	}         		
     }
    
     /**
      * Metodo para activar una talento
      * @param int $id
      * @throws \Exception
      */
    function activaTalento($id){
    	if((int) $id > 0){
    		DB::beginTransaction ();
    		try{
     			$talento = $this->getTalento($id);
     			$talentoA = clone $talento;
     			$talento->activo = 1;
     			$talento->update();
     			//Bitacora
     			$this->insertaBitacora(Constantes::ACCION_ACTIVAR, $talentoA->getAttributes(), $talento->getAttributes(), $talento->idtalento,$this->controller,$this->moduloId,Session::get('idUser'),array(),$this->campoBase);     			
    			DB::commit ();
    		}
    		catch ( \Exception $e ) {
    			DB::rollback ();
    			throw new \Exception('Error al restablecer a la Talento con id:'.$id.' -> ' . $e);
    		}
    	}
    }
    
    
    
    /**
     * Metodo que regresa un objeto de tu
     * @param int $id
     */
    function getTalento($id){
    	return  Talento::findOrFail ( $id );
    }
    
    
    /**
     * Metodo para validar si el nombre de la talento ya se encuentra registrado
     * @param String $nombre
     * @param String $idtalento
     * @return boolean
     */
    function validaNombre($nombre, $idtalento){
    	$existe = false;
    	$results = DB::table('talento')->where('nombre','=',$nombre);
    	if($idtalento != null && $idtalento != '')
    		$results = $results->where('idtalento','!=',$idtalento);
    
    	$results = $results->get();
    	if(count($results) > 0){
    		$existe = true;
    	}
    	return $existe;
    }

    
    
}