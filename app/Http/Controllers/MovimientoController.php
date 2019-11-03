<?php

namespace sistema\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use sistema\Repositories\MovimientoRepository;

class MovimientoController extends Controller
{
	private $movimientoRepository;
	
	
    function __construct(MovimientoRepository $movimientoRepository){
    	parent::__construct();
    	$this->movimientoRepository = $movimientoRepository;
    }
    
    /**
     * Metodo para buscar los accesos en los logs
     * @param Request $request
     */
	public function index(Request $request) {
		Session::forget('message-warning');
		$controller = $this->movimientoRepository->obtenerNombreController($request);
		$moduloId   = $this->movimientoRepository->obtenModuloId($controller);
		$session    = Session()->get('permisos');
		return view ( 'bitacoras.movimientos.index', ["isAdmin"    => $this->isAdmin,
				"idRol"		 => $this->idRol,
				"moduloId"    => $moduloId,
				"sessionPermisos" => $session[$moduloId]]);
	}
			
	public function buscar(Request $request){
	    try {
            Session::forget('message-warning');
            $moduloId = $request->get('idModulo');
            $opciones = $this->parametros($request,'fecha');
            $view = $this->buscaMovimientos($request, $opciones, $moduloId);
            return response()->json(view('bitacoras.movimientos.busquedaBitacora', $view)->render());
        }
        catch(\Exception $e){
            $this->log->error("Error:\nMensaje: ".$e->getMessage()."\n".$e->getMessage());
        }
	}
	
// 	public function parametros(Request $request){
// 		$noPage = Constantes::NOPAGINA;
// 		$noRegs = Constantes::getPaginator();
// 		$orden  = 'desc';
// 		$campo  = "id";
// 		if($request){
// 			if( (int) $request->get('noRegs') > 0){
// 				$noRegs = $request->get('noRegs');
// 			}
// 			if( (int) $request->get('page') > 0){
// 				$noPage = $request->get('page');
// 			}
// // 			if( trim($request->get('orden')) != ''){
// // 				$orden = $request->get('orden');
// // 			}
// 		}
// 		return array('campo' => $campo,'orden' => $orden, 'nopage' => $noPage, 'noregs' => $noRegs);
// 	}
	
	private function buscaMovimientos(Request $request,$opciones,$moduloId){
		$session    = Session()->get('permisos');
		$buscar     = $this->validaParametros($request);
		$userFiltro[]=  ['activo', '=', 1];
		if($this->idRol == 2){
			$userFiltro[] =  ['idEmpresa', '=', $this->idEmpresaUser];
		}
		if($this->idRol > 2){
			$userFiltro[] =  ['id', '=', Session::get('idUser')];
		}
		$modulos 	= $this->movimientoRepository->getModuloChildren();
		$usuarios 	= $this->movimientoRepository->getAllUsuarios($userFiltro);
		$movimientos= $this->movimientoRepository->getMovimientos();
		$usuariosCat= $this->catalogoUsuarios($usuarios);
		$modulosCat = $this->catalogoModulos($modulos);		
		if ($buscar > 0){
			$filtros    = $this->generaFiltrosMovimientos($request);
			Session::put('busqueda_Movimientos', $filtros);
			$total      = $this->movimientoRepository->findByCount($filtros);
			$bitacoras 	= $this->movimientoRepository->findByColumn($filtros,$opciones);
			return ["bitacoras"  => $bitacoras,
					"total"      => $total,
					"leyenda"    => $this->movimientoRepository->generaLetrero($total,count($bitacoras),$opciones),
					"isAdmin"    => $this->isAdmin,
					"idRol"		 => $this->idRol,
					"modulos"    => $modulos,
					"usuarios"   => $usuarios,
					"usuariosCat"=> $usuariosCat,
					"modulosCat" => $modulosCat,
					"movimientosCat" => $movimientos,
					"moduloId"    => $moduloId,
					"sessionPermisos" => $session[$moduloId],
					"noPage" 	  => $opciones['nopage'],
					"noRegs" 	  => $opciones['noregs'],
					"id"		  =>$request->get('id'),
					"idmovimiento"=>$request->get('idmovimiento'),
					"iduser"   => $request->get('iduser'),
					"fecha"	      => $request->get('fecha'),
					"ip"	      => $request->get('ip'),
					"nombreRegistro" => $request->get('nombreRegistro'),
					"idmodulo"   => $request->get('idmodulo'),
					"fechainiacceso"=>$request->get('fechainiacceso'),
					"fechafinacceso"=>$request->get('fechafinacceso'),
				];
		}
	}
		
