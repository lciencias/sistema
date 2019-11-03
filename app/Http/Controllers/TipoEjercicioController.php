<?php

namespace sistema\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use sistema\Models\TipoEjercicio;
use sistema\Policies\Constantes;
use sistema\Repositories\TipoEjercicioRepository;


class TipoEjercicioController extends Controller
{
    private $tipoEjercicioRepository;
    private $controller;
    private $moduloId;
    /**
     * Metodo Constructor de la Clase TipoEjercicios
     * @param TipoEjercicioRepository $tipoEjercicioRepository
     */
    public function __construct(TipoEjercicioRepository $tipoEjercicioRepository)
    {
    	parent::__construct();
        $this->tipoEjercicioRepository = $tipoEjercicioRepository;
        $this->controller = Constantes::CONTROLLER_TIPO_EJERCICIO;
        $this->moduloId   = Constantes::MODULO_ID_TIPO_EJERCICIO;
    }
    
    /**
     * Metodo que muestra el listado de tipoEjercicios
     * @param Request $request
     */
    public function index(Request $request){
        Session::forget('message-warning');
        if ($request) {
            return view ( 'catalogos.tipoEjercicio.indexTipoEjercicio', [
                "isAdmin"     => $this->isAdmin,
                "moduloId"    => $this->moduloId,
                "controller"  => $this->controller
            ] );
        }
    }
    
    
    
    /**
     * Envia los resultados de la busqueda a la vista
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscar(Request $request){
    	Session::forget('message-warning');
    	$moduloId   = $request->get('idModulo');
    	$opciones   = $this->parametros($request, 'idtipo_ejercicio');
    	$view = $this->buscaTipoEjercicios($request,$opciones,$moduloId);
    	return response()->json(view('catalogos.tipoEjercicio.busquedaTipoEjercicio',$view)->render());
    }
    

    /**
     * Realiza el llamado para la busqueda de tipoEjercicios
     * @param Request $request
     * @param $opciones
     * @param $moduloId
     */
    private function buscaTipoEjercicios(Request $request,$opciones,$moduloId){
    	$session    = Session()->get('permisos');
    	$filtros  	= $this->generaFiltrosTipoEjercicios($request);
    	$total      = $this->tipoEjercicioRepository->findByCount($filtros);
    	$activo = $request->get('activoTipoEjercicioBusca');
    	if($activo == null)
    	    $activo = "-1";
    	
    	$tipoEjercicios = $this->tipoEjercicioRepository->findByColumn( $filtros,$opciones );
    	
    	
    	return [
    			"idtipoEjercicio"            => $request->get('idtipoEjercicio'),
    			"total"      		   => $total,
    			"leyenda"    		   => $this->tipoEjercicioRepository->generaLetrero($total,count($tipoEjercicios),$opciones),
    			"nombreTipoEjercicioBusca"   => $request->get('nombreTipoEjercicioBusca'),
    	        "descripcionTipoEjercicioBusca"   => $request->get('descripcionTipoEjercicioBusca'),
    	        "activoTipoEjercicioBusca"   => $activo,
    	         "tipoTipoEjercicioBusca"   => $request->get('tipoTipoEjercicioBusca'),
    			"tipoEjercicios"        	   => $tipoEjercicios,
    			"moduloId" 		       => $moduloId,
    			"noPage" 	  		   => $opciones['nopage'],
    			"noRegs" 	   		   => $opciones['noregs'],
    			"isAdmin" 		       => $this->isAdmin,
    			"sessionPermisos"      => $session[$moduloId],
    			"catEstatus"      => array(1 => 'Activo',0=> 'No Activo')
    	];
    }
    
    /**
     * Metodo que genera el filtro para la busqueda de tipoEjercicios
     * @param Request $request
     * @return multitype:multitype:string
     */
    private function generaFiltrosTipoEjercicios(Request $request){
    	$filtros = array ();
    	$nombre = $request->get('nombreTipoEjercicioBusca');
    	$definicion = $request->get('descripcionTipoEjercicioBusca');
    	$activo = $request->get('activoTipoEjercicioBusca');
    	if($activo == null)
    	    $activo = "-1";
    
    	if(trim($nombre) != ''){
    		$filtros[] = ['tipoEjercicio.nombre', 'LIKE', '%' . $nombre . '%'];
    	}
    	if(trim($definicion) != ''){
    	    $filtros[] = ['tipoEjercicio.descripcion', 'LIKE', '%' . $definicion . '%'];
    	}
    	if(trim($activo) != '-1'){
    		$filtros[] = ['tipoEjercicio.activo', '=', $activo];
    	}
    	return $filtros;
    }
    
    
    /**
     * Metodo para crear una tipoEjercicio
     */
    public function create()
    {
    	Session()->put('editar',false);
    	$tipoEjercicio = new TipoEjercicio();
    	$tipoEjercicio->nombre = '';
    	$tipoEjercicio->definicion = '';
    	$session    = Session()->get('permisos'); 
        return view("catalogos.tipoEjercicio.createTipoEjercicio", [
                "sessionPermisos" => $session[$this->moduloId],
        		"tipoEjercicio" => $tipoEjercicio,
                "ejercicios" =>array(),
        		"tipoEjercicioIdUser" => Session::get ( 'userEnterprise' ),
                "idModulo" => $this->moduloId] );
    }
    
