<?php

namespace sistema\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use sistema\Models\Ejercicio;
use sistema\Policies\Constantes;
use sistema\Repositories\EjercicioRepository;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use sistema\Models\TipoEjercicioCliente;

/**
 *
 * @author Miguel Angel Molina 05/04/2017
 *         Clase controladora para los ejercicios
 */
class EjercicioController extends Controller {
	private $ejercicioRepository;
	private $controller;
	private $moduloId;
	private $competencias;
	private $clientes;
	private $tiposEjercicios;
	
	public function __construct(EjercicioRepository $ejercicioRepository) {
		parent::__construct();
		$this->ejercicioRepository = $ejercicioRepository;
		$this->controller = Constantes::CONTROLLER_EJERCICIO;
		$this->moduloId = Constantes::MODULO_ID_EJERCICIO;
		$this->competencias = $this->ejercicioRepository->getCompetencias(Constantes::ESTATUS_ACTIVO);
		$this->clientes = $this->ejercicioRepository->getClientes(Constantes::ESTATUS_TODOS);
		$this->tiposEjercicios = $this->ejercicioRepository->getTiposEjercicios(Constantes::ESTATUS_TODOS);
		
	}
	
	/**
	 * Se encarga de listar todos los ejercicios seg�n los par�metros de busqueda
	 * 
	 * @param Request $request        	
	 */
	public function index(Request $request) {	
		Session::forget('message-warning');
        if ($request) {
            return view ( 'catalogos.ejercicio.indexEjercicio', [
                "isAdmin"     => Session::get ('isAdmin'),
                "moduloId"    => $this->moduloId,
                "controller"  => $this->controller
            ] );
        }
	}
	
	public function buscar(Request $request){
		Session::forget('message-warning');
		$moduloId   = $request->get('idModulo');
		$opciones   = $this->parametros($request, 'idtipo_ejercicio_cliente');
		$view = $this->buscaEjercicios($request,$opciones,$moduloId);
		return response()->json(view('catalogos.ejercicio.busquedaEjercicio',$view)->render());
	}
	
	
	private function buscaEjercicios(Request $request,$opciones,$moduloId){
		$ejercicios   = array();
		$session    = Session()->get('permisos');
	
		$filtros    = $this->generaFiltrosEjercicio($request);
		Session::put('busqueda_Ejercicios', $filtros);
		 
		$total      = $this->ejercicioRepository->findByCount($filtros);
		$ejercicios   = $this->ejercicioRepository->findByColumn($filtros,$opciones);
	
	
		$activo = '-1';
		if($request->get ('activo') != null && $request->get ('activo') != '')
			$activo = $request->get ('activo');
	
		$idempresa = '-1';
		if($request->get ('empresa') != null && $request->get ('empresa') != '')
			$idempresa = $request->get ('empresa');
	
		return [
				"ejercicios"        => $ejercicios,
				"total"           => $total,
				"leyenda"     	  => $this->ejercicioRepository->generaLetrero($total,count($ejercicios),$opciones),
				"isAdmin"         => Session::get ('isAdmin'),
				"idclienteEjercicioBusca"        => $request->get ('idclienteEjercicioBusca'),
				"idTipoEjercicioBusca"          => trim ($request->get ('idTipoEjercicioBusca')),
				"activo"          => $request->get ('activo'),
				"moduloId"        => $moduloId,
				"noPage" 	      => $opciones['nopage'],
				"noRegs" 	      => $opciones['noregs'],
				"sessionPermisos" => $session[$moduloId],
		        "clientes"        => $this->clientes,
		        "tiposEjercicios" => $this->tiposEjercicios,
				"empresaIdUser"   => Session::get ( 'userEnterprise' ),
				"activo"          => $activo,
				"catEstatus"      => array(1 => 'Activo',0=> 'No Activo')
		];
	}
	
	
	private function generaFiltrosEjercicio(Request $request){
		$idcliente    = $request->get ('idclienteEjercicioBusca');
		$idtipoEjer   = $request->get ('idTipoEjercicioBusca');
		$activo      = $request->get ('activo');
		$filtros   = array ();

		if(trim($idcliente) != ''){
		    $filtros[] = ['tipo_ejercicio_cliente.idcliente', '=', $idcliente];
		}
		if(trim($activo) != '' && trim($activo) != '-1'){
			$filtros[] = ['tipo_ejercicio_cliente.activo', '=', $activo];
		}
		if(trim($idtipoEjer) != ''){
		    $filtros[] = ['tipo_ejercicio_cliente.idtipo_ejercicio', '=', $idtipoEjer];
		}
		return $filtros;
	}
	public function create() {
		Session()->put('editar',false);
		$ejercicio = new Ejercicio ();
		$ejercicio->nombre = '';
		$ejercicio->descripcion = '';
		$ejercicio->idempresa = '' ;
		$session    = Session()->get('permisos');
		return view ( "catalogos.ejercicio.createEjercicio", [
				"ejercicio" => $ejercicio,
				"competencias" => $this->competencias,
		        "ejercicioCompetencias" => array(),
		        "ejercicioCompetenciasComportamientos" => array(),
		        "clientes" => $this->clientes,
		        "tiposEjercicios" => $this->tiposEjercicios,
				"empresaIdUser" => Session::get ( 'userEnterprise' ),
		        "sessionPermisos" => $session[$this->moduloId],
		        "idModulo" => $this->moduloId,
				"isAdmin"  => Session::get ('isAdmin'),
				"disabled" => ''
		] );
	}

