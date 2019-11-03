<?php namespace sistema\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use sistema\Models\OpcionPregunta;
use sistema\Models\PreguntaPrueba;
use sistema\Models\Prueba;
use sistema\Models\PruebaResultado;
use sistema\Policies\Constantes;
use sistema\Repositories\Eloquent\Repository;
use sistema\Models\PruebaInterpretacion;

class PruebaRepository extends Repository {

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
        return 'sistema\Models\Prueba';
    }
    
    
    function savePrueba(Request $request) {
    	DB::beginTransaction ();
    	try {
    		 $prueba = new Prueba;
    		 $prueba->nombre =  trim($request->get('nombrePrueba')); 
    		 $prueba->descripcion = trim($request->get('descripcionPrueba'));
    		 $prueba->indicaciones = trim($request->get('indicacionesPrueba'));
    		 $prueba->etapa = Constantes::ETAPA_PRUEBA_POR_CARGAR_RESULTADOS; 
       		 $prueba->activo = true;
       		 $prueba->save();         		 
       		 
       		 
       		 if($request->get('etapa') == Constantes::ETAPA_PRUEBA_POR_CARGAR_PREGUNTAS)
       		   $this->guardaPreguntas($request, $prueba->idprueba);
       		 
       		 //Bitacora
       		 $this->insertaBitacora(Constantes::ACCION_ALTA, null, $prueba->getAttributes(), $prueba->idprueba,$this->controller,$this->moduloId,Session::get('idUser'),array(),$this->campoBase);
       		 
    		DB::commit ();
    	} catch ( \Exception $e ) {
    		DB::rollback ();
    		throw new \Exception('Error al guardar Prueba: ' . $e);
    		 
    	}
    	 return $prueba;
    }
    
    function updatePrueba(Request $request,$id) {
    	DB::beginTransaction ();
    	try {
    	    
    	    
    		 $prueba  = $this->getPrueba($id);
    		 $pruebaA = clone $prueba;
       		 $prueba->nombre       = $request->get('nombrePrueba');
       		 $prueba->descripcion = trim($request->get('descripcionPrueba'));
       		 $prueba->indicaciones = trim($request->get('indicacionesPrueba'));
       		 
       		 
       		 
       		 if($prueba->etapa == Constantes::ETAPA_PRUEBA_POR_CARGAR_RESULTADOS) {
                $this->guardaResultados($request, $id);
                $prueba->etapa = Constantes::ETAPA_PRUEBA_POR_CARGAR_PREGUNTAS;
       		 }
       		 if($prueba->etapa == Constantes::ETAPA_PRUEBA_POR_CARGAR_PREGUNTAS || $prueba->etapa == Constantes::ETAPA_PRUEBA_POR_CARGAR_INTERPRETACION) {
                $this->guardaPreguntas($request, $id);
                $prueba->etapa = Constantes::ETAPA_PRUEBA_POR_CARGAR_INTERPRETACION;
       		 }
       		 if($prueba->etapa == Constantes::ETAPA_PRUEBA_POR_CARGAR_INTERPRETACION) {
//        		     $this->guardaInterpretaciones($request, $id);
//        		     $prueba->etapa = Constantes::ETAPA_PRUEBA_FINAL;
       		 }
       		 $prueba->update();  
       		 //Bitacora
       		 $this->insertaBitacora(Constantes::ACCION_ACTUALIZAR, $pruebaA->getAttributes(), $prueba->getAttributes(), $prueba->idprueba,$this->controller,$this->moduloId,Session::get('idUser'),array(),$this->campoBase);
       		 
       		 
    		 DB::commit ();
    	} catch ( \Exception $e ) {
    		DB::rollback ();
    		throw new \Exception('Error al actualizar Prueba: ' . $e);
    	}    
    }
    
    
    
    private function guardaPreguntas(Request $request, $idPrueba) {
        try {
            $preguntas = $request->get ( "preguntas" );
            if($preguntas != '') {
                $arrayPreguntas = explode ( "@@", $preguntas );
                // 			 print_r($arrayPreguntas);
                // 			 die ("aca");
                foreach ( $arrayPreguntas as $pregunta ) {
                    $arrayDatos = explode ( "--", $pregunta );
                    // 				print_r($arrayDatos);
                    // 				die ("aca1");
                    $pregunta = new PreguntaPrueba();
                    $pregunta->idpregunta_prueba = $arrayDatos [0];
                    $pregunta->orden = $arrayDatos [1];
                    $pregunta->pregunta = $arrayDatos [2];
                    //                 $pregunta->obligada = $arrayDatos [3];
                    $pregunta->idprueba = $idPrueba;
                    
                    if ($pregunta->idpregunta_prueba == "null") {
                        $pregunta->save ();
                    } else {
                        $pregunta->update ();
                    }
                    
                    
                    $arrayOpciones = explode ( "&&", $arrayDatos [3] );
                    // print_r($arrayOpciones);
                    // die ("aca");
                    foreach ( $arrayOpciones as $opcion ) {
                        if($opcion != '') {
                            $arrayDatosOpciones = explode ( "//", $opcion );
                            // 						print_r ( $arrayDatosOpciones );
                            // die ("aca3");
                            $opcion = new OpcionPregunta();
                            $opcion->idopcion_pregunta = $arrayDatosOpciones [0];
                            $opcion->orden = $arrayDatosOpciones [1];
                            $opcion->opcion = $arrayDatosOpciones [2];
                            $opcion->idprueba_resultado = $arrayDatosOpciones [3];
                            $opcion->idpregunta_prueba = $pregunta->idpregunta_prueba;
                            
                            if ($opcion->idopcion_pregunta == "null") {
                                $opcion->save ();
                            } else {
                                $opcion->update ();
                            }
                        }
                        
                        
                    }
                    
                }
                
            }
           
            
            
        } catch ( \Exception $e ) {
            throw $e;
        }
        
    }
    
    
    private function guardaResultados(Request $request, $idPrueba) {
        try {
            $resultados = $request->get ( "resultados" );
            $arrayResultados = explode ( "@@", $resultados );
            
            foreach ( $arrayResultados as $resp) {
                $arrayDatos = explode ( "--", $resp );
                $resultadoPregunta = new PruebaResultado();
                $resultadoPregunta->idprueba_resultado = $arrayDatos [0];
                $resultadoPregunta->resultado = $arrayDatos [1];
                $resultadoPregunta->descripcion = $arrayDatos [2];
                $resultadoPregunta->idprueba = $idPrueba;
                
                if ($resultadoPregunta->idprueba_resultado == "null") {
                    $resultadoPregunta->save ();
                } else {
                    $resultadoPregunta->update ();
                }
            }
            
        } catch ( \Exception $e ) {
            throw $e;
        }    
        
    }
    
    
    private function guardaInterpretaciones(Request $request, $idPrueba) {
        try {
            $interpretaciones = $request->get ( "interpretaciones" );
            $arrayInterpretaciones = explode ( "@@", $interpretaciones );
            
            foreach ( $arrayInterpretaciones as $resp) {
                $arrayDatos = explode ( "--", $resp );
                $pruebaInterpretacion = new PruebaInterpretacion();
                $pruebaInterpretacion->idprueba_interpretacion = $arrayDatos [0];
                $pruebaInterpretacion->resultado = $arrayDatos [1];
                $pruebaInterpretacion->interpretacion = $arrayDatos [2];
                $pruebaInterpretacion->idprueba = $idPrueba;
                
                if ($pruebaInterpretacion->idprueba_interpretacion == "null") {
                    $pruebaInterpretacion->save ();
                } else {
                    $pruebaInterpretacion->update ();
                }
            }
            
        } catch ( \Exception $e ) {
            throw $e;
        }
        
    }
    
    /**
     * Metodo para eliminar una prueba
     * @param  $id
     * @throws \Exception
     */
     function eliminaPrueba($id){
     	DB::beginTransaction ();
     	try {
     		$prueba = $this->getPrueba($id);
     		$pruebaA = clone $prueba;
     		$prueba->activo = 0;
     		$prueba->update();
     		//Bitacora
     		$this->insertaBitacora(Constantes::ACCION_ELIMINAR, $pruebaA->getAttributes(), $prueba->getAttributes(), $prueba->idprueba,$this->controller,$this->moduloId,Session::get('idUser'),array(),$this->campoBase);     		 
     		DB::commit ();
    	} catch ( \Exception $e ) {
    		DB::rollback ();
    		throw new \Exception('Error al actualizar Prueba: ' . $e);
    	}         		
     }
    
     /**
      * Metodo para activar una prueba
      * @param int $id
      * @throws \Exception
      */
    function activaPrueba($id){
    	if((int) $id > 0){
    		DB::beginTransaction ();
    		try{
     			$prueba = $this->getPrueba($id);
     			$pruebaA = clone $prueba;
     			$prueba->activo = 1;
     			$prueba->update();
     			//Bitacora
     			$this->insertaBitacora(Constantes::ACCION_ACTIVAR, $pruebaA->getAttributes(), $prueba->getAttributes(), $prueba->idprueba,$this->controller,$this->moduloId,Session::get('idUser'),array(),$this->campoBase);     			
    			DB::commit ();
    		}
    		catch ( \Exception $e ) {
    			DB::rollback ();
    			throw new \Exception('Error al restablecer a la Prueba con id:'.$id.' -> ' . $e);
    		}
    	}
    }
    
    
    
    /**
     * Metodo que regresa un objeto de tu
     * @param int $id
     */
    function getPrueba($id){
    	return  Prueba::findOrFail ( $id );
    }
    
    
    /**
     * Metodo para validar si el nombre de la prueba ya se encuentra registrado
     * @param String $nombre
     * @param String $idprueba
     * @return boolean
     */
    function validaNombre($nombre, $idprueba){
    	$existe = false;
    	$results = DB::table('prueba')->where('nombre','=',$nombre);
    	if($idprueba != null && $idprueba != '')
    		$results = $results->where('idprueba','!=',$idprueba);
    
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
    
    function getResultadosPrueba($idPrueba, $estatus){
        $qry = DB::table( 'prueba_resultado' )->where('idprueba','=',$idPrueba);
        if($estatus != '')
            $qry = $qry->where('activo','=',$estatus);
            
            $qry = $qry->orderBy('resultado')->get();
            return $qry;
    }
    
    function getPreguntasPrueba($idPrueba, $estatus){
        $qry = DB::table( 'pregunta_prueba' )->where('idprueba','=',$idPrueba);
        if($estatus != '')
            $qry = $qry->where('activo','=',$estatus);
            
            $qry = $qry->orderBy('pregunta')->get();
            return $qry;
    }
    
    function getInterpretacionesPrueba($idPrueba, $estatus){
        $qry = DB::table( 'prueba_interpretacion' )->where('idprueba','=',$idPrueba);
//         if($estatus != '')
//             $qry = $qry->where('activo','=',$estatus);
            
            $qry = $qry->orderBy('resultado')->get();
            return $qry;
    }
    
    
}