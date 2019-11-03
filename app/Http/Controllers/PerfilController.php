<?php

namespace sistema\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use sistema\Http\Requests\PerfilFormRequest;
use sistema\Models\Perfil;
use sistema\Policies\Constantes;
use sistema\Repositories\PerfilRepository;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;

/**
 *
 * @author Miguel Angel Molina 05/04/2017
 *         Clase controladora para los perfiles
 */
class PerfilController extends Controller {
	private $perfilRepository;
	private $controller;
	private $moduloId;
	
	public function __construct(PerfilRepository $perfilRepository) {
		parent::__construct();
		$this->perfilRepository = $perfilRepository;
		$this->controller = Constantes::CONTROLLER_PERFIL;
		$this->moduloId = Constantes::MODULO_ID_PERFIL;
	}
	
	/**
	 * Se encarga de listar todos los perfiles seg�n los par�metros de busqueda
	 * 
	 * @param Request $request        	
	 */
	public function index(Request $request) {	
		Session::forget('message-warning');
        if ($request) {
            return view ( 'seguridad.perfil.indexPerfil', [
                "isAdmin"     => Session::get ('isAdmin'),
                "moduloId"    => $this->moduloId,
                "controller"  => $this->controller
            ] );
        }
	}
	
	public function buscar(Request $request){
		Session::forget('message-warning');
		$moduloId   = $request->get('idModulo');
		$opciones   = $this->parametros($request, 'idPerfil');
		$view = $this->buscaPerfiles($request,$opciones,$moduloId);
		return response()->json(view('seguridad.perfil.busquedaPerfil',$view)->render());
	}
	
	
	private function buscaPerfiles(Request $request,$opciones,$moduloId){
		$perfiles   = array();
		$idEmpresa  = $request->get ('idEmpresaBusca');
		$session    = Session()->get('permisos');
	
		$filtros    = $this->generaFiltrosPerfil($request);
		Session::put('busqueda_Perfiles', $filtros);
		$empresas 	= $this->perfilRepository->getAllEmpresas();
		$catEmpresas = $this->perfilRepository->catalogoEmpresas($empresas);
		 
		$total      = $this->perfilRepository->findByCount($filtros);
		$perfiles   = $this->perfilRepository->findByColumn($filtros,$opciones);
	
	
		$activo = '-1';
		if($request->get ('activo') != null && $request->get ('activo') != '')
			$activo = $request->get ('activo');
	
		$idempresa = '-1';
		if($request->get ('empresa') != null && $request->get ('empresa') != '')
			$idempresa = $request->get ('empresa');
	
		return [
				"perfiles"        => $perfiles,
				"total"           => $total,
				"leyenda"     	  => $this->perfilRepository->generaLetrero($total,count($perfiles),$opciones),
				"empresas"    	  => $empresas,
				"catEmpresas"    => $catEmpresas,
				"isAdmin"         => Session::get ('isAdmin'),
				"idperfil"        => $request->get ('idperfil'),
				"nombre"          => trim ($request->get ('nombre')),
				"activo"          => $request->get ('activo'),
				"idempresa"       => $idempresa,
				"descripcion"     => $request->get('descripcion'),
				"moduloId"        => $moduloId,
				"noPage" 	      => $opciones['nopage'],
				"noRegs" 	      => $opciones['noregs'],
				"sessionPermisos" => $session[$moduloId],
				"empresaIdUser"   => Session::get ( 'userEnterprise' ),
				"activo"          => $activo,
				"catEmpresas"     => $catEmpresas,
				"catEstatus"      => array(1 => 'Activo',0=> 'No Activo')
		];
	}
	
	
	private function generaFiltrosPerfil(Request $request){
		$idperfil    = (int) $request->get ('idperfil');
		$nombre      = trim ($request->get ('nombre'));
		$activo      = $request->get ('activo');
		$idEmpresa = null;
		if(Session::get ('isAdmin'))
			$idEmpresa = $request->get('empresa');
		else
			$idEmpresa = Session::get('userEnterprise');
	
		$descripcion = $request->get('descripcion');
		$filtros   = array ();
		if(Session::get ('isAdmin'))	{
			$filtros[] = ['perfil.idperfil', '>',Constantes::NOACTIVO];
		} else {
			$filtros[] = ['perfil.idperfil', '>',2];
		}

		if($idperfil > 0){
			$filtros[] = ['perfil.idperfil', '=',$idperfil];
		}
		if(trim($nombre) != ''){
			$filtros[] = ['perfil.nombre', 'LIKE', '%' . $nombre . '%'];
		}
		if(trim($activo) != '' && trim($activo) != '-1'){
			$filtros[] = ['perfil.activo', '=', $activo];
		}
		if(trim($idEmpresa) != '' && trim($idEmpresa) != '-1'){
			$filtros[] = ['perfil.idempresa', '=', $idEmpresa];
		}
		if(trim($descripcion) != ''){
			$filtros[] = ['perfil.descripcion', 'LIKE', '%' . $descripcion . '%'];
		}
		return $filtros;
	}
	public function create() {
		Session()->put('editar',false);
		$perfil = new Perfil ();
		$perfil->nombre = '';
		$perfil->descripcion = '';
		$perfil->idempresa = '' ;
		$session    = Session()->get('permisos');
		if((int) Session::get ( 'userIdPerfil' ) == Constantes::ACTIVO){
			$modulosParents = $this->perfilRepository->getModuloParents();
			$modulosChildren = $this->perfilRepository->getModuloChildren();
		}else{
			$modulosParents = $this->perfilRepository->getModuloParentsSinEmpresa();
			$modulosChildren = $this->perfilRepository->getModuloChildrenSimEmpresa();				
		}
		$empresas = $this->perfilRepository->getAllEmpresas();
		$permisos = $this->perfilRepository->getAllModuloPermisos();
		$noPermisos = count($this->perfilRepository->getAllPermisos());
		$catEmpresas = $this->perfilRepository->catalogoEmpresas($empresas);
		return view ( "seguridad.perfil.createPerfil", [
				"perfil" => $perfil,
				"modulosP" => $modulosParents,
				"modulosC" => $modulosChildren,
				"empresas" => $empresas,
				"catEmpresas" => $catEmpresas,
				"noPermisos" => $noPermisos,
				"permisos" => $permisos,
				"perfilModulo" => array(),				
				"perfilModuloPermisos" => array(),
				"empresaIdUser" => Session::get ( 'userEnterprise' ),
		        "sessionPermisos" => $session[$this->moduloId],
		        "idModulo" => $this->moduloId,
				"isAdmin"  => Session::get ('isAdmin'),
				"disabled" => ''
		] );
	}

