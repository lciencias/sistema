<?php

namespace sistema\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use sistema\Models\PerfilPuesto;
use sistema\Policies\Constantes;
use sistema\Repositories\PerfilPuestoRepository;


class PerfilPuestoController extends Controller
{
    private $perfilPuestoRepository;
    private $controller;
    private $moduloId;
    private $clientes;
    private $niveles;
    private $talentos;
    private $pruebas;
    private $nivelesImportancia;
    /**
     * Metodo Constructor de la Clase PerfilPuestos
     * @param PerfilPuestoRepository $perfilPuestoRepository
     */
    public function __construct(PerfilPuestoRepository $perfilPuestoRepository)
    {
    	parent::__construct();
        $this->perfilPuestoRepository = $perfilPuestoRepository;
        $this->controller = Constantes::CONTROLLER_PERFIL_PUESTO;
        $this->moduloId   = Constantes::MODULO_ID_PERFIL_PUESTO;
        $this->clientes = $this->perfilPuestoRepository->getClientes(Constantes::ESTATUS_TODOS);
        $this->niveles = Constantes::CATALOGO_NIVELES_PUESTO;
        $this->nivelesImportancia = Constantes::CATALOGO_NIVELES_IMPORTANCIA;
        $this->talentos = $this->perfilPuestoRepository->getTalentos(Constantes::ESTATUS_TODOS);
        $this->pruebas = $this->perfilPuestoRepository->getPruebas(Constantes::ESTATUS_TODOS);
    }
    
