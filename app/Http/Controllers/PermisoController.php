<?php

namespace sistema\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use sistema\Http\Requests\PerfilFormRequest;
use sistema\Policies\Constantes;
use sistema\Repositories\PermisoRepository;
/**
 *
 * @author Miguel Angel Molina 05/04/2017
 *         Clase controladora para los permisos
 */
class PermisoController extends Controller {
	private $permisoRepository;
	
	public function __construct(PermisoRepository $permisoRepository) {
		parent::__construct();
		$this->permisoRepository = $permisoRepository;		
	}
	
	/**
	 * Se encarga de listar todos los perfiles según los parámetros de busqueda
	 * 
	 * @param Request $request        	
	 */
	public function index(Request $request) {
		Session::forget('message-warning');
		$moduloId   = $this->permisoRepository->obtenModuloId(Constantes::CONTROLLER_PERFIL);
		$session    = Session()->get('permisos');
		$modulosParents = $this->permisoRepository->getModuloParents();
		$modulosChildren = $this->permisoRepository->getModuloChildren();
		$permisos  = $this->permisoRepository->getAllPermisos();
		$permisosSel = $this->permisoRepository->getAllModuloPermisosIds();
		$permisosSel = $this->permisoRepository->getArrayPermisos($permisosSel);
		$moduloId   = $this->permisoRepository->obtenModuloId(Constantes::CONTROLLER_USUARIO);
		
		return view ( "seguridad.permiso.indexPermiso", [
				"modulosP"     => $modulosParents,
				"modulosC"     => $modulosChildren,
				"permisosSeleccionados"  => $permisosSel,
				"permisoModulo" => array_keys($permisosSel),
				"permisoModuloPermisos" => array(),
				"perfilModulo" => array(),
				"permisos"     => $permisos,
				"sessionPermisos" => $session[$moduloId]
		] );
		

	}
	
	
	public function create(Request $request) {
	}
	
	public function store(Request $request) {
		try{
			if($request->isMethod('post')){
				$this->permisoRepository->savePermiso($request);
				Session::flash ( 'message', Lang::get ( 'general.success' ) );
			}
		}
		catch ( \Exception $e ) {
			$this->log->error ($e);
			Session::flash ( 'message-error', Lang::get ( 'general.error' ) );
		}
		finally{
			return Redirect::to ( 'seguridad/permiso' );
		}
	}
		
	public function show($id) {
	}
	
	public function edit($id) {	
	}
		
	public function update(PerfilFormRequest $request, $id) {
	}
	
	public function destroy($id) {
	}
}