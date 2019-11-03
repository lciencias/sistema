<?php namespace sistema\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use sistema\Models\Competencia;
use sistema\Models\Ejercicio;
use sistema\Models\TipoEjercicioCliente;
use sistema\Models\TipoEjercicioClienteComportamiento;
use sistema\Policies\Constantes;
use sistema\Repositories\Eloquent\Repository;

/**
 * Clase de servicio de acceso a datos de ejercicios
 * @author Miguel Molina 05/04/2017
 *
 */
class EjercicioRepository extends Repository {

	//nombre del campo que se registrara en la bitacora
    private $campoBase = "nombre";
	private $controller =  Constantes::CONTROLLER_EJERCICIO;
	private $moduloId = Constantes::MODULO_ID_EJERCICIO;
	
	
    /**
     * Specify Model class name
     *
     * @return mixed
     */
	/**
	 * Metodo que regresa el modelo utilizado
	 * {@inheritDoc}
	 * @see \sistema\Repositories\Eloquent\Repository::model()
	 */
    function model()
    {
        return 'sistema\Models\TipoEjercicioCliente';
    }
        
  
    
    
    private function comparaEjercicioPermisos($tipo,$ejercicio,$ejercicioA){
    	$array = array();
    	if($tipo == Constantes::ACCION_ALTA){
    		$array['ejercicioComportamientosAnt']  = '';
    		$array['ejercicioComportamientosDes']  = $this->exportaCadena($this->getEjercicioModulosPermisosUser($ejercicio->idejercicio));    
    	}else{
    		$array['ejercicioComportamientosAnt']  = $this->exportaCadena($this->getEjercicioModulosPermisosUser($ejercicioA->idejercicio));
    		$array['ejercicioComportamientosDes']  = $this->exportaCadena($this->getEjercicioModulosPermisosUser($ejercicio->idejercicio));
    	}
    	return $array;
    }
    
    private function exportaCadena($objetos){
    	$tmp = array();
    	$cadena = "";
    	if(count($objetos) > 0){
    		foreach($objetos as $objeto){
    			if( (int) $objeto->idcomportamiento > 0){
   					$tmp[] = "{Ejercicio : ".$objeto->idejercicio."  Comportamiento ".$objeto->idcomportamiento."}";
    			}
    		}
    		$cadena = implode(' ',$tmp);
    	}
    	return $cadena;
    }
    
    
    /**
     * Metodo para registrar la informaación del ejercicio
     * @param  $request
     * @throws \Exception
     */
    function save(Request $request) {
        $competencias = $request->input ( 'comportamientos' );
        $tmp = $registros  = array();
        DB::beginTransaction ();
        try {
            
            $ejercicioCliente  = new TipoEjercicioCliente();
            $ejercicioCliente->idcliente = trim($request->get ( 'idclienteEjercicio' ));
            $ejercicioCliente->idtipo_ejercicio = trim($request->get ( 'idTipoEjercicio' ));
            $ejercicioCliente->save ();    
            
            $registros = $this->construyeCompetenciasComportamientos($competencias);
            //Insert Ejercicio-comportamiento
            foreach($registros['comportamientos'] as $idComportamiento){
                if( (int) $idComportamiento > 0){
                    $ejercicioComportamientos = new TipoEjercicioClienteComportamiento();
                    $ejercicioComportamientos->idtipo_ejercicio_cliente = $ejercicioCliente->idtipo_ejercicio_cliente;
                    $ejercicioComportamientos->idcomportamiento= $idComportamiento;
                    $ejercicioComportamientos->save ();
                }
            }
            
            //Bitacora
            //$ejerciciosPermisos = $this->comparaEjercicioPermisos(Constantes::ACCION_ALTA,$ejercicio,$ejercicio);
            //$this->insertaBitacora(Constantes::ACCION_ALTA, null, $ejercicio->getAttributes(), $ejercicio->idejercicio,$this->controller,$this->moduloId,Session::get('idUser'),array(),$this->campoBase);
            DB::commit ();
        } catch ( \Exception $e ) {
            DB::rollback ();
            throw $e;
        }
    }
    
    /**
     * Metodo para actualizar la información del ejercicio
      * @param  $request
     * @throws \Exception
     */
    function updateEjercicio(Request $request, $id) {
        $competencias = $request->input ( 'comportamientos' );
        $tmp = $registros  = array();
    	DB::beginTransaction ();
    	try { 
    	    $ejercicioCliente  = TipoEjercicioCliente::findOrFail($id);
    	    $ejercicioCliente->idcliente = trim($request->get ( 'idclienteEjercicio' ));
    	    $ejercicioCliente->idtipo_ejercicio = trim($request->get ( 'idTipoEjercicio' ));
    	    $ejercicioCliente->save ();  
    		
    		//Se eliminan las relaciones entre ejercicio y comportamientos
    		$temp = DB::table('tipo_ejercicio_cliente_comportamiento')->where('idtipo_ejercicio_cliente','=',$id)->delete();
    		
    		$registros = $this->construyeCompetenciasComportamientos($competencias);
    		//Insert Ejercicio-comportamiento
    		foreach($registros['comportamientos'] as $idComportamiento){
    		    if( (int) $idComportamiento > 0){
    		        $ejercicioComportamientos = new TipoEjercicioClienteComportamiento();
    		        $ejercicioComportamientos->idtipo_ejercicio_cliente = $ejercicioCliente->idtipo_ejercicio_cliente;
    		        $ejercicioComportamientos->idcomportamiento= $idComportamiento;
    		        $ejercicioComportamientos->save ();
    		    }
    		}
    		//Bitacora
//     		$moduloId   = $this->obtenModuloId(Constantes::CONTROLLER_EJERCICIO);
//     		$ejerciciosPermisos = $this->comparaEjercicioPermisos(Constantes::ACCION_ACTUALIZAR,$ejercicio,$ejercicioA);
//     		$this->insertaBitacora(Constantes::ACCION_ACTUALIZAR, $ejercicioA->getAttributes(), $ejercicio->getAttributes(), $ejercicio->idejercicio,Constantes::CONTROLLER_EJERCICIO,$moduloId,Session::get('idUser'),$ejerciciosPermisos,self::$campoBase);
    		DB::commit ();
    		
    	} catch ( \Exception $e ) {
    		DB::rollback ();
    		throw $e;   		 
    	}    	 
    }
        
    
	
