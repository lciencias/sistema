<?php namespace sistema\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use sistema\Models\Ejercicio;
use sistema\Models\TipoEjercicio;
use sistema\Policies\Constantes;
use sistema\Repositories\Eloquent\Repository;

class TipoEjercicioRepository extends Repository {

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    private $campoBase  = "nombre";
    private $controller = Constantes::CONTROLLER_TIPO_EJERCICIO;
    private $moduloId = Constantes::MODULO_ID_TIPO_EJERCICIO;
	
    function model()
    {
        return 'sistema\Models\TipoEjercicio';
    }
    
    
    function saveTipoEjercicio(Request $request) {
    	DB::beginTransaction ();
    	try {
    		 $tipoEjercicio = new TipoEjercicio;
    		 $tipoEjercicio->nombre =  trim($request->get('nombreTipoEjercicio')); 
    		 $tipoEjercicio->descripcion = trim($request->get('descripcionTipoEjercicio')); 
       		 $tipoEjercicio->activo = true;
       		 $tipoEjercicio->save();         		 
       		 
       		 $this->guardaEjercicios($request, $tipoEjercicio->idtipo_ejercicio);
       		 //Bitacora
       		 $this->insertaBitacora(Constantes::ACCION_ALTA, null, $tipoEjercicio->getAttributes(), $tipoEjercicio->idtipoEjercicio,$this->controller,$this->moduloId,Session::get('idUser'),array(),$this->campoBase);
       		 
    		DB::commit ();
    	} catch ( \Exception $e ) {
    		DB::rollback ();
    		throw new \Exception('Error al guardar TipoEjercicio: ' . $e);
    		 
    	}
    	 return $tipoEjercicio;
    }
    
    function updateTipoEjercicio(Request $request,$id) {
    	DB::beginTransaction ();
    	try {
    	    
    	    
    		 $tipoEjercicio  = $this->getTipoEjercicio($id);
    		 $tipoEjercicioA = clone $tipoEjercicio;
       		 $tipoEjercicio->nombre 				 = $request->get('nombreTipoEjercicio');
       		 $tipoEjercicio->descripcion 			 = $request->get('descripcionTipoEjercicio');
       		 $tipoEjercicio->update();   
       		 
       		 
       		 $this->guardaEjercicios($request, $id);
       		 
       		 //Bitacora
       		 $this->insertaBitacora(Constantes::ACCION_ACTUALIZAR, $tipoEjercicioA->getAttributes(), $tipoEjercicio->getAttributes(), $tipoEjercicio->idtipoEjercicio,$this->controller,$this->moduloId,Session::get('idUser'),array(),$this->campoBase);
       		 
       		 
    		 DB::commit ();
    	} catch ( \Exception $e ) {
    		DB::rollback ();
    		throw new \Exception('Error al actualizar TipoEjercicio: ' . $e);
    	}    
    }
    
    private function guardaEjercicios(Request $request, $idTipoEjercicio) {
        try {
            $ejercicio = null;
            $ejercicios =  $request->get('ejercicios');
            $arrayEjercicios = explode ( "@@", $ejercicios );
            
            foreach ( $arrayEjercicios as $ejercicio ) {
                if($ejercicio != '') {
                    $arrayDatos = explode ( "--", $ejercicio );
                    $idEjercicio = $arrayDatos[0];
                    if($idEjercicio == "null") {
                        $ejercicio =  new Ejercicio();
                    } else {
                        $ejercicio = Ejercicio::findOrFail($idEjercicio);
                    }
                    
                    $ejercicio->nombre = $arrayDatos[1];
                    $ejercicio->activo = 1;
                    $ejercicio->descripcion = $arrayDatos[2];
                    $ejercicio->idtipo_ejercicio = $idTipoEjercicio;
                    $ejercicio->save();
                    
                }
                
            }
            
        } catch ( \Exception $e ) {
            throw $e;
        }    
        
    }
    
    /**
     * Metodo para eliminar una tipoEjercicio
     * @param  $id
     * @throws \Exception
     */
     function eliminaTipoEjercicio($id){
     	DB::beginTransaction ();
     	try {
     		$tipoEjercicio = $this->getTipoEjercicio($id);
     		$tipoEjercicioA = clone $tipoEjercicio;
     		$tipoEjercicio->activo = 0;
     		$tipoEjercicio->update();
     		//Bitacora
     		$this->insertaBitacora(Constantes::ACCION_ELIMINAR, $tipoEjercicioA->getAttributes(), $tipoEjercicio->getAttributes(), $tipoEjercicio->idtipoEjercicio,$this->controller,$this->moduloId,Session::get('idUser'),array(),$this->campoBase);     		 
     		DB::commit ();
    	} catch ( \Exception $e ) {
    		DB::rollback ();
    		throw new \Exception('Error al actualizar TipoEjercicio: ' . $e);
    	}         		
     }
    
     /**
      * Metodo para activar una tipoEjercicio
      * @param int $id
      * @throws \Exception
      */
    function activaTipoEjercicio($id){
    	if((int) $id > 0){
    		DB::beginTransaction ();
    		try{
     			$tipoEjercicio = $this->getTipoEjercicio($id);
     			$tipoEjercicioA = clone $tipoEjercicio;
     			$tipoEjercicio->activo = 1;
     			$tipoEjercicio->update();
     			//Bitacora
     			$this->insertaBitacora(Constantes::ACCION_ACTIVAR, $tipoEjercicioA->getAttributes(), $tipoEjercicio->getAttributes(), $tipoEjercicio->idtipoEjercicio,$this->controller,$this->moduloId,Session::get('idUser'),array(),$this->campoBase);     			
    			DB::commit ();
    		}
    		catch ( \Exception $e ) {
    			DB::rollback ();
    			throw new \Exception('Error al restablecer a la TipoEjercicio con id:'.$id.' -> ' . $e);
    		}
    	}
    }
    
    
    
    /**
     * Metodo que regresa un objeto de tu
     * @param int $id
     */
    function getTipoEjercicio($id){
    	return  TipoEjercicio::findOrFail ( $id );
    }
    
    
    /**
     * Metodo para validar si el nombre de la tipoEjercicio ya se encuentra registrado
     * @param String $nombre
     * @param String $idtipoEjercicio
     * @return boolean
     */
    function validaNombre($nombre, $idtipoEjercicio){
    	$existe = false;
    	$results = DB::table('tipoEjercicio')->where('nombre','=',$nombre);
    	if($idtipoEjercicio != null && $idtipoEjercicio != '')
    		$results = $results->where('idtipoEjercicio','!=',$idtipoEjercicio);
    
    	$results = $results->get();
    	if(count($results) > 0){
    		$existe = true;
    	}
    	return $existe;
    }

    
   
    
    
}