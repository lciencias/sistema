<?php

namespace sistema\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use Dompdf\Exception;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Logger;
use sistema\Http\Controllers\Controller;
use sistema\Models\Acceso;
use sistema\Models\Empresa;
use sistema\Models\Modulo;
use sistema\Models\Perfil;
use sistema\Models\PerfilModulo;
use sistema\Models\PerfilModuloPermiso;
use sistema\Models\Permiso;
use sistema\Models\UsersModulo;
use sistema\Models\UsersModuloPermiso;
use sistema\Policies\Constantes;
use function Psy\debug;


class LoginController extends Controller
{
	use RedirectsUsers;
	use ThrottlesLogins;
	public $logger;
    public function __construct(){
    	Logger::configure ( './xml/config.xml' );
    	$this->logger = \Logger::getLogger ( 'main' );
        $this->middleware('guest',['only' => 'showLoginForm']);
    }
    public function showLoginForm(){
        return view('auth.login');
    }
    public function login(Request $request){
    	$lockedOut = true;
        $credentials = $this->validate(request(),[$this->username() => 'required|string','password' => 'required|string']);
        $throttles = $this->isUsingThrottlesLoginsTrait();
        if ($throttles && $lockedOut = $this->hasTooManyLoginAttempts($request)) {
        	$this->fireLockoutEvent($request);
        	return $this->sendLockoutResponse($request);
        }
        $credentials = $this->getCredentials($request);
        if (Auth::guard($this->getGuard())->attempt($credentials, $request->has('remember'))) {
            $throttles = $this->isUsingThrottlesLoginsTrait();
            $user = Auth::guard($this->getGuard())->user();
            if((int) $user['idperfil'] > 1){
                if($user['activo'] == 1){
                    if(!$this->revisaPerilActivo((int) $user['idperfil'])){
                        $this->registraAcceso($credentials['email'],"2",$user->id);
                        Auth::guard($this->getGuard())->logout();
                        return $this->sendPerfilInactiveResponse($request);
                    }
                    if(!$this->revisaEmpresaActivo((int) $user['idempresa'])){
                        $this->registraAcceso($credentials['email'],"2",$user->id);
                        Auth::guard($this->getGuard())->logout();
                        return $this->sendCompanyInactiveResponse($request);
                    }
                    $this->registraAcceso($credentials['email'],"1",$user->id);
                    return $this->handleUserWasAuthenticatedSession($request, $throttles, $credentials);
                }else{
                    $this->registraAcceso($credentials['email'],"2",$user->id);
                    Auth::guard($this->getGuard())->logout();
                    return $this->sendUserInactiveResponse($request);
                }
            }else{
                $this->registraAcceso($credentials['email'],"1",$user->id);
                return $this->handleUserWasAuthenticatedSession($request, $throttles, $credentials);
            }
            return redirect()->route('home');
        }
        if ($throttles && ! $lockedOut) {
        	$this->incrementLoginAttempts($request);
        }        
        $this->registraAcceso($credentials['email'],"2",null);
        return back()->withErrors([$this->username() => trans('auth.failed')])->withInput(request([$this->username()]));
    }


    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect()->back()
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                $this->loginUsername() => $this->getFailedLoginMessage(),
            ]);
    }

    protected function sendUserInactiveResponse(Request $request)
    {
        return redirect()->back()
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                $this->loginUsername() => "El usuario está Inactivo, consulte al administrador"
            ]);
    }

    protected function sendPerfilInactiveResponse(Request $request)
    {
        return redirect()->back()
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([$this->loginUsername() => "El perfil asignado al usuario está Inactivo, consulte al administrador"]);
    }

    protected function sendCompanyInactiveResponse(Request $request)
    {
        return redirect()->back()
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([$this->loginUsername() => "La empresa asignada al usuario está Inactiva, consulte al administrador"]);
    }

    private function revisaPerilActivo($idPerfil){
        $perfiles = Perfil::where('idperfil', '=', $idPerfil)->get();
        foreach($perfiles as $perfil){
            if($perfil['activo'] == 1){
                return true;
            }
        }
        return false;
    }

    private function revisaEmpresaActivo($idEmpresa){
        $empresas = Empresa::where('idempresa', '=', $idEmpresa)->get();
        foreach($empresas as $empresa){
            if($empresa['activo'] == 1){
                return true;
            }
        }
        return false;
    }

