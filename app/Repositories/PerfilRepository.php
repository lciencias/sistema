<?php namespace sistema\Repositories;

use sistema\Repositories\Eloquent\Repository;
use sistema\Http\Requests\PerfilFormRequest;
use sistema\Models\Perfil;
use sistema\Models\PerfilModulo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use sistema\Policies\RegistraBitacora;
use sistema\Policies\Constantes;
use sistema\Models\PerfilModuloPermiso;
use Illuminate\Http\Request;

/**
 * Clase de servicio de acceso a datos de perfiles
 * @author Miguel Molina 05/04/2017
 *
 */
class PerfilRepository extends Repository {

	//nombre del campo que se registrara en la bitacora
	public static $campoBase = 'nombre';
	
    /**
     * Specify Model class name
     *
     * @return mixed
     */
	/**
	 * Metodo que regresa el modelo utilizado
	 * {@inheritDoc}
	 * @see \sistema\Repositories\Eloquent\Repository::model()
	 */
    function model()
    {
        return 'sistema\Models\Perfil';
    }
        
    /**
     * Metodo para registrar la informaación del perfil
     * @param PerfilFormRequest $request
     * @throws \Exception
     */
    function save(PerfilFormRequest $request) {    	
    	$modulos = $request->input ( 'permisos' );
    	$tmp = $registros = $permisos = array();
    	DB::beginTransaction ();
    	try {
    		$perfil = new Perfil ();
    		$perfil->nombre = trim($request->get ( 'nombre' ));
    		$perfil->descripcion = trim($request->get ( 'descripcion' ));
    		$perfil->idempresa = $request->get ( 'empresa' );
    		$perfil->activo = '1';
    		$perfil->save ();    		
    		//eliminar los id de perfiles
    		$registros = $this->construyeModulosPermisos($modulos);
    		//Insert Perfil-modulo
    		foreach($registros['modulos'] as $idModulo){
    			if( (int) $idModulo > 0){
    				$perfilmodulo = new PerfilModulo ();
    				$perfilmodulo->idperfil = $perfil->idperfil;
    				$perfilmodulo->idmodulo = $idModulo;
    				$perfilmodulo->save ();
    				$permisos[$idModulo] = $perfilmodulo->idperfil_modulo;
    			}    			 
    		}
    		
    		//Insert Perfil-modulo-permiso
    		foreach($registros['permisos'] as $idModuloPermiso){
    			$tmp   = explode('-',$idModuloPermiso);
    			$id    = (int) $permisos[$tmp[0]];
    			$value = (int) $tmp[1];
    			if((int)$id > 0 && (int)$value > 0){
    				$perfilModuloPermiso = new PerfilModuloPermiso();
    				$perfilModuloPermiso->idperfil_modulo = $id;
    				$perfilModuloPermiso->idpermiso       = $value;
    				$perfilModuloPermiso->save();
    			}
    		}    		
    		//Bitacora
    		$controller = $this->obtenerNombreController($request);
    		$moduloId   = $this->obtenModuloId($controller);
    		$perfilesPermisos = $this->comparaPerfilPermisos(Constantes::ACCION_ALTA,$perfil,$perfil);
    		$this->insertaBitacora(Constantes::ACCION_ALTA, null, $perfil->getAttributes(), $perfil->idperfil,$controller,$moduloId,Session::get('idUser'),$perfilesPermisos,self::$campoBase);
    		DB::commit ();
    	} catch ( \Exception $e ) {
    		DB::rollback ();
    		throw $e;	
    	}	
    }
    
    
    private function comparaPerfilPermisos($tipo,$perfil,$perfilA){
    	$array = array();
    	if($tipo == Constantes::ACCION_ALTA){
    		$array['perfilModulosPermisosAnt']  = '';
    		$array['perfilModulosPermisosDes']  = $this->exportaCadena($this->getPerfilModulosPermisosUser($perfil->idperfil));    
    	}else{
    		$array['perfilModulosPermisosAnt']  = $this->exportaCadena($this->getPerfilModulosPermisosUser($perfilA->idperfil));
    		$array['perfilModulosPermisosDes']  = $this->exportaCadena($this->getPerfilModulosPermisosUser($perfil->idperfil));
    	}
    	return $array;
    }
    
