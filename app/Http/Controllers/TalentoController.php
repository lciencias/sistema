<?php

namespace sistema\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use sistema\Models\Talento;
use sistema\Policies\Constantes;
use sistema\Repositories\TalentoRepository;


class TalentoController extends Controller
{
    private $talentoRepository;
    private $controller = Constantes::CONTROLLER_TALENTO;
    private $moduloId = Constantes::MODULO_ID_TALENTO;
	 
    /**
     * Metodo Constructor de la Clase Talentos
     * @param TalentoRepository $talentoRepository
     */
    public function __construct(TalentoRepository $talentoRepository)
    {
    	parent::__construct();
        $this->talentoRepository = $talentoRepository;
    }
    
    /**
     * Metodo que muestra el listado de talentos
     * @param Request $request
     */
    public function index(Request $request){
        Session::forget('message-warning');
        if ($request) {
            return view ( 'catalogos.talento.indexTalento', [
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
    	$opciones   = $this->parametros($request, 'idtalento');
    	$view = $this->buscaTalentos($request,$opciones,$moduloId);
    	return response()->json(view('catalogos.talento.busquedaTalento',$view)->render());
    }
    

    /**
     * Realiza el llamado para la busqueda de talentos
     * @param Request $request
     * @param $opciones
     * @param $moduloId
     */
    private function buscaTalentos(Request $request,$opciones,$moduloId){
    	$session    = Session()->get('permisos');
    	$filtros  	= $this->generaFiltrosTalentos($request);
    	$total      = $this->talentoRepository->findByCount($filtros);
    	$activo = $request->get('activoTalentoBusca');
    	if($activo == null)
    	    $activo = "-1";
    	    $talentos = $this->talentoRepository->findByColumn( $filtros,$opciones );
    	
    	return [
    			"idtalento"            => $request->get('idtalento'),
    			"total"      		   => $total,
    			"leyenda"    		   => $this->talentoRepository->generaLetrero($total,count($talentos),$opciones),
    			"nombreTalentoBusca"   => $request->get('nombreTalentoBusca'),
    	        "descripcionTalentoBusca"   => $request->get('descripcionTalentoBusca'),
    	        "activoTalentoBusca"   => $activo,
    			"talentos"        	   => $talentos,
    			"moduloId" 		       => $moduloId,
    			"noPage" 	  		   => $opciones['nopage'],
    			"noRegs" 	   		   => $opciones['noregs'],
    			"isAdmin" 		       => $this->isAdmin,
    			"sessionPermisos"      => $session[$moduloId],
    			"catEstatus"      => array(1 => 'Activo',0=> 'No Activo')
    	];
    }
    
    /**
     * Metodo que genera el filtro para la busqueda de talentos
     * @param Request $request
     * @return multitype:multitype:string
     */
    private function generaFiltrosTalentos(Request $request){
    	$filtros = array ();
    	$nombre = $request->get('nombreTalentoBusca');
    	$definicion = $request->get('descripcionTalentoBusca');
    	$activo = $request->get('activoTalentoBusca');
    	if($activo == null)
    	    $activo = "-1";
    
    	if(trim($nombre) != ''){
    		$filtros[] = ['talento.nombre', 'LIKE', '%' . $nombre . '%'];
    	}
    	if(trim($definicion) != ''){
    	    $filtros[] = ['talento.definicion', 'LIKE', '%' . $definicion . '%'];
    	}
    	if(trim($activo) != '-1'){
    		$filtros[] = ['talento.activo', '=', $activo];
    	}
    	return $filtros;
    }
    
    
    /**
     * Metodo para crear una talento
     */
    public function create()
    {
    	Session()->put('editar',false);
    	$talento = new Talento();
    	$talento->nombre = '';
    	$talento->descripcion = '';
    	$session    = Session()->get('permisos');    	 
        return view("catalogos.talento.createTalento", [
                "sessionPermisos" => $session[$this->moduloId],
        		"talento" => $talento,
        		"talentoIdUser" => Session::get ( 'userEnterprise' ),
                "idModulo" => $this->moduloId] );
    }
    
    /**
     * Metodo que guarda la información de una talento
     * @param $request
     */
 	public function store (Request $request)
    {
        try {
    		$this->talentoRepository->saveTalento( $request);
        	Session::flash ( 'message', Lang::get ( 'general.success' ) );
        } catch ( \Exception $e ) {
        	$this->log->error ($e);
        	Session::flash ( 'message-error', Lang::get ( 'general.error' ) );
        }
        return Redirect::to ( 'catalogos/talento');

    }
    
    /**
     * Metodo para obtener los datos de una talento que se edita
     * @param int $id
     */
    public function edit($id)
    {
    	$id =  Crypt::decrypt($id);
    	Session()->put('editar',true);
    	$talento = $this->talentoRepository->getTalento($id);
    	$session    = Session()->get('permisos');
    	return view("catalogos.talento.editTalento",[
    	        "sessionPermisos" => $session[$this->moduloId],
    	        "idModulo"=>$this->moduloId,
    			"talentoIdUser" => Session::get ( 'userEnterprise' ),
    			"talento"=>$talento]);
    }
    
    /**
     * Metodo que actualiza la información de la talento
     * @param $request
     */
 	public function update(Request $request, $id)
    {    
    	try {
    		if($request){   
    			$this->talentoRepository->updateTalento( $request ,$id);
    			Session::flash ( 'message', Lang::get ( 'general.success' ) );
    			
    		}
    	} 
		catch (\Exception $e) {
            $this->log->error($e);
			Session::flash ( 'message-error', Lang::get ( 'general.error' ) );
		}
		finally {
			 return Redirect::to('catalogos/talento');
		}
    }
    
    
    
    /**
     * Metodo para activar la talento
     * @param Request $request
     */
    public function activar(Request $request){
    	$exito = 0;
    	$msg   = Lang::get ( 'general.error' );
    	try{
    		$id = $request->get('idTalento');
    		if((int) $id > 0){
    			$this->talentoRepository->activaTalento($id);
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
     * Para desactivar la talento
     * @param Request $request
     * @return string
     */
    public function desactivar(Request $request){
    	$exito = 0;
    	$msg   = Lang::get ( 'general.error' );
    	try{
    		$id = $request->get('idTalento');
    		if((int) $id > 0){
    			$this->talentoRepository->eliminaTalento($id);
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
    	if(!$this->talentoRepository->validaNombreElemento($request)){
    		$response = array('valid' => true ,  'message' => '');
    	} else {
    		$response = array('valid' => false,  'message' => Lang::get ( 'leyendas.nombreExistente' ));
    	}
    	print_r(json_encode($response));
    }
    
    
    
}