private function generateMenu($user){
        $permisos             = $this->toCombo(Permiso::get());
		$modulosActivos       = Modulo::where('activo','=',Constantes::ACTIVO)->select('idmodulo')->get();
		$modulosActivosArray  = $this->generaIdsModulos($modulosActivos);
		$perfilModulosBD      = PerfilModulo::where('idperfil', '=', $user['idperfil'])->get();
        $modulosPermisosFinal = $modulosPermisosFinalArray = array();
        $arrayModulos         = array();
        $arrayModulosB        = array();
        foreach ($perfilModulosBD as $perfilModuloBD){
            $arrayPermisosB = array();                   	
            $permisosPerfilModuloBD = PerfilModuloPermiso::where('idperfil_modulo', '=', $perfilModuloBD->idperfil_modulo)->get();
            foreach($permisosPerfilModuloBD as $permisoId){
                $arrayPermisosB[] = $permisoId->idpermiso;
            }
            if(in_array($perfilModuloBD->modulo->idmodulo,$modulosActivosArray)){
            	$modulosPermisosFinal[$perfilModuloBD->modulo->idmodulo] = $permisosPerfilModuloBD;
            	$modulosPermisosFinalArray[$perfilModuloBD->modulo->idmodulo] = $this->getArray($arrayPermisosB,$permisos);
            	$arrayModulosB [$perfilModuloBD->modulo->idmodulo] = $arrayPermisosB;
            }
        }
        $usuarioModulosBD = UsersModulo::where('iduser', '=', $user['id'])->get();
        foreach ($usuarioModulosBD as $usuarioModuloBD){         		
            $arrayPermisosB = array();
            $permisosUsuarioModuloBD = UsersModuloPermiso::where('iduser_modulo', '=', $usuarioModuloBD->iduser_modulo)->get();
            if($usuarioModuloBD->permitido){
                foreach($permisosUsuarioModuloBD  as $permisoId){
                    if($permisoId->permitido)
                        $arrayPermisosB[] = $permisoId->idpermiso;
                }
                if(in_array($usuarioModuloBD->idmodulo,$modulosActivosArray)){
                	if(isset($modulosPermisosFinal[$usuarioModuloBD->idmodulo] ))
                		$modulosPermisosFinal[$usuarioModuloBD->idmodulo] =  $modulosPermisosFinal[$usuarioModuloBD->idmodulo]->push($permisosUsuarioModuloBD);
                	else 
                		$modulosPermisosFinal[$usuarioModuloBD->idmodulo] =  $permisosUsuarioModuloBD;
                		
                	if(isset($modulosPermisosFinalArray[$usuarioModuloBD->idmodulo] ))
                		$modulosPermisosFinalArray[$usuarioModuloBD->idmodulo] =  array_merge($modulosPermisosFinalArray[$usuarioModuloBD->idmodulo], $this->getArray($arrayPermisosB,$permisos));
                	else
                		$modulosPermisosFinalArray[$usuarioModuloBD->idmodulo] = $this->getArray($arrayPermisosB,$permisos);
                	
                	if(isset($arrayModulosB [$usuarioModuloBD->idmodulo]))
                		$arrayModulosB [$usuarioModuloBD->idmodulo] = array_merge($arrayModulosB [$usuarioModuloBD->idmodulo] , $arrayPermisosB); 
                	else
                		$arrayModulosB [$usuarioModuloBD->idmodulo] = $arrayPermisosB;
                	
                }else{
                	$modulosPermisosFinal[$usuarioModuloBD->idmodulo] = $permisosUsuarioModuloBD;
                	$modulosPermisosFinalArray[$usuarioModuloBD->idmodulo] = $this->getArray($arrayPermisosB,$permisos);
                	$arrayModulosB [$usuarioModuloBD->idmodulo] = $arrayPermisosB;                	 
                }
            }else{
                unset($modulosPermisosFinal[$usuarioModuloBD->idmodulo]);
            }
        }
        foreach($modulosPermisosFinal as $idMenu => $tmp){
            $arrayModulos[] = $idMenu;
        }
        $modulosBiz = Modulo::where('activo','=',Constantes::ACTIVO)->get();
        Session()->put('modules', $modulosPermisosFinal);
        Session()->put('menu', $this->generaMenu($arrayModulos));
        Session()->put('items', $this->readMenu($arrayModulos));
        Session()->put('modulos', $arrayModulos);
        Session()->put('permisos',$modulosPermisosFinalArray);        
        Session()->put('identificadores', $this->regresaIdentificadores($modulosBiz));
        
    }

    private function generaMenuTop($arrayModulos){
    	$menu = "";
    	$modulos = Modulo::where("parent",'=','0')->where('activo','=',1)->orderBy('orden')->get();
    	if(count($modulos) > 0){
    		$menu .=' <ul class="nav navbar-nav">';
    		foreach($modulos as $modulo){
    			if((int) $modulo->idmodulo > 0) {
    				if(in_array($modulo->idmodulo, $arrayModulos)){
    					$menu .= '<li class="">
                                        <a href="/'.Constantes::THIS_SERVER_NAME.'/public/#"  class="dropdown-toggle" data-toggle="dropdown">                                            
                                            ' . trim($modulo->nombre) . '<span class="caret"></span>
                                            </i>
                                        </a>';
    					$submodulos = Modulo::where('parent', '=', $modulo->idmodulo)->orderBy('orden')->get();
    					if (count($submodulos) > 0) {
    						$menu .= '<ul class="dropdown-menu" role="menu">';
    						foreach ($submodulos as $submodulo) {
    							if(in_array($submodulo->idmodulo, $arrayModulos)) {
    								$trimodulos = Modulo::where('parent', '=', $submodulo->idmodulo)->orderBy('orden')->get();
    								$menu .= '<li>
                                                <a href="/'.Constantes::THIS_SERVER_NAME.'/public' . $submodulo->recurso . '" class="dropdown-toggle" data-toggle="dropdown">
                                                    ' . trim($submodulo->nombre);
    								if (count($trimodulos) > 0) {
    									$menu .= '<i class="fa fa-angle-left pull-right"></i>';
    								}
    								$menu .= '</a>';
    								if (count($trimodulos) > 0) {
    									$menu .= '<ul  class="dropdown-menu" role="menu">';
    									foreach ($trimodulos as $trimodulo) {
    										if(!in_array($trimodulo->idmodulo, $arrayModulos)) {
    											$menu .= '<li>
                                                <a href="/'.Constantes::THIS_SERVER_NAME.'/public' . $trimodulo->recurso . '">                                                    
                                                    ' . trim($trimodulo->nombre) . '
                                                </a></li>';
    										}
    									}
    									$menu .= '</ul>';
    								}
    								$menu .= '</li>';
    							}
    						}
    						$menu .= '</ul>';
    					}
    					$menu .= '</li>';
    				}
    			}
    		}
    		/*$menu .='<li>
                    <a href="/sistema/public/logout")}}">
                        <i class="glyphicon glyphicon-hand-right"></i><span>Salir</span>
                    </a>
                </li>';*/
    		$menu .='</ul>';
    	}
    	return $menu;
    }
    
    
    private function buscaModulosHijos($idModPadre){
    	$arrayHijos = array();
    	$hijos = Modulo::where("parent",'=',$idModPadre)->where('activo','=',1)->orderBy('orden')->get();    	   
    	if(count($hijos) > 0){
    		foreach($hijos as $hijo){
    			$arrayHijos[] = $hijo->idmodulo;
    		}
    	}
    	return $arrayHijos;
    }
    
    private function existeEnSeleccion($modHijo,$arraytemporal){
    	$y = implode(',' , $arraytemporal);
    	$exito = (int) in_array($modHijo,$arraytemporal,true);
    	return $exito;
    }
    private function generaMenu($arrayModulos){
    	$totalHijosExisten = 0;
        $menu = "";
        $modulos = Modulo::where("parent",'=','0')->where('activo','=',1)->orderBy('orden')->get();
        Session()->put('parents', $this->regresaParents($modulos));
        if(count($modulos) > 0){
            $menu .=' <ul class="sidebar-menu">';
            foreach($modulos as $modulo){
                if((int) $modulo->idmodulo > 0) {
                	$totalHijosExisten = 0;
                	$hijos   = $this->buscaModulosHijos($modulo->idmodulo);
                	if(count($hijos) > 0){
                		foreach($hijos as $idHijo){
                			if($this->existeEnSeleccion($idHijo, $arrayModulos) == 1){
                				$totalHijosExisten++;
                			}
                		}
                	}
                    if(in_array($modulo->idmodulo, $arrayModulos) && $totalHijosExisten > 0){
                        $menu .= '<li class="treeview">
                                        <a href="/'.Constantes::THIS_SERVER_NAME.'/public/#"  >
                                            <i class="' . $modulo->clase . '"></i>
                                            <span>' . trim($modulo->nombre) . '</span>
                                            <i class="fa fa-angle-left pull-right"></i>
                                        </a>';
                        $submodulos = Modulo::where('parent', '=', $modulo->idmodulo)->orderBy('orden')->get();                        
                        if (count($submodulos) > 0) {
                            $menu .= '<ul class="treeview-menu">';
                            foreach ($submodulos as $submodulo) {
                                if(in_array($submodulo->idmodulo, $arrayModulos)) {
                                	$trimodulos = Modulo::where('parent', '=', $submodulo->idmodulo)->orderBy('orden')->get();
                                    $menu .= '<li>
                                                <a href="/'.Constantes::THIS_SERVER_NAME.'/public' . $submodulo->recurso . '">
                                                    <i class="' . $submodulo->clase . '" aria-hidden="true"></i>
                                                    ' . trim($submodulo->nombre);
                                    if (count($trimodulos) > 0) {
                                    	$menu .= '<i class="fa fa-angle-left pull-right"></i>';
                                     }
									$menu .= '</a>';                                    
                                    if (count($trimodulos) > 0) {
                                    	$menu .= '<ul class="treeview-menu">';
                                    	foreach ($trimodulos as $trimodulo) {
                                    		if(!in_array($trimodulo->idmodulo, $arrayModulos)) {
                                    			$menu .= '<li>
                                                <a href="/'.Constantes::THIS_SERVER_NAME.'/public' . $trimodulo->recurso . '">
                                                    <i class="' . $trimodulo->clase . '" aria-hidden="true"></i>
                                                    ' . trim($trimodulo->nombre) . '
                                                </a></li>';
                                    		}
                                    	}
                                    	$menu .= '</ul>';                                    	
                                    }                
                                    $menu .= '</li>';
                                }
                            }
                            $menu .= '</ul>';
                        }
                        $menu .= '</li>';
                    }
                }
            }
            $menu .='<li>
                    <a href="/'.Constantes::THIS_SERVER_NAME.'/public/logout")}}">
                        <i class="fa fa-window-close-o fa-lg"></i><span>Cerrar Sesión</span>
                    </a>
                </li>';
            $menu .='</ul>';            
        }
        return $menu;
    }

    private function readMenu(){
        $filename = file_get_contents(Constantes::PATH_MENU);
        $sx = simplexml_load_string($filename);
        return json_decode( json_encode($sx) , 1);
    }



    private function registraAcceso($user, $status, $idUser){
        $ip = "";
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        try{
	        $carbon = new \Carbon\Carbon();
	        $info= $this->detect();
	        $acceso = new Acceso();
	        $acceso->usuario    = $user;
	        $acceso->idusuario  = $idUser;
	        $acceso->fecha      = $carbon->now()->toDateTimeString();
	        $acceso->status     = $status;
	        $acceso->explorador = $info["browser"];
	        $acceso->so 		= $info["os"];
	        $acceso->ip 		= $ip;
	        $acceso->save();
        }catch(\Exception $e){
        	$this->logger->error($e->getMessage());
        	
        }
    }

    private function detect(){
        $browser=array("IE","OPERA","MOZILLA","NETSCAPE","FIREFOX","SAFARI","CHROME");
        $os=array("WIN","MAC","LINUX");
        $info['browser'] = "OTHER";
        $info['os'] = "OTHER";
        # buscamos el navegador con su sistema operativo
        foreach($browser as $parent){
            $s = strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $parent);
            $f = $s + strlen($parent);
            $version = substr($_SERVER['HTTP_USER_AGENT'], $f, 15);
            $version = preg_replace('/[^0-9,.]/','',$version);
            if ($s){
                $info['browser'] = $parent;
                $info['version'] = $version;
            }
        }
        # obtenemos el sistema operativo
        foreach($os as $val){
            if (strpos(strtoupper($_SERVER['HTTP_USER_AGENT']),$val)!==false)
                $info['os'] = $val;
        }
        # devolvemos el array de valores
        return $info;
    }


    public function logout(){
        Session()->flush();
        Auth::logout();
        return redirect('/');
    }

    protected function getGuard(){
        return property_exists($this, 'guard') ? $this->guard : null;
    }

    /**
     * Determine if the class is using the ThrottlesLogins trait.
     *
     * @return bool
     */
    protected function isUsingThrottlesLoginsTrait(){
        return in_array(
            ThrottlesLogins::class, class_uses_recursive(static::class)
        );
        //return 1;
    }

    /**
     * MAMP
     * Metodo modificado para cargar datos en sesion
     * @param Request $request
     * @param $throttles
     * @param $credentials
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function handleUserWasAuthenticatedSession(Request $request, $throttles, $credentials){
        if ($throttles) {
            $this->clearLoginAttempts($request);
        }
        $user = Auth::guard($this->getGuard())->user();
        if (method_exists($this, 'authenticated')) {
            //return $this->authenticated($request, Auth::guard($this->getGuard())->user());
            return $this->authenticated($request, $user);
        }
        $this->loadSesionData($user);
        return redirect()->intended('home');
    }

    /**
     * MAMP
     * carga en la sesion los datos requeridos para armar el menu y los permisos del usuario
     * @param $credentials
     */
    private function loadSesionData($user){
        $carbon   = new \Carbon\Carbon();
        Session()->put('idUser', $user['id']);
        Session()->put('user',   $user['email']);
        Session()->put('dummy',   $user['dummy']);
        Session()->put('userName', $user['name']);
        Session()->put('userIdPerfil', $user['idperfil']);
        Session()->put('userIngreso', substr($carbon->now()->toDateTimeString(),0,19));
        Session()->put('fechaAcceso',$carbon->now());
        Session()->put('path_web', Constantes::PATH_WEB);
        Session()->put('path_sis', Constantes::PATH_SYSTEM);
        $this->logger->debug("loadSesionData: " . $user);
        if($user['idperfil'] == Constantes::ACTIVO ){
        	$this->logger->debug("Si es admin");
        	Session()->put('isAdmin', true);
        }else{
        	$this->logger->debug("NO es admin");
        	Session()->put('isAdmin', false);
        }
        $perfil = Perfil::findOrFail($user['idperfil']);        
        Session()->put('roleName', $perfil['nombre']);
        $idEmpresa = '';
        if( $perfil['idempresa'] != null &&  $perfil['idempresa'] != "") {
            $idEmpresa = $perfil['idempresa'];
        } else {
            //Si el perfil no tiene empresa entonces puede ser el super administrador o el administrador de empresas
            if( $user['idempresa'] != null && $user['idempresa'] != "")
                $idEmpresa = $user['idempresa'];
//             print_r($idEmpresa);
//             die("scsad");
        }
        
        //Se carga el id de la empresa a la que pertenece el usuario
        Session()->put('userEnterprise', $idEmpresa);
        $logotipo = "";
        if((int)$idEmpresa > 0){
            $empresas = Empresa::where('idempresa' , '=' , $idEmpresa)->get();
            foreach ($empresas as $empresa){
                $logotipo = $empresa->logotipo;
                Session()->put('logotipo', $logotipo);
                Session()->put('nmEmpresa', $empresa->nombre);
            }
        }
        	
        
        
        $this->generateMenu($user);
    }

    protected function getCredentials(Request $request)
    {
    	return $request->only($this->loginUsername(), 'password');
    }
    
    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function loginUsername()
    {
    	return property_exists($this, 'username') ? $this->username : 'email';
    }
    
    /**
     * @return string Campo por el caual se hace el logueo
     */
    public function username(){
        return 'email';
    }

    function toCombo($permisos){
        $array = array();
        if(count($permisos) > 0){
            foreach($permisos as $permiso){
                $array[$permiso->idpermiso] = $permiso->nombre;
            }
        }
        return $array;
    }
 
    private function generaIdsModulos($modulosActivos){
    	$array = array();
    	foreach($modulosActivos as $modulo){
    		if(!in_array($modulo->idmodulo,$array)){
    			$array[] = $modulo->idmodulo;
    		}
    	}
    	return $array;
    }
    
    private function regresaIdentificadores($modulosActivos){
    	$identificadores = array();
    	if(count($modulosActivos) > 0){
    		foreach($modulosActivos as $moduloAct){
    			$identificadores[$moduloAct->identificador] = $moduloAct->parent;
    		}
    	}
    	return $identificadores;
    }
    
    private function regresaParents($modulos){
    	$parents = array();
    	if(count($modulos) > 0){
    		foreach($modulos as $modulo){
    			$parents[$modulo->idmodulo] = $modulo->nombre;
    		}
    	}
    	return $parents;
    }
    
    
    private function getArray($permisosUser,$permisos){
        $return = array();
        foreach($permisosUser as $value){
            $return[] = $permisos[$value];
        }
        return $return;
    }
    protected function hasTooManyLoginAttempts(Request $request)
    {
    	$intentos = Constantes::INTENTOS;
    	$minutos  = Constantes::MININTENTOS;
    	if($intentos < Constantes::PERFIL_ADMIN_EMPRESA){
    		$intentos = Constantes::PERFIL_ADMIN_EMPRESA;
    	}else{
    		$intentos--;
    	}
    	
    	if($minutos == Constantes::NOACTIVO){
    		$minutos = 1;
    	}
    	return $this->limiter()->tooManyAttempts($this->throttleKey($request), $intentos, $minutos);
    }
}