    private function exportaCadena($objetos){
    	$tmp = array();
    	$cadena = "";
    	if(count($objetos) > 0){
    		foreach($objetos as $objeto){
    			if( (int) $objeto->idpermiso > 0){
   					$tmp[] = "{Perfil : ".$objeto->idperfil."  Modulo ".$objeto->idmodulo." : Permiso ".$objeto->idpermiso."}";
    			}
    		}
    		$cadena = implode(' ',$tmp);
    	}
    	return $cadena;
    }
    
    
    /**
     * Metodo para actualizar la información del perfil
     * @param Perfil $perfil
     * @param array $modulos
     * @throws \Exception
     */
    function updatePerfil(Perfil $perfil,$modulos, Perfil $perfilA) {
    	$tmp = $registros = $permisos = array();
    	DB::beginTransaction ();
    	try {    		 
    		$perfil->update ();    		
    		$registros = $this->construyeModulosPermisos($modulos);
    		if(count($registros)>0){
    			// consultamos los idperfil_modulos asignados y Elimina perfiles
    			$results = $this->regresaIdPerfilModulo($perfil);
    			if(count($results)>0){
    				$this->eliminaPerfilModulo($results);
    			}    			 
    			if(count($registros['modulos'])>0){
		    		//Insert Perfil-modulo		    		
    				foreach($registros['modulos'] as $idModulo){
		    			if( (int) $idModulo > 0){
		    				$perfilmodulo = new PerfilModulo ();
		    				$perfilmodulo->idperfil = $perfil->idperfil;
		    				$perfilmodulo->idmodulo = $idModulo;
		    				$perfilmodulo->save ();
		    				$permisos[$idModulo] = $perfilmodulo->idperfil_modulo;
		    			}
		    		}
	    		
		    		//Insert Perfil-modulo-permiso
		    		foreach($registros['permisos'] as $idModuloPermiso){
		    			$tmp   = explode('-',$idModuloPermiso);
		    			$id    = (int) $permisos[$tmp[0]];
		    			$value = (int) $tmp[1];
		    			if((int)$id > 0 && (int)$value > 0){
		    				$perfilModuloPermiso = new PerfilModuloPermiso();
		    				$perfilModuloPermiso->idperfil_modulo = $id;
		    				$perfilModuloPermiso->idpermiso       = $value;
		    				$perfilModuloPermiso->save();
		    			}
		    		}    
    			}
    		}
    		//Bitacora
    		$moduloId   = $this->obtenModuloId(Constantes::CONTROLLER_PERFIL);
    		$perfilesPermisos = $this->comparaPerfilPermisos(Constantes::ACCION_ACTUALIZAR,$perfil,$perfilA);
    		$this->insertaBitacora(Constantes::ACCION_ACTUALIZAR, $perfilA->getAttributes(), $perfil->getAttributes(), $perfil->idperfil,Constantes::CONTROLLER_PERFIL,$moduloId,Session::get('idUser'),$perfilesPermisos,self::$campoBase);
    		DB::commit ();
    		
    	} catch ( \Exception $e ) {
    		DB::rollback ();
    		throw $e;   		 
    	}    	 
    }
        
    /**
     * Metodo para eliminar un perfil
     * @param array $results
     * @return boolean
     */
    function eliminaPerfilModulo($results){
		$arrayIds = array();
		if($results != null ){
			foreach($results as $result ){
				$arrayIds[] = $result->idperfil_modulo;
			}		
			$this->eliminaPerfilModuloPermiso($arrayIds);
			if(count($arrayIds) > 0){
				foreach($arrayIds as $idPerfil){
					PerfilModulo::destroy($idPerfil);
				}
			}				
			return true;
   		}   		
		return false;
	}
    
    function eliminaPerfilModuloPermiso($arrayIds){
    	try{
	    	if(count($arrayIds) > 0){
	    		$results = DB::table('perfil_modulo_permiso')->select('idperfil_modulo_permiso')
	    				->whereIn('perfil_modulo_permiso.idperfil_modulo', $arrayIds)
	    				->get();
	    		if(count($results) > 0){
	    			foreach($results as $result){
	    				PerfilModuloPermiso::destroy($result->idperfil_modulo_permiso);
	    			}
	    		}
	    	}
    	}
    	catch(\Exception $e){
    		
    	}
    }
	
