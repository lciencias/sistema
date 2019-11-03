<?php

namespace sistema\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use sistema\Models\Prueba;
use sistema\Policies\Constantes;
use sistema\Repositories\PruebaRepository;


class PruebaController extends Controller
{
    private $pruebaRepository;
    private $controller;
    private $moduloId;
    /**
     * Metodo Constructor de la Clase Pruebas
     * @param PruebaRepository $pruebaRepository
     */
    public function __construct(PruebaRepository $pruebaRepository)
    {
    	parent::__construct();
        $this->pruebaRepository = $pruebaRepository;
        $this->controller = Constantes::CONTROLLER_PRUEBA;
        $this->moduloId   = Constantes::MODULO_ID_PRUEBA;
    }
    
    /**
     * Metodo que muestra el listado de pruebas
     * @param Request $request
     */
    public function index(Request $request){
        Session::forget('message-warning');
        if ($request) {
            return view ( 'catalogos.prueba.indexPrueba', [
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
    	$opciones   = $this->parametros($request, 'idprueba');
    	$view = $this->buscaPruebas($request,$opciones,$moduloId);
    	return response()->json(view('catalogos.prueba.busquedaPrueba',$view)->render());
    }
    

    /**
     * Realiza el llamado para la busqueda de pruebas
     * @param Request $request
     * @param $opciones
     * @param $moduloId
     */
    private function buscaPruebas(Request $request,$opciones,$moduloId){
    	$session    = Session()->get('permisos');
    	$filtros  	= $this->generaFiltrosPruebas($request);
    	$total      = $this->pruebaRepository->findByCount($filtros);
    	$activo = $request->get('activoPruebaBusca');
    	if($activo == null)
    	    $activo = "-1";
    	
    	$pruebas = $this->pruebaRepository->findByColumn( $filtros,$opciones );
    	
    	
    	return [
    			"idprueba"            => $request->get('idprueba'),
    			"total"      		   => $total,
    			"leyenda"    		   => $this->pruebaRepository->generaLetrero($total,count($pruebas),$opciones),
    			"nombrePruebaBusca"   => $request->get('nombrePruebaBusca'),
    	        "descripcionPruebaBusca"   => $request->get('descripcionPruebaBusca'),
    	        "activoPruebaBusca"   => $activo,
    			"pruebas"        	   => $pruebas,
    			"moduloId" 		       => $moduloId,
    			"noPage" 	  		   => $opciones['nopage'],
    			"noRegs" 	   		   => $opciones['noregs'],
    			"isAdmin" 		       => $this->isAdmin,
    			"sessionPermisos"      => $session[$moduloId],
    			"catEstatus"      => array(1 => 'Activo',0=> 'No Activo')
    	];
    }
    
    /**
     * Metodo que genera el filtro para la busqueda de pruebas
     * @param Request $request
     * @return multitype:multitype:string
     */
    private function generaFiltrosPruebas(Request $request){
    	$filtros = array ();
    	$nombre = $request->get('nombrePruebaBusca');
    	$descripcion = $request->get('descripcionPruebaBusca');
    	$activo = $request->get('activoPruebaBusca');
    	if($activo == null)
    	    $activo = "-1";
    
    	if(trim($nombre) != ''){
    		$filtros[] = ['prueba.nombre', 'LIKE', '%' . $nombre . '%'];
    	}
    	if(trim($descripcion) != ''){
    	    $filtros[] = ['prueba.definicion', 'LIKE', '%' . $descripcion . '%'];
    	}
    	if(trim($activo) != '-1'){
    		$filtros[] = ['prueba.activo', '=', $activo];
    	}
    	return $filtros;
    }
    
    
    /**
     * Metodo para crear una prueba
     */
    public function create()
    {
    	Session()->put('editar',false);
    	$prueba = new Prueba();
    	$prueba->nombre = '';
    	$prueba->descripcion = '';
    	$prueba->etapa = Constantes::ETAPA_PRUEBA_INICIAL;
    	$session    = Session()->get('permisos'); 
    	
        return view("catalogos.prueba.createPrueba", [
                "sessionPermisos" => $session[$this->moduloId],
        		"prueba" => $prueba,
                "etapa" => Constantes::ETAPA_PRUEBA_INICIAL,
                "catTipoInterprestacionPrueba" => Constantes::CATALOGO_TIPOS_INTERPRETACION,
                "preguntasPrueba" =>array(),
                "resultadosPrueba" =>array(),
            "interpretacionesPrueba" =>array(),
            "visible" =>"hidden='true'",
        		"pruebaIdUser" => Session::get ( 'userEnterprise' ),
                "idModulo" => $this->moduloId] );
    }
    
    /**
     * Metodo que guarda la información de una prueba
     * @param $request
     */
 	public function store (Request $request)
    {
        try {
    		$this->pruebaRepository->savePrueba( $request);
        	Session::flash ( 'message', Lang::get ( 'general.success' ) );
        } catch ( \Exception $e ) {
        	$this->log->error ($e);
        	Session::flash ( 'message-error', Lang::get ( 'general.error' ) );
        }
        return Redirect::to ( 'catalogos/prueba');

    }
    
    /**
     * Metodo para obtener los datos de una prueba que se edita
     * @param int $id
     */
    public function edit($id)
    {
    	$id =  Crypt::decrypt($id);
    	Session()->put('editar',true);
    	$prueba = $this->pruebaRepository->getPrueba($id);
    	$session    = Session()->get('permisos');
    	$preguntasPrueba = array();
    	$resultadosPrueba = array();
    	$interpretacionesPrueba = array();
    	$interpretacionesPrueba = array();
    	
    	if($prueba->etapa == Constantes::ETAPA_PRUEBA_POR_CARGAR_PREGUNTAS) {
    	    $resultadosPrueba = $this->pruebaRepository->getResultadosPrueba($id, Constantes::ESTATUS_TODOS);
    	    
    	}
    	else if($prueba->etapa == Constantes::ETAPA_PRUEBA_POR_CARGAR_INTERPRETACION) {
    	    $resultadosPrueba = $this->pruebaRepository->getResultadosPrueba($id, Constantes::ESTATUS_TODOS);
    	    $preguntasPrueba = $this->pruebaRepository->getPreguntasPrueba($id, Constantes::ESTATUS_TODOS);
    	    
    	} 
    	else if($prueba->etapa == Constantes::ETAPA_PRUEBA_FINAL) {
    	    $resultadosPrueba = $this->pruebaRepository->getResultadosPrueba($id, Constantes::ESTATUS_TODOS);
    	    $preguntasPrueba = $this->pruebaRepository->getPreguntasPrueba($id, Constantes::ESTATUS_TODOS);
    	    $interpretacionesPrueba = $this->pruebaRepository->getInterpretacionesPrueba($id, Constantes::ESTATUS_TODOS);
    	}
    	
    	$etapa = $prueba->etapa;
    	
    	return view("catalogos.prueba.editPrueba",[
    	        "sessionPermisos" => $session[$this->moduloId],
    	       "idModulo"=>$this->moduloId,
    	    "preguntasPrueba" =>$preguntasPrueba,
    	    "etapa" => $etapa,
    	    "resultadosPrueba" =>$resultadosPrueba,
    	    "interpretacionesPrueba" =>$interpretacionesPrueba,
    	    "visible" =>"",
    	    "catTipoInterprestacionPrueba" => Constantes::CATALOGO_TIPOS_INTERPRETACION,
    			"pruebaIdUser" => Session::get ( 'userEnterprise' ),
    			"prueba"=>$prueba]);
    }
    
    /**
     * Metodo que actualiza la información de la prueba
     * @param $request
     */
 	public function update(Request $request, $id)
    {    
    	try {
    		if($request){   
    			$this->pruebaRepository->updatePrueba( $request ,$id);
    			Session::flash ( 'message', Lang::get ( 'general.success' ) );
    			
    		}
    	} 
		catch (\Exception $e) {
            $this->log->error($e);
			Session::flash ( 'message-error', Lang::get ( 'general.error' ) );
		}
		finally {
			 return Redirect::to('catalogos/prueba');
		}
    }
    
    
    
    /**
     * Metodo para activar la prueba
     * @param Request $request
     */
    public function activar(Request $request){
    	$exito = 0;
    	$msg   = Lang::get ( 'general.error' );
    	try{
    		$id = $request->get('idPrueba');
    		if((int) $id > 0){
    			$this->pruebaRepository->activaPrueba($id);
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
     * Para desactivar la prueba
     * @param Request $request
     * @return string
     */
    public function desactivar(Request $request){
    	$exito = 0;
    	$msg   = Lang::get ( 'general.error' );
    	try{
    		$id = $request->get('idPrueba');
    		if((int) $id > 0){
    			$this->pruebaRepository->eliminaPrueba($id);
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
    	if(!$this->pruebaRepository->validaNombreElemento($request)){
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
                $nivelesCalificacion = $this->pruebaRepository->getNivelesCalificacionComportamiento($id);
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
     * Metodo que busca los comportamientos asociados a una prueba
     * @param Request $request
     */
    public function buscaComportamientos(Request $request){
        $exito = 0;
        $comportamientos = array();
        try{
            $id = $request->get('idPrueba');
            if((int) $id > 0){
                $comportamientos = $this->pruebaRepository->getComportamientosPrueba($id, Constantes::ESTATUS_ACTIVO);
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