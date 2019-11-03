<?php namespace sistema\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use sistema\Models\Competencia;
use sistema\Policies\Constantes;
use sistema\Repositories\Eloquent\Repository;
use sistema\Models\Comportamiento;
use sistema\Models\CalificacionComportamiento;

class CompetenciaRepository extends Repository {

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    private $campoBase  = "nombre";
    private $controller = Constantes::CONTROLLER_COMPETENCIA;
    private $moduloId = Constantes::MODULO_ID_COMPETENCIA;
	
    function model()
    {
        return 'sistema\Models\Competencia';
    }
    
    
    function saveCompetencia(Request $request) {
    	DB::beginTransaction ();
    	try {
    		 $competencia = new Competencia;
    		 $competencia->nombre =  trim($request->get('nombreCompetencia')); 
    		 $competencia->definicion = trim($request->get('definicionCompetencia')); 
    		 $competencia->idtipo_competencia = $request->get('tipoCompetencia'); 
       		 $competencia->activo = true;
       		 $competencia->save();         		 
       		 
       		 $this->guardaComportamientos($request, $competencia->idcompetencia);
       		 //Bitacora
       		 $this->insertaBitacora(Constantes::ACCION_ALTA, null, $competencia->getAttributes(), $competencia->idcompetencia,$this->controller,$this->moduloId,Session::get('idUser'),array(),$this->campoBase);
       		 
    		DB::commit ();
    	} catch ( \Exception $e ) {
    		DB::rollback ();
    		throw new \Exception('Error al guardar Competencia: ' . $e);
    		 
    	}
    	 return $competencia;
    }
    
    function updateCompetencia(Request $request,$id) {
    	DB::beginTransaction ();
    	try {
    	    
    	    
    		 $competencia  = $this->getCompetencia($id);
    		 $competenciaA = clone $competencia;
       		 $competencia->nombre 				 = $request->get('nombreCompetencia');
       		 $competencia->definicion 			 = $request->get('definicionCompetencia');
       		 $competencia->idtipo_competencia = $request->get('tipoCompetencia'); 
       		 $competencia->update();   
       		 
       		 
       		 $this->guardaComportamientos($request, $id);
       		 
       		 //Bitacora
       		 $this->insertaBitacora(Constantes::ACCION_ACTUALIZAR, $competenciaA->getAttributes(), $competencia->getAttributes(), $competencia->idcompetencia,$this->controller,$this->moduloId,Session::get('idUser'),array(),$this->campoBase);
       		 
       		 
    		 DB::commit ();
    	} catch ( \Exception $e ) {
    		DB::rollback ();
    		throw new \Exception('Error al actualizar Competencia: ' . $e);
    	}    
    }
    
    private function guardaComportamientos(Request $request, $idCompetencia) {
        try {
            $comportamiento = null;
            $comportamientos =  $request->get('comportamientos');
            $arrayComportamientos = explode ( "@@", $comportamientos );
            
            foreach ( $arrayComportamientos as $comportamiento ) {
                if($comportamiento != '') {
                    $arrayDatos = explode ( "--", $comportamiento );
                    $idComportamiento = $arrayDatos[0];
                    if($idComportamiento == "null") {
                        $comportamiento =  new Comportamiento();
                    } else {
                        $comportamiento = Comportamiento::findOrFail($idComportamiento);
                    }
                    
                    $comportamiento->nombre = $arrayDatos[1];
                    $comportamiento->activo = 1;
                    $comportamiento->idcompetencia = $idCompetencia;
                    $comportamiento->save();
                    
                    $opciones  = $arrayDatos[2];
                    $arrayOpciones = explode ( "@/@", $opciones );
                    
                    if($arrayOpciones[0] == ''  || $arrayOpciones[0] == 'null')
                        $calificacionComportamiento = new CalificacionComportamiento();
                    else
                        $calificacionComportamiento = CalificacionComportamiento::findOrFail($arrayOpciones[0]);
                    $calificacionComportamiento->idescala_calificacion_competencia = 1;
                    $calificacionComportamiento->calificacion_texto = $arrayOpciones[1];
                    $calificacionComportamiento->idcomportamiento = $comportamiento->idcomportamiento;
                    $calificacionComportamiento->save();
                            
                            
                            
                    if($arrayOpciones[2] == '' ||  $arrayOpciones[2] == 'null')
                        $calificacionComportamiento = new CalificacionComportamiento();
                    else
                        $calificacionComportamiento = CalificacionComportamiento::findOrFail($arrayOpciones[2]);
                    $calificacionComportamiento->idescala_calificacion_competencia = 2;
                    $calificacionComportamiento->calificacion_texto = $arrayOpciones[3];
                    $calificacionComportamiento->idcomportamiento = $comportamiento->idcomportamiento;
                    $calificacionComportamiento->save();
                                    
                                    
                                    
                    if($arrayOpciones[4] == '' || $arrayOpciones[4] == 'null' )
                        $calificacionComportamiento = new CalificacionComportamiento();
                    else
                        $calificacionComportamiento = CalificacionComportamiento::findOrFail($arrayOpciones[4]);
                    $calificacionComportamiento->idescala_calificacion_competencia = 3;
                    $calificacionComportamiento->calificacion_texto = $arrayOpciones[5];
                    $calificacionComportamiento->idcomportamiento = $comportamiento->idcomportamiento;
                    $calificacionComportamiento->save();
                                            
                                            
                                            
                    if($arrayOpciones[6] == '' || $arrayOpciones[6] == 'null')
                        $calificacionComportamiento = new CalificacionComportamiento();
                    else
                        $calificacionComportamiento = CalificacionComportamiento::findOrFail($arrayOpciones[6]);
                    $calificacionComportamiento->idescala_calificacion_competencia = 4;
                    $calificacionComportamiento->calificacion_texto = $arrayOpciones[7];
                    $calificacionComportamiento->idcomportamiento = $comportamiento->idcomportamiento;
                    $calificacionComportamiento->save();
                                                    
                                                    
                                                    
                    if($arrayOpciones[8] == '' || $arrayOpciones[8] == 'null')
                        $calificacionComportamiento = new CalificacionComportamiento();
                    else
                        $calificacionComportamiento = CalificacionComportamiento::findOrFail($arrayOpciones[8]);
                    $calificacionComportamiento->idescala_calificacion_competencia = 5;
                    $calificacionComportamiento->calificacion_texto = $arrayOpciones[9];
                    $calificacionComportamiento->idcomportamiento = $comportamiento->idcomportamiento;
                    $calificacionComportamiento->save();
                                                            
                                                            
                                                            
                    if($arrayOpciones[10] == '' || $arrayOpciones[10] == 'null')
                        $calificacionComportamiento = new CalificacionComportamiento();
                    else
                        $calificacionComportamiento = CalificacionComportamiento::findOrFail($arrayOpciones[10]);
                    $calificacionComportamiento->idescala_calificacion_competencia = 6;
                    $calificacionComportamiento->calificacion_texto = $arrayOpciones[11];
                    $calificacionComportamiento->idcomportamiento = $comportamiento->idcomportamiento;
                    $calificacionComportamiento->save();
                    
                }
                
                
            }
            
        } catch ( \Exception $e ) {
            throw $e;
        }    
        
    }
    