  /**
   * Metodo que elimina un perfil
   * @param int $id
   * @throws \Exception
   */  
    function eliminaPerfil($id){
    	if((int) $id > 0){
    		DB::beginTransaction ();
    		try{    			
    			$perfil  = $this->getPerfil($id);
    			$perfilA = clone $perfil;
    			$results = $this->regresaIdPerfilModulo($perfil);
    		//	$this->eliminaPerfilModulo($results);
    			$perfil->activo = false;
    			$perfil->update();
    			//Bitacora
    			$moduloId   = $this->obtenModuloId(Constantes::CONTROLLER_PERFIL);
    			$this->insertaBitacora(Constantes::ACCION_ELIMINAR, $perfilA->getAttributes(), $perfil->getAttributes(), $perfil->idperfil,Constantes::CONTROLLER_PERFIL,$moduloId,Session::get('idUser'),array(),self::$campoBase);    			 
    			DB::commit ();
    		}
    		catch(\Exception $e){
    			DB::rollback ();
    			throw $e;
    		}
    	}
    	
    }
	/**
	 * Metodo qye regresa el objeto perfil de un id solicitado
	 * @param int $id
	 */
    function getPerfil($id){
    	return  Perfil::findOrFail ( $id );
    }
    
    
    /**
     * Metodo que sirve para activar el perfil
     * @param  $id
     * @throws \Exception
     */
    function activaPerfil($id){
    	if((int) $id > 0){
    		DB::beginTransaction ();
    		try{
    		    $perfil = Perfil::findOrFail ( $id );
    		    $perfilA = clone $perfil;
    			$perfil->activo = true;
    			$perfil->update();
    			//Bitacora
    			$moduloId   = $this->obtenModuloId(Constantes::CONTROLLER_PERFIL);
    			$this->insertaBitacora(Constantes::ACCION_ACTIVAR, $perfilA->getAttributes(), $perfil->getAttributes(), $perfil->idperfil,Constantes::CONTROLLER_PERFIL,$moduloId,Session::get('idUser'),array(),self::$campoBase);    			 
    			DB::commit ();
    		}
    		catch ( \Exception $e ) {
    			DB::rollback ();
    			throw $e;
    		}
    	}
    
    }
    
    
    function construyeModulosPermisos($modulos){
    	$arrayModulos = $arrayPermisos = $tmp = array();
    	if(count($modulos) > 0){
    		foreach($modulos as $modulo){
    			$tmp = explode('-',$modulo);
    			if(!in_array($tmp[1], $arrayModulos))
    				$arrayModulos[] = $tmp[1];
    				if(!in_array($tmp[2], $arrayModulos))
    					$arrayModulos[] = $tmp[2];
    					if(!in_array($tmp[2]."-".$tmp[3], $arrayPermisos))
    						$arrayPermisos[] = $tmp[2]."-".$tmp[3];
    		}
    	}
    	return array('modulos' => $arrayModulos,'permisos' => $arrayPermisos);
    }
    
    function regresaIdPerfilModulo($perfil){
    	$result = null;
    	if((int) $perfil->idperfil > 0){
    		$result = DB::table('perfil_modulo')->distinct()->select('idperfil_modulo')->where('idperfil',$perfil->idperfil)->get();
    	}
    	return $result;
    }
    
    function getAllPerfiles($request,$admin,$idEmpresaUser){
		$query          = trim ( $request->get ( 'searchText' ) );
		$idEmpresaBusca = $request->get ( 'idEmpresaBusca' );
		$activo         = $request->get ( 'activo' );
		if(trim($activo) == ''){
			$activo = 1;
		}
		if(trim($activo) == -1){
			$activo = '';
		}
		$perfiles = DB::table ( 'perfil' )->where('idperfil','>',1);
		if(trim($query) != ''){
			$perfiles = $perfiles->where ( 'nombre', 'LIKE', '%' . $query . '%' );
		}
		if(trim($activo) != ''){
			$perfiles = $perfiles->where('activo' , '=', $activo);
		}
		if(!$admin){
			$perfiles = $perfiles->where('idempresa' , '=', $idEmpresaUser);
		}else{
			if ($idEmpresaBusca != "" && $idEmpresaBusca != -1){
				$perfiles = $perfiles->where('idempresa' , '=', $idEmpresaBusca);
			}
		}
		$perfiles = $perfiles->orderBy ( 'nombre', 'asc' )->paginate (8);
		return $perfiles;

    }
    
   
   
}