<?php namespace sistema\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use sistema\Models\OpcionPregunta;
use sistema\Models\PerfilPuesto;
use sistema\Models\PerfilPuestoTalento;
use sistema\Policies\Constantes;
use sistema\Repositories\Eloquent\Repository;

class PerfilPuestoRepository extends Repository {

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
        return 'sistema\Models\PerfilPuesto';
    }
    
    
    function savePerfilPuesto(Request $request) {
    	DB::beginTransaction ();
    	try {
    		 $perfilPuesto = new PerfilPuesto;
    		 $perfilPuesto->nombre =  trim($request->get('nombrePerfilPuesto')); 
    		 $perfilPuesto->idcliente = trim($request->get('idclientePerfilPuesto'));
    		 $perfilPuesto->nivel_puesto = trim($request->get('nivelPerfilPuesto'));
    		 $perfilPuesto->funcion_generica = trim($request->get('funcionGenericaPerfilPuesto'));
    		 $perfilPuesto->funciones_generales = trim($request->get('funcionesGeneralesPerfilPuesto'));
    		 $perfilPuesto->actividades = trim($request->get('actividadesPerfilPuesto'));
    		 $perfilPuesto->etapa = Constantes::ETAPA_PRUEBA_POR_CARGAR_RESULTADOS; 
       		 $perfilPuesto->activo = true;
       		 $perfilPuesto->save();         		 
       		 
       		 
       		 if($request->get('etapa') == Constantes::ETAPA_PERFIL_PUESTO_POR_CARGAR_TALENTOS)
       		     $this->guardaTalentos($request, $perfilPuesto->idperfilPuesto);
       		 
       		 //Bitacora
       		 $this->insertaBitacora(Constantes::ACCION_ALTA, null, $perfilPuesto->getAttributes(), $perfilPuesto->idperfilPuesto,$this->controller,$this->moduloId,Session::get('idUser'),array(),$this->campoBase);
       		 
    		DB::commit ();
    	} catch ( \Exception $e ) {
    		DB::rollback ();
    		throw new \Exception('Error al guardar PerfilPuesto: ' . $e);
    		 
    	}
    	 return $perfilPuesto;
    }
    
    function updatePerfilPuesto(Request $request,$id) {
    	DB::beginTransaction ();
    	try {
    	    
    	    
    		 $perfilPuesto  = $this->getPerfilPuesto($id);
    		 $perfilPuestoA = clone $perfilPuesto;
       		 $perfilPuesto->nombre       = $request->get('nombrePerfilPuesto');
       		 $perfilPuesto->idcliente = trim($request->get('descripcionPerfilPuesto'));
       		 $perfilPuesto->nivel_puesto = trim($request->get('nivelPerfilPuesto'));
       		 $perfilPuesto->funcion_generica = trim($request->get('funcionGenericaPerfilPuesto'));
       		 $perfilPuesto->funciones_generales = trim($request->get('funcionesGeneralesPerfilPuesto'));
       		 
       		 
       		 
       		 if($perfilPuesto->etapa == Constantes::ETAPA_PERFIL_PUESTO_POR_CARGAR_TALENTOS) {
                $this->guardaTalentos($request, $id);
                $perfilPuesto->etapa = Constantes::ETAPA_PERFIL_PUESTO_POR_CARGAR_PRUEBAS;
       		 }
//        		 if($perfilPuesto->etapa == Constantes::ETAPA_PRUEBA_POR_CARGAR_PREGUNTAS || $perfilPuesto->etapa == Constantes::ETAPA_PRUEBA_POR_CARGAR_INTERPRETACION) {
//                 $this->guardaPreguntas($request, $id);
//                 $perfilPuesto->etapa = Constantes::ETAPA_PRUEBA_POR_CARGAR_INTERPRETACION;
//        		 }
//        		 if($perfilPuesto->etapa == Constantes::ETAPA_PRUEBA_POR_CARGAR_INTERPRETACION) {
//        		     $perfilPuesto->etapa = Constantes::ETAPA_PRUEBA_FINAL;
//        		 }
       		 $perfilPuesto->update();  
       		 //Bitacora
       		 $this->insertaBitacora(Constantes::ACCION_ACTUALIZAR, $perfilPuestoA->getAttributes(), $perfilPuesto->getAttributes(), $perfilPuesto->idperfilPuesto,$this->controller,$this->moduloId,Session::get('idUser'),array(),$this->campoBase);
       		 
       		 
    		 DB::commit ();
    	} catch ( \Exception $e ) {
    		DB::rollback ();
    		throw new \Exception('Error al actualizar PerfilPuesto: ' . $e);
    	}    
    }
    
    
    private function guardaTalentos(Request $request, $idPerfilPuesto) {
        try {
            $talentos = $request->get ( "resultados" );
            $arrayTalentos = explode ( "@@", $talentos );
            
            foreach ( $arrayTalentos as $resp) {
                $arrayDatos = explode ( "--", $resp );
                $telantoPerfilPuesto = new PerfilPuestoTalento();
                $telantoPerfilPuesto->idperfil_puesto_talento = $arrayDatos [0];
                $telantoPerfilPuesto->idtalento = $arrayDatos [1];
                $telantoPerfilPuesto->idperfil_puesto = $idPerfilPuesto;
                
                if ($telantoPerfilPuesto->idperfil_puesto_talento == "null") {
                    $telantoPerfilPuesto->save ();
                } else {
                    $telantoPerfilPuesto->update ();
                }
            }
            
        } catch ( \Exception $e ) {
            throw $e;
        }
        
    }
    
    
    
    
    
    /**
     * Metodo para eliminar una perfilPuesto
     * @param  $id
     * @throws \Exception
     */
     function eliminaPerfilPuesto($id){
     	DB::beginTransaction ();
     	try {
     		$perfilPuesto = $this->getPerfilPuesto($id);
     		$perfilPuestoA = clone $perfilPuesto;
     		$perfilPuesto->activo = 0;
     		$perfilPuesto->update();
     		//Bitacora
     		$this->insertaBitacora(Constantes::ACCION_ELIMINAR, $perfilPuestoA->getAttributes(), $perfilPuesto->getAttributes(), $perfilPuesto->idperfilPuesto,$this->controller,$this->moduloId,Session::get('idUser'),array(),$this->campoBase);     		 
     		DB::commit ();
    	} catch ( \Exception $e ) {
    		DB::rollback ();
    		throw new \Exception('Error al actualizar PerfilPuesto: ' . $e);
    	}         		
     }
    
     /**
      * Metodo para activar una perfilPuesto
      * @param int $id
      * @throws \Exception
      */
    function activaPerfilPuesto($id){
    	if((int) $id > 0){
    		DB::beginTransaction ();
    		try{
     			$perfilPuesto = $this->getPerfilPuesto($id);
     			$perfilPuestoA = clone $perfilPuesto;
     			$perfilPuesto->activo = 1;
     			$perfilPuesto->update();
     			//Bitacora
     			$this->insertaBitacora(Constantes::ACCION_ACTIVAR, $perfilPuestoA->getAttributes(), $perfilPuesto->getAttributes(), $perfilPuesto->idperfilPuesto,$this->controller,$this->moduloId,Session::get('idUser'),array(),$this->campoBase);     			
    			DB::commit ();
    		}
    		catch ( \Exception $e ) {
    			DB::rollback ();
    			throw new \Exception('Error al restablecer a la PerfilPuesto con id:'.$id.' -> ' . $e);
    		}
    	}
    }
    
    
    
    /**
     * Metodo que regresa un objeto de tu
     * @param int $id
     */
    function getPerfilPuesto($id){
    	return  PerfilPuesto::findOrFail ( $id );
    }
    
    
    /**
     * Metodo para validar si el nombre de la perfilPuesto ya se encuentra registrado
     * @param String $nombre
     * @param String $idperfilPuesto
     * @return boolean
     */
    function validaNombre($nombre, $idperfilPuesto){
    	$existe = false;
    	$results = DB::table('perfilPuesto')->where('nombre','=',$nombre);
    	if($idperfilPuesto != null && $idperfilPuesto != '')
    		$results = $results->where('idperfilPuesto','!=',$idperfilPuesto);
    
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
    
    function getResultadosPerfilPuesto($idPerfilPuesto, $estatus){
        $qry = DB::table( 'perfilPuesto_resultado' )->where('idperfilPuesto','=',$idPerfilPuesto);
        if($estatus != '')
            $qry = $qry->where('activo','=',$estatus);
            
            $qry = $qry->orderBy('resultado')->get();
            return $qry;
    }
    
    function getPreguntasPerfilPuesto($idPerfilPuesto, $estatus){
        $qry = DB::table( 'pregunta_perfilPuesto' )->where('idperfilPuesto','=',$idPerfilPuesto);
        if($estatus != '')
            $qry = $qry->where('activo','=',$estatus);
            
            $qry = $qry->orderBy('pregunta')->get();
            return $qry;
    }
    
}