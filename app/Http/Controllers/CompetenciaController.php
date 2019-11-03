<?php

namespace sistema\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use sistema\Models\Competencia;
use sistema\Policies\Constantes;
use sistema\Repositories\CompetenciaRepository;


class CompetenciaController extends Controller
{
    private $competenciaRepository;
    private $controller;
    private $moduloId;
    private $tiposCompetencia;
    /**
     * Metodo Constructor de la Clase Competencias
     * @param CompetenciaRepository $competenciaRepository
     */
    public function __construct(CompetenciaRepository $competenciaRepository)
    {
    	parent::__construct();
        $this->competenciaRepository = $competenciaRepository;
        $this->controller = Constantes::CONTROLLER_COMPETENCIA;
        $this->moduloId   = Constantes::MODULO_ID_COMPETENCIA;
        $this->tiposCompetencia = $this->competenciaRepository->getTiposCompetencia(Constantes::ESTATUS_ACTIVO);
    }
    
    /**
     * Metodo que muestra el listado de competencias
     * @param Request $request
     */
    public function index(Request $request){
        Session::forget('message-warning');
        if ($request) {
            return view ( 'catalogos.competencia.indexCompetencia', [
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
    	$opciones   = $this->parametros($request, 'idcompetencia');
    	$view = $this->buscaCompetencias($request,$opciones,$moduloId);
    	return response()->json(view('catalogos.competencia.busquedaCompetencia',$view)->render());
    }
    

    /**
     * Realiza el llamado para la busqueda de competencias
     * @param Request $request
     * @param $opciones
     * @param $moduloId
     */
    private function buscaCompetencias(Request $request,$opciones,$moduloId){
    	$session    = Session()->get('permisos');
    	$filtros  	= $this->generaFiltrosCompetencias($request);
    	$total      = $this->competenciaRepository->findByCount($filtros);
    	$activo = $request->get('activoCompetenciaBusca');
    	if($activo == null)
    	    $activo = "-1";
    	
    	$catTipoCompetencias = $this->tiposCompetencia;
    	$competencias = $this->competenciaRepository->findByColumn( $filtros,$opciones );
    	
    	
    	return [
    			"idcompetencia"            => $request->get('idcompetencia'),
    			"total"      		   => $total,
    			"leyenda"    		   => $this->competenciaRepository->generaLetrero($total,count($competencias),$opciones),
    			"nombreCompetenciaBusca"   => $request->get('nombreCompetenciaBusca'),
    	        "definicionCompetenciaBusca"   => $request->get('definicionCompetenciaBusca'),
    	        "activoCompetenciaBusca"   => $activo,
    	         "tipoCompetenciaBusca"   => $request->get('tipoCompetenciaBusca'),
    			"competencias"        	   => $competencias,
    			"moduloId" 		       => $moduloId,
    			"noPage" 	  		   => $opciones['nopage'],
    			"noRegs" 	   		   => $opciones['noregs'],
    			"isAdmin" 		       => $this->isAdmin,
    	        "catTipoCompetencias" => $catTipoCompetencias,
    			"sessionPermisos"      => $session[$moduloId],
    			"catEstatus"      => array(1 => 'Activo',0=> 'No Activo')
    	];
    }
    
    /**
     * Metodo que genera el filtro para la busqueda de competencias
     * @param Request $request
     * @return multitype:multitype:string
     */
    private function generaFiltrosCompetencias(Request $request){
    	$filtros = array ();
    	$nombre = $request->get('nombreCompetenciaBusca');
    	$definicion = $request->get('definicionCompetenciaBusca');
    	$tipo = $request->get('tipoCompetenciaBusca');
    	$activo = $request->get('activoCompetenciaBusca');
    	if($activo == null)
    	    $activo = "-1";
    
    	if(trim($nombre) != ''){
    		$filtros[] = ['competencia.nombre', 'LIKE', '%' . $nombre . '%'];
    	}
    	if(trim($definicion) != ''){
    	    $filtros[] = ['competencia.definicion', 'LIKE', '%' . $definicion . '%'];
    	}
    	if($tipo != '' && $tipo != '-1'){
    	    $filtros[] = ['competencia.idtipo_competencia', '=', $tipo];
    	}
    	if(trim($activo) != '-1'){
    		$filtros[] = ['competencia.activo', '=', $activo];
    	}
    	return $filtros;
    }
    
    
    /**
     * Metodo para crear una competencia
     */
    public function create()
    {
    	Session()->put('editar',false);
    	$competencia = new Competencia();
    	$competencia->nombre = '';
    	$competencia->definicion = '';
    	$session    = Session()->get('permisos'); 
    	$catTipoCompetencias =   $this->tiposCompetencia;
        return view("catalogos.competencia.createCompetencia", [
                "sessionPermisos" => $session[$this->moduloId],
        		"competencia" => $competencia,
                "catTipoCompetencias" => $catTipoCompetencias,
                "comportamientos" =>array(),
        		"competenciaIdUser" => Session::get ( 'userEnterprise' ),
                "idModulo" => $this->moduloId] );
    }
    
    /**
     * Metodo que guarda la información de una competencia
     * @param $request
     */
 	public function store (Request $request)
    {
        try {
    		$this->competenciaRepository->saveCompetencia( $request);
        	Session::flash ( 'message', Lang::get ( 'general.success' ) );
        } catch ( \Exception $e ) {
        	$this->log->error ($e);
        	Session::flash ( 'message-error', Lang::get ( 'general.error' ) );
        }
        return Redirect::to ( 'catalogos/competencia');

    }
    
    /**
     * Metodo para obtener los datos de una competencia que se edita
     * @param int $id
     */
    public function edit($id)
    {
    	$id =  Crypt::decrypt($id);
    	Session()->put('editar',true);
    	$competencia = $this->competenciaRepository->getCompetencia($id);
    	$session    = Session()->get('permisos');
    	$comportamientos = $this->competenciaRepository->getComportamientosCompetencia($id, Constantes::ESTATUS_ACTIVO);
    	$catTipoCompetencias = $this->tiposCompetencia;
    	return view("catalogos.competencia.editCompetencia",[
    	        "sessionPermisos" => $session[$this->moduloId],
    	       "idModulo"=>$this->moduloId,
    	       "catTipoCompetencias" => $catTipoCompetencias,
    	       "comportamientos" => $comportamientos,
    			"competenciaIdUser" => Session::get ( 'userEnterprise' ),
    			"competencia"=>$competencia]);
    }
    
    /**
     * Metodo que actualiza la información de la competencia
     * @param $request
     */
 	public function update(Request $request, $id)
    {    
    	try {
    		if($request){   
    			$this->competenciaRepository->updateCompetencia( $request ,$id);
    			Session::flash ( 'message', Lang::get ( 'general.success' ) );
    			
    		}
    	} 
		catch (\Exception $e) {
            $this->log->error($e);
			Session::flash ( 'message-error', Lang::get ( 'general.error' ) );
		}
		finally {
			 return Redirect::to('catalogos/competencia');
		}
    }
    
    
    
    /**
     * Metodo para activar la competencia
     * @param Request $request
     */
    public function activar(Request $request){
    	$exito = 0;
    	$msg   = Lang::get ( 'general.error' );
    	try{
    		$id = $request->get('idCompetencia');
    		if((int) $id > 0){
    			$this->competenciaRepository->activaCompetencia($id);
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
     * Para desactivar la competencia
     * @param Request $request
     * @return string
     */
    public function desactivar(Request $request){
    	$exito = 0;
    	$msg   = Lang::get ( 'general.error' );
    	try{
    		$id = $request->get('idCompetencia');
    		if((int) $id > 0){
    			$this->competenciaRepository->eliminaCompetencia($id);
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
    	if(!$this->competenciaRepository->validaNombreElemento($request)){
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
                $nivelesCalificacion = $this->competenciaRepository->getNivelesCalificacionComportamiento($id);
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
     * Metodo que busca los comportamientos asociados a una competencia
     * @param Request $request
     */
    public function buscaComportamientos(Request $request){
        $exito = 0;
        $comportamientos = array();
        try{
            $id = $request->get('idCompetencia');
            if((int) $id > 0){
                $comportamientos = $this->competenciaRepository->getComportamientosCompetencia($id, Constantes::ESTATUS_ACTIVO);
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