    /**
     * @param PerfilFormRequest $request
     * @return mixed
     */
    public function store(PerfilFormRequest $request) {
		try {
			if($request->isMethod('post')){
				if(!Session::get ('isAdmin')) {
					$request['empresa'] = Session::get('userEnterprise');
				} 		
				
				$this->perfilRepository->save( $request );
				Session::flash ( 'message', Lang::get ( 'general.success' ) );
			}
		} catch ( \Exception $e ) {
			$this->log->error($e);
			Session::flash ( 'message-error', Lang::get ( 'general.error' ) );
		}
		return Redirect::to ( 'seguridad/perfil' );
	}
	
	
	public function show($id) {		
		if((int) $id > 0){
			$perfil = $this->perfilRepository->getPerfil($id);
			return view ( "seguridad.perfil.show", ["perfil" => $perfil,"empresaIdUser" => Session::get ( 'userEnterprise' )] );
		}
		return view ( "layouts.error");
	}
	
	public function edit($id) {
		Session()->put('editar',true);
		$id =  Crypt::decrypt($id);
		if((int) $id > 0){
			try{
				if(	$this->idRol == 1){
					$modulosP = $this->perfilRepository->getModuloParents();
					$modulosC = $this->perfilRepository->getModuloChildren();
				}else{
					$modulosP = $this->perfilRepository->getModuloParentsSinEmpresa();
					$modulosC = $this->perfilRepository->getModuloChildrenSimEmpresa();
				}				
				$perfil   = $this->perfilRepository->getPerfil($id);
				$empresas = $this->perfilRepository->getAllEmpresas();
				$permisos = $this->perfilRepository->getAllModuloPermisos();
				$noPermisos = count($this->perfilRepository->getAllPermisos());
				$perfilModulos = $this->perfilRepository->getAllPerfilModulos($id);
				$perfilModuloPermisos = $this->perfilRepository->getAllPerfilModuloPermisos($id);
				$session    = Session()->get('permisos');		
				return view ( "seguridad.perfil.editPerfil", [ 
							"perfil"   => $perfil,
							"modulosP" => $modulosP,
							"modulosC"  => $modulosC,
							"empresas" => $empresas,
						    "noPermisos" => $noPermisos,
							"permisos" => $permisos,
							"perfilModulo" => $perfilModulos,
							"perfilModuloPermisos" => $perfilModuloPermisos,
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
	
	
	public function update(PerfilFormRequest $request, $id) {
		if((int) $id > 0){
			try {
				$modulos = $request->input ( 'permisos' );
				$perfil  = $this->perfilRepository->getPerfil($id);
				$perfilA = clone $perfil;
				$perfil->nombre = trim($request->get ( 'nombre' ));
				$perfil->descripcion = trim($request->get ( 'descripcion' ));
				$this->perfilRepository->updatePerfil( $perfil,$modulos ,$perfilA);
				Session::flash ( 'message', Lang::get ( 'general.success' ) );
			} 
			catch (\Exception $e) {
	            $this->log->error($e);
				Session::flash ( 'message-error', Lang::get ( 'general.error' ) );
			}
			finally {
				return Redirect::to ( 'seguridad/perfil' );
			}
		}
		return redirect('home');
	}
	
	
	public function activar(Request $request){
		$exito = 0;
		$msg   = Lang::get ( 'general.error' );
		try{
			$id = $request->get('idPerfil');
			if((int) $id > 0){
				$this->perfilRepository->activaPerfil($id);
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
			$id = $request->get('idPerfil');
			if((int) $id > 0){
				$this->perfilRepository->eliminaPerfil($id);
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