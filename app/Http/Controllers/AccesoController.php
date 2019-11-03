<?php

namespace sistema\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use sistema\Repositories\AccesoRepository;

class AccesoController extends Controller
{
	private $accesoRepository;
    function __construct(AccesoRepository $accesoRepository){
    	parent::__construct();
    	$this->accesoRepository = $accesoRepository;
    }
    
    /**
     * Metodo para buscar los accesos en los logs
     * @param Request $request
     */
	public function index(Request $request) {
		Session::forget('message-warning');	
		$controller = $this->accesoRepository->obtenerNombreController($request);
		$moduloId   = $this->accesoRepository->obtenModuloId($controller);
		return view ( 'bitacoras.acceso.index', ["moduloId"  => $moduloId,"isAdmin"   => $this->isAdmin,"idRol" => $this->idRol]);
	}
		
	/**
	 * Metodo para buscar usuarios
	 * @param Request $request
	 */
	public function buscar(Request $request){
	    try {
            Session::forget('message-warning');
            $moduloId = $request->get('idModulo');
            $opciones = $this->parametros($request, 'fecha');
            $view = $this->buscaAccesos($request, $opciones, $moduloId);
            return response()->json(view('bitacoras.acceso.busquedaAcceso', $view)->render());
        }catch(\Exception $e){
            $this->log->error("Error:\nMensaje: ".$e->getMessage()."\n".$e->getMessage());
        }
	}
	
// 	private function parametros1(Request $request){
// 		$noPage = Constantes::NOPAGINA;
// 		$noRegs = Constantes::getPaginator();
// 		$orden  = 'desc';
// 		$campo  = "idacceso";
// 		if($request){
// 			if( (int) $request->get('noRegs') > 0){
// 				$noRegs = $request->get('noRegs');
// 			}
// 			if( (int) $request->get('page') > 0){
// 				$noPage = $request->get('page');
// 			}
			
// 		}
// 		return array('campo' => $campo,'orden' => $orden, 'nopage' => $noPage, 'noregs' => $noRegs);
// 	}
	
	private function buscaAccesos(Request $request,$opciones,$moduloId){
		$accesos = array();
		$session    = Session()->get('permisos');
		$buscar     = $this->validaParametros($request);
		$userFiltro[]     =  ['activo', '=', true];
		if($this->idRol == 2){
			$userFiltro[] =  ['idEmpresa', '=', $this->idEmpresaUser];
		}
		if($this->idRol > 2){
			$userFiltro[] =  ['id', '=', Session::get('idUser')];
		}
		$usuarios 	 = $this->accesoRepository->getAllUsuarios($userFiltro);
        $usuariosCat = $this->accesoRepository->catalogoUsuarios($usuarios);
       
		if ($buscar > 0){
			$filtros    = $this->generaFiltrosAccesos($request);	
			Session::put('busqueda_Accesos', $filtros);
			$total      = $this->accesoRepository->findByCount($filtros);
			$accesos 	= $this->accesoRepository->findByColumn($filtros,$opciones);
			return ["accesos"    => $accesos,
					"total"      => $total,
					"leyenda"    => $this->accesoRepository->generaLetrero($total,count($accesos),$opciones),
					"idacceso"   => $request->get('idacceso'),
					"status"     => $request->get('status'),
					"fecha"      => $request->get ('fecha'),
					"ip"       	 => $request->get ('ip'),
					"so"         => $request->get ('so'),
					"explorador" => $request->get ('explorador'),						
					"moduloId"  => $moduloId,
					"sessionPermisos" => $session[$moduloId],
					"isAdmin"   => $this->isAdmin,
					"idRol"	  => $this->idRol,
					"usuarios"  => $usuarios,
					"noPage" 	  => $opciones['nopage'],
					"noRegs" 	  => $opciones['noregs'],
					"usuariosCat" => $usuariosCat,
					"idusuario"    => $request->get('idusuario'),
					"fechaInicio"=> $request->get('fechaInicio'),
					"fechaFinal" => $request->get('fechaFinal'),
					"catEstatus"  => array(1 => 'Login',2=> 'Fallido'),
					"fechainiacceso"=>$request->get('fechainiacceso'),
					"fechafinacceso"=>$request->get('fechafinacceso'),
			];
		}
	}
	
	/**
	 * Metodo que se encarga de filtrar los registros del modulo de Usuarios
	 * @param Request $request
	 */
	private function generaFiltrosAccesos(Request $request){
		$filtros   = array ();
		$idacceso  = (int) $request->get('idacceso') + 0;
		$idusuario = (int) $request->get('idusuario') + 0;		
		$status    = (int) $request->get('status') + 0;		
		$fecha     = trim($request->get ('fecha'));
		$ip        = trim($request->get ('ip'));
		$so        = trim($request->get ('so'));
		$explorador= trim($request->get ('explorador'));
		$fechainiacceso     = trim($request->get ('fechainiacceso'));
		$fechafinacceso     = trim($request->get ('fechafinacceso'));

		
		$filtros[] = ['acceso.idacceso', '>', 0];		
		if($idacceso > 0){
			$filtros[] = ['acceso.idacceso', '=',$idacceso];
		}		
		if($idusuario > 0){
			$filtros[] = ['acceso.idusuario', '=',$idusuario];
		}
		if($status > 0){
			$filtros[] = ['acceso.status', '=',$status];
		}
		if(trim($fecha) != ''){
			$filtros[] = ['acceso.fecha', 'LIKE', '%' . $fecha . '%'];
		}
		if(trim($ip) != ''){
			$filtros[] = ['acceso.ip', 'LIKE', '%' . $ip . '%'];
		}
		if(trim($so) != ''){
			$filtros[] = ['acceso.so', 'LIKE', '%' . $so . '%'];
		}
		if(trim($explorador) != ''){
			$filtros[] = ['acceso.explorador', 'LIKE', '%' . $explorador . '%'];
		}
		if(trim($fechainiacceso) != ''){
			$fecha1= \Carbon\Carbon::createFromFormat('d-m-Y', $fechainiacceso)->startOfDay();
			$filtros[] = ['acceso.fecha', '>=', $fecha1];
		}
		if(trim($fechafinacceso) != ''){
			$fecha2= \Carbon\Carbon::createFromFormat('d-m-Y', $fechafinacceso)->endOfDay();
			$filtros[] = ['acceso.fecha', '<=', $fecha2];
		}
		return $filtros;
	}

	
	private function catalogoUsuarios($usuarios){
		$catalogo = array();
		if(count($usuarios) > 0){
			foreach($usuarios as $usu){
				$catalogo[$usu->id] = $usu->name;
			}
		}
		return $catalogo;
	}
	
	private function catalogoIds($usersIdEmpresa){
		$catalogo = array();
		if(count($usersIdEmpresa) > 0){
			foreach($usersIdEmpresa as $usu){
				$catalogo[] = $usu->id;
			}
		}
		return $catalogo;
		
	}
	private function validaParametros($request){		
		if( (int) $request->get('idUsuario') > 0){
			return 1;
		}
		if( trim($request->get('fechaInicio')) != ''){
			return 1;
		}
		if( trim($request->get('fechaFinal')) != ''){
			return 1;
		}
		if($this->idRol == 2){
			return 1;		
		}
		if($this->idRol > 2){
			return 1;
		}
		return 1;
	}
}