    /**
     * Metodo que muestra el listado de perfilPuestos
     * @param Request $request
     */
    public function index(Request $request){
        Session::forget('message-warning');
        if ($request) {
            return view ( 'gestion.perfilPuesto.indexPerfilPuesto', [
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
    	$opciones   = $this->parametros($request, 'idperfil_puesto');
    	$view = $this->buscaPerfilPuestos($request,$opciones,$moduloId);
    	return response()->json(view('gestion.perfilPuesto.busquedaPerfilPuesto',$view)->render());
    }
    

    /**
     * Realiza el llamado para la busqueda de perfilPuestos
     * @param Request $request
     * @param $opciones
     * @param $moduloId
     */
    private function buscaPerfilPuestos(Request $request,$opciones,$moduloId){
    	$session    = Session()->get('permisos');
    	$filtros  	= $this->generaFiltrosPerfilPuestos($request);
    	$total      = $this->perfilPuestoRepository->findByCount($filtros);
    	$activo = $request->get('activoPerfilPuestoBusca');
    	if($activo == null)
    	    $activo = "-1";
    	
    	$perfilPuestos = $this->perfilPuestoRepository->findByColumn( $filtros,$opciones );
    	
    	
    	return [
    			"idperfilPuesto"            => $request->get('idperfilPuesto'),
    			"total"      		   => $total,
    			"leyenda"    		   => $this->perfilPuestoRepository->generaLetrero($total,count($perfilPuestos),$opciones),
    			"nombrePerfilPuestoBusca"   => $request->get('nombrePerfilPuestoBusca'),
    	        "nivelBusca"   => $request->get('nivelBusca'),
    	        "idclienteEjercicioBusca"        => $request->get ('idclienteEjercicioBusca'),
    	        "clientes"        => $this->clientes,
    	        "catNiveles"        => $this->niveles,
    	        "activoPerfilPuestoBusca"   => $activo,
    			"perfilPuestos"        	   => $perfilPuestos,
    			"moduloId" 		       => $moduloId,
    			"noPage" 	  		   => $opciones['nopage'],
    			"noRegs" 	   		   => $opciones['noregs'],
    			"isAdmin" 		       => $this->isAdmin,
    			"sessionPermisos"      => $session[$moduloId],
    			"catEstatus"      => array(1 => 'Activo',0=> 'No Activo')
    	];
    }
    
    /**
     * Metodo que genera el filtro para la busqueda de perfilPuestos
     * @param Request $request
     * @return multitype:multitype:string
     */
    private function generaFiltrosPerfilPuestos(Request $request){
    	$filtros = array ();
    	$nombre = $request->get('nombrePerfilPuestoBusca');
    	$descripcion = $request->get('descripcionPerfilPuestoBusca');
    	$activo = $request->get('activoPerfilPuestoBusca');
    	if($activo == null)
    	    $activo = "-1";
    
    	if(trim($nombre) != ''){
    		$filtros[] = ['perfilPuesto.nombre', 'LIKE', '%' . $nombre . '%'];
    	}
    	if(trim($descripcion) != ''){
    	    $filtros[] = ['perfilPuesto.definicion', 'LIKE', '%' . $descripcion . '%'];
    	}
    	if(trim($activo) != '-1'){
    		$filtros[] = ['perfilPuesto.activo', '=', $activo];
    	}
    	return $filtros;
    }
    
    
    /**
     * Metodo para crear una perfilPuesto
     */
    public function create()
    {
    	Session()->put('editar',false);
    	$perfilPuesto = new PerfilPuesto();
    	$perfilPuesto->nombre = '';
    	$perfilPuesto->descripcion = '';
    	$perfilPuesto->etapa = Constantes::ETAPA_PRUEBA_INICIAL;
    	$session    = Session()->get('permisos'); 
    	
        return view("gestion.perfilPuesto.createPerfilPuesto", [
                "sessionPermisos" => $session[$this->moduloId],
        		"perfilPuesto" => $perfilPuesto,
                "etapa" => Constantes::ETAPA_PRUEBA_INICIAL,
            "clientes"        => $this->clientes,
            "catNiveles"        => $this->niveles,
        		"perfilPuestoIdUser" => Session::get ( 'userEnterprise' ),
                "idModulo" => $this->moduloId] );
    }
    
    /**
     * Metodo que guarda la información de una perfilPuesto
     * @param $request
     */
 	public function store (Request $request)
    {
        try {
    		$this->perfilPuestoRepository->savePerfilPuesto( $request);
        	Session::flash ( 'message', Lang::get ( 'general.success' ) );
        } catch ( \Exception $e ) {
        	$this->log->error ($e);
        	Session::flash ( 'message-error', Lang::get ( 'general.error' ) );
        }
        return Redirect::to ( 'gestion/perfilPuesto');

    }
    
    /**
     * Metodo para obtener los datos de una perfilPuesto que se edita
     * @param int $id
     */
    public function edit($id)
    {
    	$id =  Crypt::decrypt($id);
    	Session()->put('editar',true);
    	$perfilPuesto = $this->perfilPuestoRepository->getPerfilPuesto($id);
    	$session    = Session()->get('permisos');
    	$preguntasPerfilPuesto = array();
//     	$resultadosPerfilPuesto = array();
//     	$interpretacionesPerfilPuesto = array();
//     	$interpretacionesPerfilPuesto = array();
    	
//     	if($perfilPuesto->etapa == Constantes::ETAPA_PRUEBA_POR_CARGAR_PREGUNTAS) {
//     	    $resultadosPerfilPuesto = $this->perfilPuestoRepository->getResultadosPerfilPuesto($id, Constantes::ESTATUS_TODOS);
    	    
//     	}
//     	else if($perfilPuesto->etapa == Constantes::ETAPA_PRUEBA_POR_CARGAR_INTERPRETACION) {
//     	    $resultadosPerfilPuesto = $this->perfilPuestoRepository->getResultadosPerfilPuesto($id, Constantes::ESTATUS_TODOS);
//     	    $preguntasPerfilPuesto = $this->perfilPuestoRepository->getPreguntasPerfilPuesto($id, Constantes::ESTATUS_TODOS);
    	    
//     	} 
//     	else if($perfilPuesto->etapa == Constantes::ETAPA_PRUEBA_FINAL) {
//     	    $resultadosPerfilPuesto = $this->perfilPuestoRepository->getResultadosPerfilPuesto($id, Constantes::ESTATUS_TODOS);
//     	    $preguntasPerfilPuesto = $this->perfilPuestoRepository->getPreguntasPerfilPuesto($id, Constantes::ESTATUS_TODOS);
//     	    $interpretacionesPerfilPuesto = array();
//     	}
    	
    	$etapa = $perfilPuesto->etapa;
    	
    	return view("gestion.perfilPuesto.editPerfilPuesto",[
    	        "sessionPermisos" => $session[$this->moduloId],
    	       "idModulo"=>$this->moduloId,
    	    "etapa" => $etapa,
    	    "clientes"        => $this->clientes,
    	    "talentos"        => $this->talentos,
    	    "pruebas"        => $this->pruebas,
    	    "catNiveles"        => $this->niveles,
    	    "catNivelesImportancia"        => $this->nivelesImportancia,
    	    "talentosPerfilPuesto"        => array(),
    	    "pruebasPerfilPuesto"        => array(),
    			"perfilPuestoIdUser" => Session::get ( 'userEnterprise' ),
    			"perfilPuesto"=>$perfilPuesto]);
    }
    
    /**
     * Metodo que actualiza la información de la perfilPuesto
     * @param $request
     */
 	public function update(Request $request, $id)
    {    
    	try {
    		if($request){   
    			$this->perfilPuestoRepository->updatePerfilPuesto( $request ,$id);
    			Session::flash ( 'message', Lang::get ( 'general.success' ) );
    			
    		}
    	} 
		catch (\Exception $e) {
            $this->log->error($e);
			Session::flash ( 'message-error', Lang::get ( 'general.error' ) );
		}
		finally {
			 return Redirect::to('gestion/perfilPuesto');
		}
    }
    
    
    
    /**
     * Metodo para activar la perfilPuesto
     * @param Request $request
     */
    public function activar(Request $request){
    	$exito = 0;
    	$msg   = Lang::get ( 'general.error' );
    	try{
    		$id = $request->get('idPerfilPuesto');
    		if((int) $id > 0){
    			$this->perfilPuestoRepository->activaPerfilPuesto($id);
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
     * Para desactivar la perfilPuesto
     * @param Request $request
     * @return string
     */
    public function desactivar(Request $request){
    	$exito = 0;
    	$msg   = Lang::get ( 'general.error' );
    	try{
    		$id = $request->get('idPerfilPuesto');
    		if((int) $id > 0){
    			$this->perfilPuestoRepository->eliminaPerfilPuesto($id);
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
    	if(!$this->perfilPuestoRepository->validaNombreElemento($request)){
    		$response = array('valid' => true ,  'message' => '');
    	} else {
    		$response = array('valid' => false,  'message' => Lang::get ( 'leyendas.nombreExistente' ));
    	}
    	print_r(json_encode($response));
    }
    
    
    
    
}