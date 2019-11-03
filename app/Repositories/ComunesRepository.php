<?php namespace sistema\Repositories;

use sistema\Repositories\Eloquent\Repository;
use sistema\Models\Perfil;
use sistema\Models\Modulo;
use sistema\Models\PerfilModulo;
use sistema\Models\PerfilModuloPermiso;
use sistema\Models\ModuloPermiso;
use Illuminate\Support\Facades\DB;

/**
 * Clase de servicio de acceso a datos de perfiles
 * @author Miguel Molina 05/04/2017
 *
 */
class ComunesRepository extends Repository {
    
    function model(){
    	
    }
	
	function modulos($idPerfil){
		$datos = DB::table('perfil_modulo')
				->join('modulo','perfil_modulo.idmodulo','=','modulo.idmodulo')
				->where('perfil_modulo.idperfil','=',$idPerfil)->whereNull('modulo.parent')
				->select('perfil_modulo.idmodulo','modulo.nombre','modulo.parent')
				->get();
		return $datos;
	}
    function modulosHijos($idPerfil){
    	return DB::table('perfil_modulo')
    	->join('modulo','perfil_modulo.idmodulo','=','modulo.idmodulo')
    	->where('perfil_modulo.idperfil','=',$idPerfil)->whereNotNull('modulo.parent')
    	->select('perfil_modulo.idmodulo','modulo.nombre','modulo.parent')
    	->get();
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
    
}