	private function generaFiltrosMovimientos(Request $request){
		$filtros   = array ();
		$id  		    = (int) $request->get('id') + 0;
		$iduser 	    = (int) $request->get('iduser') + 0;
		$idmodulo 	    = (int) $request->get('idmodulo') + 0;
		$idmovimiento   = $request->get('idmovimiento');
		$fecha     	    = trim($request->get ('fecha'));
		$nombreRegistro = trim($request->get ('nombreRegistro'));
		$ip			    = trim($request->get ('ip'));
		$fechainiacceso     = trim($request->get ('fechainiacceso'));
		$fechafinacceso     = trim($request->get ('fechafinacceso'));
		
		if($this->idRol > 2){
			$filtros[] =  ['bitacora.iduser', '=', Session::get('idUser')];
		}
				
		$filtros[] = ['bitacora.id', '>', 0];
		if($id > 0){
			$filtros[] = ['bitacora.id', '=',$id];
		}
		if($iduser > 0){
			$filtros[] = ['bitacora.iduser', '=',$iduser];
		}
		if($idmodulo > 0){
			$filtros[] = ['bitacora.idmodulo', '=',$idmodulo];
		}
		if($idmovimiento != null && $idmovimiento != ''){
			$filtros[] = ['bitacora.tipo_movimiento', '=',$idmovimiento];
		}		
		if(trim($fecha) != ''){
			$filtros[] = ['bitacora.fecha', 'LIKE', '%' . $fecha . '%'];
		}
		if(trim($nombreRegistro) != ''){
			$filtros[] = ['bitacora.nombre_registro', 'LIKE', '%' . $nombreRegistro . '%'];
		}		
		if(trim($ip) != ''){
			$filtros[] = ['bitacora.ip', 'LIKE', '%' . $ip . '%'];
		}
		if(trim($fechainiacceso) != ''){
			$fecha1= \Carbon\Carbon::createFromFormat('d-m-Y', $fechainiacceso)->startOfDay();
			$filtros[] = ['bitacora.fecha', '>=', $fecha1];
		}
		if(trim($fechafinacceso) != ''){
			$fecha2= \Carbon\Carbon::createFromFormat('d-m-Y', $fechafinacceso)->endOfDay();
			$filtros[] = ['bitacora.fecha', '<=', $fecha2];
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
	
	private function catalogoModulos($modulos){
		$catalogo = array();
		if(count($modulos) > 0){
			foreach($modulos as $mod){
				$catalogo[$mod->idmodulo] = $mod->nombre;
			}
		}
		return $catalogo;
	}
	
	private function catalogoPermisos($permisos){
		$catalogo = array();
		if(count($permisos) > 0){
			foreach($permisos as $per){
				$catalogo[$per->idpermiso] = $per->nombre;
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
		if( (int) $request->get('idModulo') > 0){
			return 1;
		}
		if( (int) $request->get('idMovimiento') > 0){
			return 1;
		}
		if( trim($request->get('fechaInicio')) != ''){
			return 1;
		}
		if( trim($request->get('fechaFinal')) != ''){
			return 1;
		}
		return 1;
	}
	private function url($moduloId,$request){
		$url = $moduloId."|";
		if( (int) $request->get('idUsuario') > 0){
			$url.="idUsuario=".$request->get('idUsuario').'|';
		}
		if( (int) $request->get('idModulo') > 0){
			$url.="idModulo=".$request->get('idModulo').'|';
		}
		if( (int) $request->get('idMovimiento') > 0){
			$url.="idMovimiento=".$request->get('idMovimiento').'|';
		}
		if( trim($request->get('fechaInicio')) != ''){
			$url.="fechaInicio=".$request->get('fechaInicio').'|';
		}
		if( trim($request->get('fechaFinal')) != ''){
			$url.="fechaFinal=".$request->get('fechaFinal').'|';
		}
		return $url;
	}
	
}