    /**
     * Metodo para eliminar una competencia
     * @param  $id
     * @throws \Exception
     */
     function eliminaCompetencia($id){
     	DB::beginTransaction ();
     	try {
     		$competencia = $this->getCompetencia($id);
     		$competenciaA = clone $competencia;
     		$competencia->activo = 0;
     		$competencia->update();
     		//Bitacora
     		$this->insertaBitacora(Constantes::ACCION_ELIMINAR, $competenciaA->getAttributes(), $competencia->getAttributes(), $competencia->idcompetencia,$this->controller,$this->moduloId,Session::get('idUser'),array(),$this->campoBase);     		 
     		DB::commit ();
    	} catch ( \Exception $e ) {
    		DB::rollback ();
    		throw new \Exception('Error al actualizar Competencia: ' . $e);
    	}         		
     }
    
     /**
      * Metodo para activar una competencia
      * @param int $id
      * @throws \Exception
      */
    function activaCompetencia($id){
    	if((int) $id > 0){
    		DB::beginTransaction ();
    		try{
     			$competencia = $this->getCompetencia($id);
     			$competenciaA = clone $competencia;
     			$competencia->activo = 1;
     			$competencia->update();
     			//Bitacora
     			$this->insertaBitacora(Constantes::ACCION_ACTIVAR, $competenciaA->getAttributes(), $competencia->getAttributes(), $competencia->idcompetencia,$this->controller,$this->moduloId,Session::get('idUser'),array(),$this->campoBase);     			
    			DB::commit ();
    		}
    		catch ( \Exception $e ) {
    			DB::rollback ();
    			throw new \Exception('Error al restablecer a la Competencia con id:'.$id.' -> ' . $e);
    		}
    	}
    }
    
    
    
    /**
     * Metodo que regresa un objeto de tu
     * @param int $id
     */
    function getCompetencia($id){
    	return  Competencia::findOrFail ( $id );
    }
    
    
    /**
     * Metodo para validar si el nombre de la competencia ya se encuentra registrado
     * @param String $nombre
     * @param String $idcompetencia
     * @return boolean
     */
    function validaNombre($nombre, $idcompetencia){
    	$existe = false;
    	$results = DB::table('competencia')->where('nombre','=',$nombre);
    	if($idcompetencia != null && $idcompetencia != '')
    		$results = $results->where('idcompetencia','!=',$idcompetencia);
    
    	$results = $results->get();
    	if(count($results) > 0){
    		$existe = true;
    	}
    	return $existe;
    }

    
    function getNivelesCalificacionComportamiento($idComportamiento){
        try{
            return  DB::table('calificacion_comportamiento')->where('idcomportamiento','=',$idComportamiento)->orderBy('idcomportamiento')->get();
        }catch(\Exception $e){
            throw $e;
        }
        
    }
    
   
    
    
}