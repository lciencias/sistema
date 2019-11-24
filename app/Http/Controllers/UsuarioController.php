<?php

namespace sistema\Http\Controllers;
use Illuminate\Support\Facades\Lang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use sistema\Http\Requests\UsuarioFormRequest;
use sistema\Repositories\UsuarioRepository;
use sistema\User;
use sistema\Policies\Constantes;
use sistema\Jobs\EnviaEmailAltaUsuarioJob;
use sistema\Jobs\EnviaEmailReseteoPasswordJob;
use sistema\Models\Perfil;
use Illuminate\Support\Facades\Crypt;

class UsuarioController extends Controller {
	private $usuarioRepository;
	private $modulosRegistrados;
	private $modulosNoRegistrados;
	private $modulosRegistradosPermisos;
	private $modulosDefaultPermidos;
	private $modulosNuevosPermisos;
	private $modulosSeleccionadosPermisos;
	private $new;
	private $reset;
	
	public function __construct(UsuarioRepository $usuarioRepository) {
		parent::__construct();
		$this->new   = 1;
		$this->reset = 2;		
		// Se genera la clase para acceso a base de datos
		$this->usuarioRepository = $usuarioRepository;		
		// array de instancias
		$this->modulosRegistrados = $this->modulosNoRegistrados = $this->modulosDefaultPermidos = array ();		
	}
	
	/**
	 * Listado de Usuarios
	 * @param Request $request
	 */
	public function index(Request $request) {
		Session::forget('message-warning');
		if ($request) {
			$controller = $this->usuarioRepository->obtenerNombreController($request);
			$moduloId   = $this->usuarioRepository->obtenModuloId($controller);
			return view ( 'seguridad.usuario.indexUsuario', [
					"isAdmin"     => Session::get ('isAdmin'),
					"moduloId"    => $moduloId,
					"controller"  => $controller
			] );
		}
	}
	
	public function generaFiltrosPerfiles($idEmpresa){
		$filtros = array();
		if($idEmpresa == ''){
			if((int) Session::get ( 'userEnterprise' ) > 0){
				$filtros[] = ['idempresa', '=', -1];
			}
		}
		else if ($idEmpresa == null){
			$filtros[] = ['idempresa', '=', null];
		}
		
		else{
			if((int) $idEmpresa > 0){
				$filtros[] = ['idempresa', '=', $idEmpresa];
			}
		}
		return $filtros;
	}
	
	/**
	 * Metodo para editar un usuario
	 * @param int $id
	 */
	public function edit($id) {
		$id =  Crypt::decrypt($id);
		if (( int ) $id > 0) {
			try {
				Session ()->put ( 'editar', true );
				$usuarios = User::findOrFail ( $id );
				if ($usuarios->idempresa != null)
					$idEmpresa = $usuarios->idempresa;
				else
					$idEmpresa = $this->usuarioRepository->getIdEmpresa ( $usuarios->idperfil );
				$empresas = $this->usuarioRepository->getAllEmpresas ();
				$empresaBusca = 'null';
				if (! Session::get ( 'isAdmin' ))
					$empresaBusca = Session::get ( 'userEnterprise' );
				else {
					$perfil = Perfil::findOrFail($usuarios->idperfil);
					if ($perfil->idempresa != null && $perfil->idempresa != '')
						$empresaBusca = $perfil->idempresa;
				}
				
				$filtrosPer = $this->generaFiltrosPerfiles ( $empresaBusca );
				$perfiles = $this->usuarioRepository->getAllPerfilFiltros ( $filtrosPer );
				$moduloId = $this->usuarioRepository->obtenModuloId ( Constantes::CONTROLLER_USUARIO );
				$session = Session ()->get ( 'permisos' );
				return view ( "seguridad.usuario.editUsuario", [ 
						"usuario" => $usuarios,
						"perfiles" => $perfiles,
						"empresas" => $empresas,
						"sessionPermisos" => $session [$moduloId],
						"isAdmin" => Session::get ( 'isAdmin' ),
						"idEmpresaBusca" => $idEmpresa,
						"idEmpresaUser" => Session::get ( 'userEnterprise' ),
						"idPerfilBusca" => $usuarios->idperfil,
						"disabled" => 'disabled' 
				] );
			} catch ( \Exception $e ) {
				$this->log->error ( $e );
			}
		}
		return view ( "layouts.error" );
	}
	
	
	/**
	 * Creaci�n de Usuarios
	 */
	public function create(Request $request) {
		Session()->put('editar',false);
		$usuario = new \sistema\Models\User();
		$moduloId   = $this->usuarioRepository->obtenModuloId(Constantes::CONTROLLER_USUARIO);
		$session    = Session()->get('permisos');
		$empresas 	= $this->usuarioRepository->getAllEmpresas();
		$empresaBusca = '';
		if(!Session::get ('isAdmin'))
			$empresaBusca =  Session::get ( 'userEnterprise' );
		else
			$empresaBusca =   $request->get ('idEmpresaBusca');
		
		
		$filtrosPer = $this->generaFiltrosPerfiles($empresaBusca);
		$perfiles 	= $this->usuarioRepository->getAllPerfilFiltros($filtrosPer);
		$catEmpresas = $this->usuarioRepository->catalogoEmpresas($empresas);
		return view ( "seguridad.usuario.createUsuario", [
						"usuario" => $usuario,		
						"perfiles" => $perfiles,
						"empresas" => $empresas,
						"catEmpresas" => $catEmpresas,
					  	"sessionPermisos" => $session[$moduloId],
						"isAdmin"  => Session::get ('isAdmin'),
					  	"idEmpresaUser"   => Session::get ( 'userEnterprise' ),
					  	"idEmpresaBusca" => $request->get ('idEmpresaBusca'),
						"disabled" => ''
				
		]);
	}
	
