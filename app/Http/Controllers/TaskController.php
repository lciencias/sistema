<?php
/**
 * Clase TaskController,  tareas para peticiones Ajax desde JQuery
 * @author  Miguel Angel Molina
 * @version 1.0
 */
namespace sistema\Http\Controllers;

use Illuminate\Http\Request;
use sistema\Repositories\PerfilRepository;

use Logger;

class TaskController extends Controller
{
	private $perfilRepository;
	private $comunesRepository;
	private $log;
	
	public function __construct(PerfilRepository $perfilRepository) {
		try{
			$this->middleware ( 'auth' );
			$this->perfilRepository = $perfilRepository;
			 Logger::configure ( './xml/config.xml' );
		}
		catch(\Exception $e){
			die("error:  ".$e->getMessage());
		}
	}
	
	/**
	 * Metodo
	 * @param Request $request
	 */
	public function peticion(Request $request){
		$menus = $submenus = array();
		if ($request) {
			try{
				$idPerfil  = (int)$request->get('idPerfil');
				$this->log = \Logger::getLogger ( 'Peticion Ajax del perfil: '.$idPerfil );			
				$menus     = $this->perfilRepository->getAllPerfilModulosMenus($idPerfil);
				$submenus  = $this->perfilRepository->getAllPerfilModulosSubmenus($idPerfil);
				$parents   = $this->perfilRepository->getModuloParents();
				$children  = $this->perfilRepository->getModuloChildren();
				return view ( 'seguridad.usuario.vista', [
						"menus"     => $menus,
						"submenus"  => $submenus,
						"parents"   => $parents,
						"childrens" => $children
				] );				
			}
			catch(\Exception $e){			
				$this->log->error($e);
			}
		}
	}
	
	
	/**
	 * 
	 * @param Request $request
	 */
	public function peticionEliminaModuloUsuario(Request $request){
		$permisos = $tmp = array();
		if ($request) {
			try{
				$idModulo = $request->get('idModulo');
				$idParent = $request->get('$idParent');
				if((int) $idModulo > 0  && (int) $idParent >= 0){
					$this->perfilRepository->debug($request->all());
				}			
			}
			catch(\Exception $e){
				$this->log->error($e);
			}
		}
		
	}
}