  /**
   * Metodo que elimina un ejercicio
   * @param int $id
   * @throws \Exception
   */  
    function eliminaEjercicio($id){
    	if((int) $id > 0){
    		DB::beginTransaction ();
    		try{    			
    			$ejercicio  = $this->getEjercicio($id);
    			$ejercicioA = clone $ejercicio;
    			$ejercicio->activo = false;
    			$ejercicio->update();
    			//Bitacora
    			$moduloId   = $this->obtenModuloId(Constantes::CONTROLLER_EJERCICIO);
    			$this->insertaBitacora(Constantes::ACCION_ELIMINAR, $ejercicioA->getAttributes(), $ejercicio->getAttributes(), $ejercicio->idejercicio,Constantes::CONTROLLER_EJERCICIO,$moduloId,Session::get('idUser'),array(),self::$campoBase);    			 
    			DB::commit ();
    		}
    		catch(\Exception $e){
    			DB::rollback ();
    			throw $e;
    		}
    	}
    	
    }
	/**
	 * Metodo qye regresa el objeto ejercicio de un id solicitado
	 * @param int $id
	 */
    function getEjercicio($id){
    	return  Ejercicio::findOrFail ( $id );
    }
    
    
    /**
     * Metodo que sirve para activar el ejercicio
     * @param  $id
     * @throws \Exception
     */
    function activaEjercicio($id){
    	if((int) $id > 0){
    		DB::beginTransaction ();
    		try{
    		    $ejercicio = Ejercicio::findOrFail ( $id );
    		    $ejercicioA = clone $ejercicio;
    			$ejercicio->activo = true;
    			$ejercicio->update();
    			//Bitacora
    			$moduloId   = $this->obtenModuloId(Constantes::CONTROLLER_EJERCICIO);
    			$this->insertaBitacora(Constantes::ACCION_ACTIVAR, $ejercicioA->getAttributes(), $ejercicio->getAttributes(), $ejercicio->idejercicio,Constantes::CONTROLLER_EJERCICIO,$moduloId,Session::get('idUser'),array(),self::$campoBase);    			 
    			DB::commit ();
    		}
    		catch ( \Exception $e ) {
    			DB::rollback ();
    			throw $e;
    		}
    	}
    
    }
    
    
    function construyeCompetenciasComportamientos($competencias){
    	$arrayCompetencias = $arrayComportamientos = $tmp = array();
    	if(count($competencias) > 0){
    		foreach($competencias as $comppetencia){
    			$tmp = explode('-',$comppetencia);
    			if(!in_array($tmp[1], $arrayCompetencias))
    				$arrayCompetencias[] = $tmp[1];
    				if(!in_array($tmp[2], $arrayCompetencias))
    					$arrayCompetencias[] = $tmp[2];
    					if(!in_array($tmp[2], $arrayComportamientos))
    						$arrayComportamientos[] = $tmp[2];
    		}
    	}
    	return array('competencias' => $arrayCompetencias,'comportamientos' => $arrayComportamientos);
    }
    

    
    
    /**
     * Obtiene las competencias asociadas al tipo de ejercicio del cliente con todos sus comportamientos 
     * @throws \Exception
     */
    function getComportamientosCompetenciasEjercicioCliente($idTipoEjercicioCliente) {
        try {
            $response = null;
            $competencias = DB::table('comportamiento')->distinct()->select('comportamiento.idcompetencia')
                ->join('tipo_ejercicio_cliente_comportamiento', 'tipo_ejercicio_cliente_comportamiento.idcomportamiento','=','comportamiento.idcomportamiento')
                ->where('tipo_ejercicio_cliente_comportamiento.idtipo_ejercicio_cliente','=',$idTipoEjercicioCliente)
                ->get();
                foreach ($competencias as $com) {
                    $competencia = Competencia::findOrFail($com->idcompetencia);
                    $comportamientos = DB::table('comportamiento')->where('idcompetencia','=',$competencia->idcompetencia)->get();
                    $response[] = array($competencia,$comportamientos); 
                }
                
                return $response;
            
        }
        catch ( \Exception $e ) {
            throw $e;
        }
    }
   
    
    /**
     * Obtiene los comportamientos asociados al ejercicio del cliente
     * @throws \Exception
     */
    function getComportamientosEjercicioCliente($idEjercicioCliente) {
        try {
            $response = null;
            $comportamientos = DB::table('comportamiento')
            ->join('tipo_ejercicio_cliente_comportamiento', 'tipo_ejercicio_cliente_comportamiento.idcomportamiento','=','comportamiento.idcomportamiento')
            ->where('tipo_ejercicio_cliente_comportamiento.idtipo_ejercicio_cliente','=',$idEjercicioCliente)->select('comportamiento.idcomportamiento')->get();
            
            foreach ($comportamientos as $com) {
                $response[] =$com->idcomportamiento;
            }
            
            return $response;
            
        }
        catch ( \Exception $e ) {
            throw $e;
        }
    }
   
}