	/**
	 * Metodo para almacenar un usaurio
	 * @param UsuarioFormRequest $request
	 */
	public function store(UsuarioFormRequest $request) {
		
		try {
			if($request->isMethod('post')){					
				if(!$this->usuarioRepository->validaEmail($request->get('email'))){				
					$passwordO = $this->generaPass ();
					$password = bcrypt ( $passwordO );
					$request->input('password', $password);
					$user = $this->usuarioRepository->saveUsuario( $request,$password);
					$this->log->info("Se registra envio de mail de acceso");
					try{
						$this->dispatch(new EnviaEmailAltaUsuarioJob($user, $passwordO));
					}
					catch(\Exception $e){
						$this->log->error("Error:  ".$e->getMessage()."\n".$e->getMessage());
					}
					Session::flash ( 'message', Lang::get ( 'general.success' ) );
				}else{
					Session::flash ( 'message-error', Lang::get ( 'El elemento email ya está en uso..' ) );
				}
			}
		} catch ( \Exception $e ) {
			$this->log->error($e);
			Session::flash ( 'message-error', Lang::get ( 'general.error' ) );
		} finally {
			return Redirect::to ( 'seguridad/usuario' );
		}
	}
	
	
	/**
	 * Metodo que actualiza en BD los datos del usuario
	 * @param UsuarioFormRequest $request
	 * @param int $id
	 */
	public function update(UsuarioFormRequest $request, $id) {
		$passwordEncript = "";
		try{
			if($request){
				$this->usuarioRepository->updateUsuario( $request);
				Session::flash ( 'message', Lang::get ( 'general.success' ) );				
			}
		}
		catch (\Exception $e) {
            $this->log->error($e);
			Session::flash ( 'message-error', Lang::get ( 'general.error' ) );
		}
		finally {		
			return Redirect::to ( 'seguridad/usuario' );
		}
	}
	