    /**
     * Metodo que guarda la información de una tipoEjercicio
     * @param $request
     */
 	public function store (Request $request)
    {
        try {
    		$this->tipoEjercicioRepository->saveTipoEjercicio( $request);
        	Session::flash ( 'message', Lang::get ( 'general.success' ) );
        } catch ( \Exception $e ) {
        	$this->log->error ($e);
        	Session::flash ( 'message-error', Lang::get ( 'general.error' ) );
        }
        return Redirect::to ( 'catalogos/tipoEjercicio');

    }
    
    /**
     * Metodo para obtener los datos de una tipoEjercicio que se edita
     * @param int $id
     */
    public function edit($id)
    {
    	$id =  Crypt::decrypt($id);
    	Session()->put('editar',true);
    	$tipoEjercicio = $this->tipoEjercicioRepository->getTipoEjercicio($id);
    	$session    = Session()->get('permisos');
    	$ejercicios = $this->tipoEjercicioRepository->getEjerciciosPorTipo($id, Constantes::ESTATUS_ACTIVO);
    	return view("catalogos.tipoEjercicio.editTipoEjercicio",[
    	        "sessionPermisos" => $session[$this->moduloId],
    	       "idModulo"=>$this->moduloId,
    	    "ejercicios" => $ejercicios,
    			"tipoEjercicioIdUser" => Session::get ( 'userEnterprise' ),
    			"tipoEjercicio"=>$tipoEjercicio]);
    }
    
    /**
     * Metodo que actualiza la información de la tipoEjercicio
     * @param $request
     */
 	public function update(Request $request, $id)
    {    
    	try {
    		if($request){   
    			$this->tipoEjercicioRepository->updateTipoEjercicio( $request ,$id);
    			Session::flash ( 'message', Lang::get ( 'general.success' ) );
    			
    		}
    	} 
		catch (\Exception $e) {
            $this->log->error($e);
			Session::flash ( 'message-error', Lang::get ( 'general.error' ) );
		}
		finally {
			 return Redirect::to('catalogos/tipoEjercicio');
		}
    }
    
    
    
    /**
     * Metodo para activar la tipoEjercicio
     * @param Request $request
     */
    public function activar(Request $request){
    	$exito = 0;
    	$msg   = Lang::get ( 'general.error' );
    	try{
    		$id = $request->get('idTipoEjercicio');
    		if((int) $id > 0){
    			$this->tipoEjercicioRepository->activaTipoEjercicio($id);
    			$exito = 1;
    			$msg   = Lang::get ( 'general.success' );
    		}
    	}
    	catch (\Exception $e) {
            $this->log->error($e);
    	}
    	finally {
    		return json_encode(array('exito' => $exito,'msg' => $msg));
    	}    
    }
    
    /**
     * Para desactivar la tipoEjercicio
     * @param Request $request
     * @return string
     */
    public function desactivar(Request $request){
    	$exito = 0;
    	$msg   = Lang::get ( 'general.error' );
    	try{
    		$id = $request->get('idTipoEjercicio');
    		if((int) $id > 0){
    			$this->tipoEjercicioRepository->eliminaTipoEjercicio($id);
    			$exito = 1;
    			$msg   = Lang::get ( 'general.success' );
    		}
    	}
    	catch (\Exception $e) {
            $this->log->error($e);
    	}
    	finally {
    		return json_encode(array('exito' => $exito,'msg' => $msg));
    	}
    }
    
    
    
    /**
     * Valida si el nombre de un elemento ya esta registrado es funcion de udo generico
     * @param Request $request
     */
    public function validaNombreElemento(Request $request) {
    	$response;
    	if(!$this->tipoEjercicioRepository->validaNombreElemento($request)){
    		$response = array('valid' => true ,  'message' => '');
    	} else {
    		$response = array('valid' => false,  'message' => Lang::get ( 'leyendas.nombreExistente' ));
    	}
    	print_r(json_encode($response));
    }
    
    
    
    
    
    
    /**
     * Metodo que busca los niveles de calificacion asociados a un comportamiento
     * @param Request $request
     */
    public function buscaNivelesCalificacionComportamiento(Request $request){
        $exito = 0;
        $nivelesCalificacion = array();
        try{
            $id = $request->get('idComportamiento');
            if((int) $id > 0){
                $nivelesCalificacion = $this->tipoEjercicioRepository->getNivelesCalificacionComportamiento($id);
                $exito = 1;
            }
        }
        catch (\Exception $e) {
            $this->log->error($e->getMessage()."<br>".$e->getMessage());
        }
        finally {
            return json_encode(array('exito' => $exito,'nivelesCalificacion' => $nivelesCalificacion));
        }
        
    }
    
    
    /**
     * Metodo que busca los comportamientos asociados a una tipoEjercicio
     * @param Request $request
     */
    public function buscaComportamientos(Request $request){
        $exito = 0;
        $comportamientos = array();
        try{
            $id = $request->get('idTipoEjercicio');
            if((int) $id > 0){
                $comportamientos = $this->tipoEjercicioRepository->getComportamientosTipoEjercicio($id, Constantes::ESTATUS_ACTIVO);
                $exito = 1;
            }
        }
        catch (\Exception $e) {
            $this->log->error($e->getMessage()."<br>".$e->getMessage());
        }
        finally {
            return json_encode(array('exito' => $exito,'comportamientos' => $comportamientos));
        }
        
    }
    
    
}