    /**
     * @param  $request
     * @return mixed
     */
    public function store(Request $request) {
		try {
			if($request->isMethod('post')){
				
				$this->ejercicioRepository->save( $request );
				Session::flash ( 'message', Lang::get ( 'general.success' ) );
			}
		} catch ( \Exception $e ) {
			$this->log->error($e);
			Session::flash ( 'message-error', Lang::get ( 'general.error' ) );
		}
		return Redirect::to ( 'catalogos/ejercicio' );
	}
	
	
	public function show($id) {		
		if((int) $id > 0){
			$ejercicio = $this->ejercicioRepository->getEjercicio($id);
			return view ( "catalogos.ejercicio.show", ["ejercicio" => $ejercicio,"empresaIdUser" => Session::get ( 'userEnterprise' )] );
		}
		return view ( "layouts.error");
	}
	
	public function edit($id) {
		Session()->put('editar',true);
		$id =  Crypt::decrypt($id);
		if((int) $id > 0){
			try{
				$tipoEjercicioCliente   = TipoEjercicioCliente::findOrFail($id);
				$ejercicioCompetenciasComportamientos = $this->ejercicioRepository->getComportamientosCompetenciasEjercicioCliente($id);
				$ejercicioComportamientos = $this->ejercicioRepository->getComportamientosEjercicioCliente($id);
				$session    = Session()->get('permisos');		
				return view ( "catalogos.ejercicio.editEjercicio", [ 
				    "ejercicio"   => $tipoEjercicioCliente,
				            "competencias" => $this->competencias,
				    "ejercicioCompetenciasComportamientos" => $ejercicioCompetenciasComportamientos,
				    "ejercicioComportamientos" => $ejercicioComportamientos,
				    "clientes" => $this->clientes,
				    "tiposEjercicios" => $this->tiposEjercicios,
							"empresaIdUser" => Session::get ( 'userEnterprise' ),
				            "sessionPermisos" => $session[$this->moduloId],
				            "idModulo" => $this->moduloId,
							"isAdmin"  => Session::get ('isAdmin'),
							"disabled" => 'disabled'
					] );
			}
			catch(\Exception $e){
				$this->log->error($e);
			}
		}
		return view ( "layouts.error");
	}
	
	
	public function update(Request $request, $id) {
		if((int) $id > 0){
			try {
				
			    $this->ejercicioRepository->updateEjercicio( $request, $id);
				Session::flash ( 'message', Lang::get ( 'general.success' ) );
			} 
			catch (\Exception $e) {
	            $this->log->error($e);
				Session::flash ( 'message-error', Lang::get ( 'general.error' ) );
			}
			finally {
				return Redirect::to ( 'catalogos/ejercicio' );
			}
		}
		return redirect('home');
	}
	
	
	public function activar(Request $request){
		$exito = 0;
		$msg   = Lang::get ( 'general.error' );
		try{
			$id = $request->get('idEjercicio');
			if((int) $id > 0){
				$this->ejercicioRepository->activaEjercicio($id);
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
	
	
	public function desactivar(Request $request){
		$exito = 0;
		$msg   = Lang::get ( 'general.error' );
		try{
			$id = $request->get('idEjercicio');
			if((int) $id > 0){
				$this->ejercicioRepository->eliminaEjercicio($id);
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
	
	
	
	
}