	/**
	 *
	 * @param Request $request
	 */
	public function peticionModuloPermiso(Request $request){
		$permisos = $tmp = array();
		if ($request) {
			try{
				$tmp = explode('-',$request->get('modulo'));
				$idModulo = $tmp[2]; 
				$idPerfil = $request->get('idPerfil');
				$idUsuario= $request->get('idUsuario');
				$this->iniciaPermisos($request->get('listapermisos'));
				$permisosDefault  = $this->usuarioRepository->getAllModuloPermisosId((int)$idModulo); 
				if((int) $idPerfil > 0 && (int) $idModulo > 0){
                    $this->log->debug('Peticion Ajax para permisos del modulo: '.$idModulo );
					$modulosPer = $this->usuarioRepository->modulosPerfilAll($idPerfil);
					$modulosPer = $this->usuarioRepository->getArray($modulosPer);
					$arrayModulosPermisos = $this->usuarioRepository->toCombo($modulosPer, 'idmodulo','idperfil_modulo');
					if(isset($arrayModulosPermisos) && array_key_exists($idModulo, $arrayModulosPermisos)){
						if(count($this->modulosSeleccionadosPermisos) == 0){
							$permisos  = $this->usuarioRepository->getAllModuloPermisosIdPerfilModulo( $arrayModulosPermisos[$idModulo] );
						}else{
							if(isset($this->modulosSeleccionadosPermisos) && array_key_exists($idModulo, $this->modulosSeleccionadosPermisos)){
								$permisos  = $this->usuarioRepository->getAllPermisosIds(explode(',',$this->modulosSeleccionadosPermisos[$idModulo]));
							}else{
								$permisos  = $this->usuarioRepository->getAllModuloPermisosIdPerfilModulo( $arrayModulosPermisos[$idModulo] );
							}
						}
					}						
					elseif(isset($this->modulosNuevosPermisos) && array_key_exists($idModulo,$this->modulosNuevosPermisos)){
						if(isset($this->modulosSeleccionadosPermisos) && array_key_exists($idModulo, $this->modulosSeleccionadosPermisos)){
							$permisos  = $this->usuarioRepository->getAllPermisosIds(explode(',',$this->modulosSeleccionadosPermisos[$idModulo]));
							}else{
							$permisos  = $this->usuarioRepository->getAllPermisosIds(explode(',',$this->modulosNuevosPermisos[$idModulo]));
						}							
					}
					else{
						if(isset($this->modulosSeleccionadosPermisos) && array_key_exists($idModulo, $this->modulosSeleccionadosPermisos)){
							$permisos  = $this->usuarioRepository->getAllPermisosIds(explode(',',$this->modulosSeleccionadosPermisos[$idModulo]));
						}else{			
							$permisos  = $this->usuarioRepository->getAllModuloPermisosId($idModulo);
						}
					}
					if(count($permisosDefault) == 0){
						$permisosDefault = $permisos;
					}
					return json_encode(array('permisos' => $permisos,'permisosDefault' => $permisosDefault));
				}
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
	public function peticionModuloPermisoId(Request $request){
		$permisos = $tmp = array();
		if ($request) {
			try{
				$id = $request->get('idModulo');
				if((int) $id > 0){
                    $this->log->info('Peticion Ajax para permisos del modulo: '.$id );
					$permisos = $this->usuarioRepository->getAllModuloPermisosId($id);
					return json_encode(array('permisos' => $permisos));
				}
			}
			catch(\Exception $e){
                $this->log->error($e);
			}
		}
	}
	/**
	 * Metodo que elimina un usuario
	 * @param int $id
	 */
	public function destroy($id) {
		if((int) $id > 0){
			try{
				$this->usuarioRepository->eliminaUsuario($id);
				Session::flash ( 'message', Lang::get ( 'general.success' ) );
			}		
			catch (\Exception $e) {
	            $this->log->error($e);
				Session::flash ( 'message-error', Lang::get ( 'general.error' ) );
			}
			finally {		
				return Redirect::to ( 'seguridad/usuario' );
			}
		}
		return redirect('home');
	}
	
	

	/**
	 * Metodo para resetar la contraseña del usuario
	 * @param int $id
	 */
	public function reset(Request $request){
		$exito = 0;
		$msg   = Lang::get ( 'general.error' );
		try{
			$id = $request->get('id');		
			if((int) $id > 0){
				$passwordOri = $this->generaPass ();
				$password    = bcrypt ( $passwordOri );
				$this->usuarioRepository->resetContrasenaUsuario($id,$password);	
				$usuario = $this->usuarioRepository->getUsuario($id);
				$request = new Request();
				$request->name = $usuario->name;
				$request->email = $usuario->email;
				$request->password = $passwordOri;
				$this->dispatch(new EnviaEmailReseteoPasswordJob($usuario, $passwordOri));
				$this->log->info("Se ha reseteado la contrasenia del usuario: " . $usuario->email);
				$exito = 1;
				$msg   = Lang::get ( 'general.success' );
			}
		}
		catch (\Exception $e) {
			die($e->getMessage());
            $this->log->error($e);
		}
		finally {
			return json_encode(array('exito' => $exito,'msg' => $msg));
		}
	}
	
	public function activar(Request $request){
		$exito = 0;
		$msg   = Lang::get ( 'general.error' );		
		try{
			$id = $request->get('id');
			if((int) $id > 0){
				$this->log->info('Activar usuario' );
				$this->usuarioRepository->activaUsuario($id);
				$msg   = Lang::get ( 'general.success' );
				$exito = 1;
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
			$id = $request->get('id');
			if((int) $id > 0){
				$this->log->info('Desactivar usuario' );
				$this->usuarioRepository->desactivaUsuario($id);
				$msg   = Lang::get ( 'general.success' );
				$exito = 1;
			}
		}
		catch (\Exception $e) {
            $this->log->error($e->getMessage()."\n".$e->getMessage());
		}
		finally {
			return json_encode(array('exito' => $exito,'msg' => $msg));
		}
	
	}
	
	
	/**
	 * Metodo via ajax que deshabilita un modulo del usuario perfil
	 * @param $idDeshabilita
	 */
	public function deshabiltaModulo(Request $request){
		if ($request) {
			try{
				$idPerfil  = (int)$request->get('idPerfil');
				$idModulo  = (int)$request->get('idModulo');
				$seleccionados   =  $request->get('seleccionados');
				$noseleccionados =  $request->get('noseleccionados');
				$idUsuario       =  $request->get('idUsuario');
				$this->iniciaPermisos($request->get('listapermisos'));
				if((int) $idPerfil > 0 && (int) $idModulo > 0){
					$permisosModulo = '';
                    $this->log->info('Quitar modulo:'.$idModulo.' del perfil  '.$idPerfil );
					$this->inicializaArraysBiz($seleccionados,$noseleccionados);
					$this->deshabilita($idModulo,1);
					$this->habilita($idModulo,1);
					$this->eliminaNoModulos();
					$menus     = $this->usuarioRepository->getAllPerfilModulosMenus($idPerfil);
					$submenus  = $this->usuarioRepository->getAllPerfilModulosSubmenus($idPerfil);
// 					$parents   = $this->usuarioRepository->getModuloParents();
// 					$children  = $this->usuarioRepository->getModuloChildren();
					
					if((int) Session::get ( 'userIdPerfil' ) == Constantes::ACTIVO){
						$parents = $this->usuarioRepository->getModuloParents();
						$children = $this->usuarioRepository->getModuloChildren();
					}else{
						$parents = $this->usuarioRepository->getModuloParentsSinEmpresa();
						$children = $this->usuarioRepository->getModuloChildrenSimEmpresa();
					}

					$permisos  = $this->usuarioRepository->getAllPermisosModulos();
					$permisos  = $this->usuarioRepository->getArray($permisos);
					$permisosN = $permisos;
					$permisos  = $this->usuarioRepository->toComboMultiple($permisos, 'idmodulo', 'idpermiso', 'nombre');
					$permisosN = $this->usuarioRepository->toComboMultiple($permisosN, 'idmodulo', 'nombre', 'nombre');
					$permisosPerfil = $this->usuarioRepository->getAllPerfilPermisosModulo($idPerfil);
					$permisosPerfil = $this->usuarioRepository->getArray($permisosPerfil);
					$permisosPerfil = $this->usuarioRepository->toComboMultiple($permisosPerfil, 'idmodulo', 'idpermiso', 'idmodulo');
					$this->asignaPermisos($idPerfil,$idUsuario);
					return view ( 'seguridad.usuario.vista', [
						"menus"     	=> $menus,
						"submenus"  	=> $submenus,
						"parents"   	=> $parents,
						"childrens" 	=> $children,
						"permisos" 	    => $permisos,
						"permisosN"     => $permisosN,
						"permisosPerfil"=> $permisosPerfil,
						"registrados"   => $this->modulosRegistrados,
						"noRegistrados" => $this->modulosNoRegistrados,
						"permisosModulo"=> $this->modulosRegistradosPermisos,
						"cadena" => $this->datosInput($this->modulosRegistradosPermisos)
					] );
				}
			}
			catch(\Exception $e){
                $this->log->error($e);
			}				
		}			
	}

	
	/**
	 * Metodo via ajax que habilita un modulo del usuario perfil
	 * @param $idDeshabilita
	 */	
	
	public function habilitaModulo(Request $request){
		if ($request) {
			try{
				$idPerfil  = (int)$request->get('idPerfil');
				$idModulo  = (int)$request->get('idModulo');
				$idParent  = (int)$request->get('idparent');
				$seleccionados   =  $request->get('seleccionados');
				$noseleccionados =  $request->get('noseleccionados');
				$permisosModulo  =  $request->get('permisos');
				$idUsuario       =  $request->get('idUsuario');
				$permisosModulo  =  $this->usuarioRepository->eliminaVacios(explode('|',$permisosModulo));
				$this->iniciaPermisos($request->get('listapermisos'));
				$this->modulosNuevosPermisos[$idModulo] = implode(',',$permisosModulo);
				$this->modulosSeleccionadosPermisos[$idModulo] = implode(',',$permisosModulo);
				if((int) $idPerfil > 0 && (int) $idModulo > 0){
                    $this->log->info('Anadir modulo:'.$idModulo.' del perfil  '.$idPerfil );
					$this->inicializaArraysBiz($seleccionados,$noseleccionados);
					$this->habilita($idParent,2);
					$this->deshabilita($idParent,2);
					$this->habilita($idModulo,2);
					$this->deshabilita($idModulo,2);
					$this->eliminaNoModulos();
					$menus     = $this->usuarioRepository->getAllPerfilModulosMenus($idPerfil);
					$submenus  = $this->usuarioRepository->getAllPerfilModulosSubmenus($idPerfil);
// 					$parents   = $this->usuarioRepository->getModuloParents();
// 					$children  = $this->usuarioRepository->getModuloChildren();
					
					if((int) Session::get ( 'userIdPerfil' ) == Constantes::ACTIVO){
						$parents = $this->usuarioRepository->getModuloParents();
						$children = $this->usuarioRepository->getModuloChildren();
					}else{
						$parents = $this->usuarioRepository->getModuloParentsSinEmpresa();
						$children = $this->usuarioRepository->getModuloChildrenSimEmpresa();
					}
					
					//$permisos  = $this->usuarioRepository->getAllPermisos();
					$permisos  = $this->usuarioRepository->getAllPermisosModulos();
					$permisos  = $this->usuarioRepository->getArray($permisos);
					$permisosN = $permisos;
					$permisos  = $this->usuarioRepository->toComboMultiple($permisos, 'idmodulo', 'idpermiso', 'nombre');
					$permisosN = $this->usuarioRepository->toComboMultiple($permisosN, 'idmodulo', 'nombre', 'nombre');
					$permisosPerfil = $this->usuarioRepository->getAllPerfilPermisosModulo($idPerfil);
					$permisosPerfil = $this->usuarioRepository->getArray($permisosPerfil);
					$permisosPerfil = $this->usuarioRepository->toComboMultiple($permisosPerfil, 'idmodulo', 'idpermiso', 'idmodulo');
					$this->asignaPermisos($idPerfil,$idUsuario);
					return view ( 'seguridad.usuario.vista', [
							"menus"     	=> $menus,
							"submenus"  	=> $submenus,
							"parents"   	=> $parents,
							"childrens" 	=> $children,
							"permisos" 	    => $permisos,
							"permisosN"     => $permisosN,
							"permisosPerfil"=> $permisosPerfil,
							"registrados"   => $this->modulosRegistrados,
							"noRegistrados" => $this->modulosNoRegistrados,
							"permisosModulo"=> $this->modulosRegistradosPermisos,
							"cadena" => $this->datosInput($this->modulosRegistradosPermisos)
					] );
				}
			}
			catch(\Exception $e){
                $this->log->error($e);
			}
		}
	}	
	
	/**
	 * Metodo para buscar usuarios
	 * @param Request $request
	 */
	
	public function buscar(Request $request){
		Session::forget('message-warning');
		$blade      = "";
		$moduloId   = $request->get('idModulo');
		$opciones   = $this->parametros($request, 'id');
		$view 		= $this->buscaUsuarios($request,$opciones,$moduloId);
		return response()->json(view('seguridad.usuario.busquedaUsuarios',$view)->render());
	}
	
	
	private function buscaUsuarios(Request $request,$opciones,$moduloId){
		$usuarios   = array();
		$filtros    = $this->generaFiltrosUsuarios($request);
		$total      = $this->usuarioRepository->findByCount($filtros);
		$usuarios   = $this->usuarioRepository->findByColumn($filtros,$opciones);
// 		$total      = count($usuarios);
        $empresas 	= $this->usuarioRepository->getAllEmpresas();
        $catEmpresas= $this->usuarioRepository->catalogoEmpresas($empresas);
        
        
        $empresaBusca = 'null';
        if (! Session::get ( 'isAdmin' ))
        	$empresaBusca = Session::get ( 'userEnterprise' );
        else {
        	 $empresaBusca = 'null';
        }
        
        $filtrosPer = $this->generaFiltrosPerfiles ( $empresaBusca );
        $perfiles = $this->usuarioRepository->getAllPerfilFiltros ( $filtrosPer );
       
// 		$filtrosPer = array();
// 		$perfiles 	= $this->usuarioRepository->getAllPerfilFiltros($filtrosPer);
        $catPerfiles= $this->usuarioRepository->catalogoPerfiles($perfiles);
		$session    = Session()->get('permisos');
		
		$activo = '-1';
		if($request->get ('activo') != null && $request->get ('activo') != '')
			$activo = $request->get ('activo');
		
		$idempresa = '-1';
		if($request->get ('empresa') != null && $request->get ('empresa') != '')
			$idempresa = $request->get ('empresa');
		
		return ["usuarios" => $usuarios,
				"total"      => $total,
				"leyenda"    => $this->usuarioRepository->generaLetrero($total,count($usuarios),$opciones),
				"sessionPermisos" => $session[$moduloId],
				"filtros"     => $filtros,
				"moduloId"    => $moduloId,
				"catEmpresas" => $catEmpresas,
				"catPerfiles" => $catPerfiles,
				"noPage" 	  => $opciones['nopage'],
				"noRegs" 	  => $opciones['noregs'],
				"id"  	 	  => $request->get('id'),
				"idEmpresa" => $request->get('idempresa'),
				"idPerfil"  => $request->get('idperfil'),
				"activo"    => $activo,
				"idempresa" => $idempresa,
				"email"     => trim(strtolower($request->get ('email'))),
				"name"      => trim($request->get ('name')),
				"catEstatus"  => array(1 => 'Activo',0=> 'No Activo'),
				"isAdmin"     => Session::get ('isAdmin'),
		];
	}
	
	/**
	 * Metodo que se encarga de filtrar los registros del modulo de Usuarios
	 * @param Request $request
	 */
	private function generaFiltrosUsuarios(Request $request){
		$filtros   = array ();
		$id  	   = (int) $request->get('id') + 0;
		
		$idEmpresa = "-1";
		if(Session::get ('isAdmin')) {
			if($request->get ('empresa') != null && $request->get ('empresa') != '')
				$idEmpresa = $request->get ('empresa');
		} else {
			$idEmpresa = Session::get('userEnterprise');
			$filtros[] = ['users.idperfil', '!=', Constantes::PERFIL_ADMIN_GENERAL];
			$filtros[] = ['users.idperfil', '!=', Constantes::PERFIL_ADMIN_EMPRESA];
		}
			
		
		
		$idPerfil  = (int) $request->get('idperfil') + 0;
		$activo    = $request->get('activo');
		$email     = trim(strtolower($request->get ('email')));
		$name      = trim($request->get ('name'));
		
		
		
        if(Session::get ('isAdmin'))	{
            $filtros[] = ['users.id', '>', Constantes::NOACTIVO];
        } else {
            $filtros[] = ['users.id', '>', Constantes::PERFIL_ADMIN_GENERAL];
        }
		if(trim($name) != ''){
			$filtros[] = ['users.name', 'LIKE', '%' . $name . '%'];
		}
		if(trim($email) != ''){
			$filtros[] = ['users.email', 'LIKE', '%' . $email . '%'];
		}
		if($id > 0){
			$filtros[] = ['users.id', '=',$id];
		}
		if(trim($activo) != '' && trim($activo) != '-1'){
			$filtros[] = ['users.activo', '=', $activo];
		}
		if(trim($idEmpresa) != '' && trim($idEmpresa) != '-1'){
			$filtros[] = ['users.idempresa', '=', $idEmpresa];
		}
		if((int) $idPerfil > 0){
			$filtros[] = ['users.idperfil', '=', $idPerfil];
		}
		return $filtros;
	}
	
	function datosInput($data){
		$array = array();
		if(count($data) > 0){
			foreach($data as $idModulo => $tmpPermisos){
				$array[$idModulo] = $idModulo.'*'.implode(',',$tmpPermisos);
			}
		}
		return implode('|',$array);		
	}
	
	function iniciaPermisos($data){
		$this->modulosNuevosPermisos = array();
		if(trim($data) != ''){
			$moduloPermiso = explode('|',$data);
			foreach($moduloPermiso as $temporal){
				$modPerm = explode('*',$temporal);			
				$this->modulosNuevosPermisos[(int)$modPerm[0]] = trim($modPerm[1]);
				$this->modulosSeleccionadosPermisos[(int)$modPerm[0]] = trim($modPerm[1]);
			}			
		}	
	}
	
	
	function asignaPermisos($idPerfil,$idUsuario){
		$this->modulosRegistradosPermisos = $tmp = array();
		$permisosPerfil = $permisosUsuario = array();
		//Modulos con parent is null
		$modulosparents = $this->usuarioRepository->getIdsModuloParents();	
		$modulosparents = $this->usuarioRepository->getArray($modulosparents);
		$modulosparents = $this->usuarioRepository->toComboMultiple($modulosparents, 'idmodulo', 'idmodulo', 'idmodulo');
		
		$permisosPerfil = $this->usuarioRepository->getAllPerfilPermisos($idPerfil);
		$permisosPerfil = $this->usuarioRepository->arrayUnique($permisosPerfil);
		
		if((int) $idUsuario > 0){  //saco modulos y permisos del usuario
			$permisosUsuario = $this->usuarioRepository->getAllUsuarioPermisos($idUsuario);
			$permisosUsuario = $this->usuarioRepository->arrayUnique($permisosUsuario);		
			$permisosNoUsuario = $this->usuarioRepository->getAllUsuarioPermisosNoAsignados($idUsuario);
			$permisosNoUsuario = $this->usuarioRepository->arrayUnique($permisosNoUsuario);
		}
		$tmp = array();
		if(count($this->modulosRegistrados) > 0){
			foreach($this->modulosRegistrados as $moduloId){
				$tmp = array();
				if(!array_key_exists($moduloId, $modulosparents)){
					if(array_key_exists($moduloId, $permisosPerfil)){ // revisamos en los perfiles
						$permisos = $permisosPerfil[$moduloId];
						foreach($permisos as $permiso){
							if(!in_array($permiso,$tmp))
								$tmp[] = $permiso; 			
						}					
					}
					if((int) $idUsuario > 0){
						if(array_key_exists($moduloId, $permisosUsuario)){ // revisamos en los usuarios permitidos
							$permisos = $permisosUsuario[$moduloId];
							foreach($permisos as $permiso){
								if(!in_array($permiso,$tmp))
									$tmp[] = $permiso;						
							}
						}						
						if(array_key_exists($moduloId, $permisosNoUsuario)){ // revisamos en los usuarios NO permitidos
							$permisos = $permisosNoUsuario[$moduloId];							
							if(count($permisos) > 0){
								foreach($permisos as $permiso){
									$clave = array_search($permiso,$tmp,true);									
									if($clave !== FALSE){
										$tmp[$clave] = 0;
									}
								}
							}
						}
					}		
					//reviso los modulos y permisos seleccionados
					if(isset($this->modulosNuevosPermisos) && array_key_exists($moduloId,$this->modulosNuevosPermisos)){
						$tmp = explode(',',$this->modulosNuevosPermisos[$moduloId]);
					}
					$tmp = $this->reajustaPermisos($tmp);
					if(count($tmp) > 0){
						$this->modulosRegistradosPermisos[$moduloId] = $tmp;
					}
				}
			}
		}
		//echo"<pre>";print_r($this->modulosRegistradosPermisos);die();
	}
	
	private function reajustaPermisos($tmp){
		$array = array();
		if(count($tmp)>0){
			foreach($tmp as $value){
				if((int) $value > 0)
					$array[] = $value;
			}
		}
		return $array;
	}
	
	/**
	 * Metodo Set de la variable de instancia
	 * @param array $modulosRegistrados
	 */
	public function setModulosRegistrados($modulosRegistrados){
		$this->modulosRegistrados = $modulosRegistrados;
	}

	/**
	 * Metodo Get de la variable de instancia
	 * @param array $modulosRegistrados
	 */
	
	public function getModulosRegistrados(){
		return $this->modulosRegistrados;
	}
	
	
	/**
	 * Metodo Set de la variable de instancia
	 * @param array $modulosNoRegistrados
	 */	
	public function setModulosNoRegistrados($modulosNoRegistrados){
		$this->modulosNoRegistrados = modulosNoRegistrados;
	}
	
	/**
	 * Metodo Get de la variable de instancia
	 * @param array $modulosNoRegistrados
	 */	
	public function getModulosNoRegistrados(){
		return $this->modulosNoRegistrados;
	}
		
	
	public function setModulosRegistradosPermisos($modulosRegistradosPermisos){
		$this->modulosRegistradosPermisos = $modulosRegistradosPermisos;
	}
	
	public function getModulosRegistradosPermisos(){
		return $this->modulosRegistradosPermisos;
	}
	
	public function setModulosNuevosPermisos($modulosNuevosPermisos){
		$this->modulosNuevosPermisos = $modulosNuevosPermisos;
	}
	
	public function getModulosNuevosPermisos(){
		return $this->modulosNuevosPermisos;
	}
	
	/**
	 * Metodo
	 * @param Request $request
	 */
	public function peticion(Request $request){
		if ($request) {
			try{
				

				$idPerfil  = (int)$request->get('idPerfil');
				$idUsuario = (int)$request->get('id');
//                 $this->log->info('Peticion Ajax del perfil: '.$idPerfil );
				$modulos   = $this->usuarioRepository->getAllModulos ();
				$modulosPer= $this->usuarioRepository->modulosPerfilAllEdit($idPerfil);
				$this->inicializaArrays($idUsuario,$modulos,$modulosPer);
// 				$parents   = $this->usuarioRepository->getModuloParents();
// 				$children  = $this->usuarioRepository->getModuloChildren();

				if((int) Session::get ( 'userIdPerfil' ) == Constantes::ACTIVO){
					$parents = $this->usuarioRepository->getModuloParents();
					$children = $this->usuarioRepository->getModuloChildren();
				}else{
					$parents = $this->usuarioRepository->getModuloParentsSinEmpresa();
					$children = $this->usuarioRepository->getModuloChildrenSimEmpresa();
				}
				
				$permisos  = $this->usuarioRepository->getAllPermisosModulos();			
				$permisos  = $this->usuarioRepository->getArray($permisos);
							
				$permisosN = $permisos;
				$permisos  = $this->usuarioRepository->toComboMultiple($permisos, 'idmodulo', 'idpermiso', 'nombre');
				$permisosN = $this->usuarioRepository->toComboMultiple($permisosN, 'idmodulo', 'nombre', 'nombre');
			
				$permisosPerfil = $this->usuarioRepository->getAllPerfilPermisosModulo($idPerfil);
				$permisosPerfil = $this->usuarioRepository->getArray($permisosPerfil);
				$permisosPerfil = $this->usuarioRepository->toComboMultiple($permisosPerfil, 'idmodulo', 'idpermiso', 'idmodulo');

				$this->asignaPermisos($idPerfil,$idUsuario);				
				return view ( 'seguridad.usuario.vista', [
						"parents"   	=> $parents,
						"childrens" 	=> $children,
						"permisos"      => $permisos,
						"permisosN"     => $permisosN,
						"permisosPerfil"=> $permisosPerfil,
						"registrados"   => $this->modulosRegistrados,
						"noRegistrados" => $this->modulosNoRegistrados,
						"permisosModulo"=> $this->modulosRegistradosPermisos,
						"cadena" => $this->datosInput($this->modulosRegistradosPermisos)
						] );
			}
			catch(\Exception $e){
                $this->log->error($e);
			}
		}
	}

	
	/**
	 * Inicializar las variables de instancia
	 * @param Object $modulos
	 * @param Object $modulosPer
	 */
	public function inicializaArrays($idUsuario,$modulos,$modulosPermitidos){
		$permitidos = array();		
		$arrayPermitidos = array();
		if((int) $idUsuario > 0){
			$modulosUsuarios   = $this->usuarioRepository->modulosUserPermitidos($idUsuario);
			foreach($modulosUsuarios as $moduloUsuario){
				$permitidos[$moduloUsuario->idmodulo] = $moduloUsuario->permitido;
				$arrayPermitidos[] = $moduloUsuario->parent;
				$arrayPermitidos[] = $moduloUsuario->idmodulo;
			}
			$modulosUsuarios   = $this->usuarioRepository->modulosUserNoPermitidos($idUsuario);
			foreach($modulosUsuarios as $moduloUsuario){
				$permitidos[$moduloUsuario->idmodulo] = -1;		
				$clave = array_search($moduloUsuario->idmodulo, $arrayPermitidos);
				if((int) $clave > 0){
					$arrayPermitidos[$clave] = -1;
				}
				
			}
		}
		if(count($modulosPermitidos)> 0){
			foreach($modulosPermitidos as $modulo){
				if(!in_array($modulo->idmodulo,$arrayPermitidos)){					
					$arrayPermitidos[] = $modulo->idmodulo;
				}
			}			
			$arrayPermitidos = array_unique ($arrayPermitidos);			
			sort($arrayPermitidos);
			if(count($modulos) > 0){
				foreach ($modulos as $modulo){
					if(in_array($modulo->idmodulo,$arrayPermitidos)){
						if (!array_key_exists($modulo->idmodulo, $permitidos)) {
							$this->modulosRegistrados[] = $modulo->idmodulo;
						}else{
							if($permitidos[$modulo->idmodulo] == 1){
								$this->modulosRegistrados[] = $modulo->idmodulo;
							}else{
								$this->modulosNoRegistrados[] = $modulo->idmodulo;
							}
						}
					}else{
						if (!array_key_exists($modulo->idmodulo, $permitidos)) {
							$this->modulosNoRegistrados[] = $modulo->idmodulo;							
						}else{
							if($permitidos[$modulo->idmodulo] == 1){
								$this->modulosRegistrados[] = $modulo->idmodulo;
							}else{
								$this->modulosNoRegistrados[] = $modulo->idmodulo;
							}
						}
					}
				}
			}
			if(count($this->modulosRegistrados) > 0){
				$moduloFinales  = array();
				$modulosPadres  = $this->usuarioRepository->getModuloParents();
				$modulosPadres  = $this->usuarioRepository->catalogoModulos($modulosPadres);				
				foreach($this->modulosRegistrados as $idModuloTmp){
					if(array_key_exists($idModuloTmp, $modulosPadres)){  // es padre
						$hijos   = $this->usuarioRepository->getModulosHijos($idModuloTmp,0);
						$totalModulosexisten = 0;
						if(count($hijos) > 0){
							foreach($hijos as $idHijo){
								if($this->usuarioRepository->existeEnSeleccion($idHijo, $this->modulosRegistrados) == 1){
									$moduloFinales[] = $idModuloTmp;
								}
							}
						}
						
					}else{
						$moduloFinales[] = $idModuloTmp;
					}
				}
				$this->modulosRegistrados = $moduloFinales;
			}
		}
	}
	
	
	/**
	 * Metodo que inicializa las variables de instancia de modulos registrados y no registrados
	 * @param array $seleccionados
	 * @param array $noseleccionados
	 */
	public function inicializaArraysBiz($seleccionados,$noseleccionados){
 		$this->modulosRegistrados   = explode(',',$seleccionados);
		$this->modulosNoRegistrados = explode(',',$noseleccionados);
		
	}
	
	/**
	 * Metodo que regenera las variables de instancia de modulos registrados y no registrados
	 */
	private function eliminaNoModulos(){
		$arrayUnicos = array();
		$registrados     = $this->modulosRegistrados;
		$noRegistrados   = $this->modulosNoRegistrados;
		if(count($registrados) > 0){
			$this->modulosRegistrados = array();
			foreach($registrados as $value){
				if((int) $value != -1){
					if(!in_array($value,$arrayUnicos)){
						$this->modulosRegistrados[] = $value;
						$arrayUnicos[] = $value;
					}
				}
			}
		}		
		$arrayUnicos = array();
		if(count($noRegistrados) > 0){
			$this->modulosNoRegistrados = array();
			foreach($noRegistrados as $value){
				if((int) $value != -1){
					if(!in_array($value,$arrayUnicos)){
						$this->modulosNoRegistrados[] = $value;
						$arrayUnicos[] = $value;
					}
				}
			}
		}
	}
	/**
	 * Metodo para enviar por correo las claves de acceso
	 * @param array $request
	 */
	private function enviaAcceso($request,$opc){
		$data = array('name' =>$request->name, 'password'=>$request->password, 'email' => $request->name);
		$template = 'seguridad.usuario.emailTemplates.nuevo';
		$titulo   = 'Alta de Usuario.';
		if((int) $opc == 2){
			$template = 'seguridad.usuario.emailTemplates.reset';
			$titulo   = 'Reseteo de claves de acceso.';
		}
		Mail::send($template, $data, function($message) use ($request) {
			$message->to($request->email, $request->name )->subject('Ventas');
			$message->from('enviomails.test2017@gmail.com','Administrador');
		});
	}
	
	/**
	 * Metodo que deshabilta el modulo del usuario
	 * @param int $idModulo
	 * @param int $opcion
	 */
	private function deshabilita($idModulo,$opcion){

		if((int) $opcion == 1){
			if(in_array($idModulo, $this->modulosRegistrados)){
				$key = array_search($idModulo,$this->modulosRegistrados);
				if((int) $key >= 0){
					$this->modulosRegistrados[$key] = -1;
					$padre   = $this->usuarioRepository->obtieneIdPadre($idModulo);
					$idPadre = $padre->parent;
					$hijos   = $this->usuarioRepository->getModulosHijos($padre->parent,$idModulo);
					$totalModulosexisten = 0;
					if(count($hijos) > 0){
						foreach($hijos as $idHijo){
							if($this->usuarioRepository->existeEnSeleccion($idHijo, $this->modulosRegistrados) == 1){
								$totalModulosexisten++;								
							}
						}						
					}
					if($totalModulosexisten == 0){
						$key = array_search($idPadre,$this->modulosRegistrados);
						$this->modulosRegistrados[$key] = -1;
					}
				}
			}
		}
		if((int) $opcion == 2){
			if(in_array($idModulo, $this->modulosNoRegistrados)){
				$key = array_search($idModulo,$this->modulosNoRegistrados);
				if((int) $key >= 0){
					$this->modulosNoRegistrados[$key] = -1;
				}
			}				
		}
	}
	
	/**
	 * Metodo que habilta el modulo del usuario
	 * @param int $idModulo
	 * @param int $opcion
	 */	
	private function habilita($idModulo,$opcion){
		if((int) $opcion == 1){
			if(!in_array($idModulo, $this->modulosNoRegistrados)){
				$this->modulosNoRegistrados[] = $idModulo;
			}
		}
		if((int) $opcion == 2){
			if(!in_array($idModulo, $this->modulosRegistrados)){
				$this->modulosRegistrados[] = $idModulo;
			}
		}		
	}
	
	
	
	private  function getPerfilPorEmpresa($idEmpresaBusca){
		$arrayTmp = array();	
		if(!Session::get ('isAdmin')){
			$idEmpresa  = Session::get ( 'userEnterprise' ) ;
		}else{
			$idEmpresa  = $idEmpresaBusca;
		}
		if((int)  $idEmpresa > 0){
			$filtrosPerf[] = ['idempresa', '=', $idEmpresa];				
			$perfiles = $this->usuarioRepository->getAllPerfilFiltros($filtrosPerf);
			if(count($perfiles) > 0){
				foreach($perfiles as $perfil){
					$arrayTmp[] = $perfil->idperfil;			
				}
			}
		}		
		return $arrayTmp;
	}
	
	/**
	 * Valida si el nomb re del perfil ya esta registrado
	 * @param Request $request
	 */
	public function validaMail(Request $request) {
		$response;
		if(!$this->usuarioRepository->validaMail($request->get('email'), $request->get('idusuario'))){
			$response = array('valid' => true ,  'message' => '');
		} else {
			$response = array('valid' => false,  'message' => Lang::get ( 'leyendas.usuario.mail.existente' ));
		}
		print_r(json_encode($response));
	
	
	
	}
	
}
