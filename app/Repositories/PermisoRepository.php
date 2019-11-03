<?php namespace sistema\Repositories;
ini_set('display_errors', 'on');
use Illuminate\Support\Facades\DB;
use sistema\Models\ModuloPermiso;
use sistema\Models\Perfil;
use sistema\Models\Permiso;
use sistema\Repositories\Eloquent\Repository;
		
/**
 * Clase de servicio de acceso a datos de permisos
 * @author Miguel Molina 05/04/2017
 *||||
 */
class PermisoRepository extends Repository {

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
        return 'sistema\Models\Permiso';
    }
        
    /**
     * Metodo para registrar la informaación del permiso
     * @param  $request
     * @throws \Exception
     */
    function savePermiso($request) {
    	$tmpArray = array();
    	$modulos  = $request->input ( 'permisos' );
    	if(count($modulos) > 0){
    		DB::beginTransaction ();
    		try{
    			ModuloPermiso::truncate();
    			//DB::statement("DBCC CHECKIDENT (modulo_permiso, RESEED,1)");
    			DB::statement("ALTER TABLE modulo_permiso AUTO_INCREMENT = 1");

    			foreach($modulos as $moduloPermiso){
    				$tmpArray   = explode('-', $moduloPermiso);
    				if((int) $tmpArray[2] > 0 && (int) $tmpArray[3] > 0 ){    					   				
    					$modPermiso = new ModuloPermiso();
    					$modPermiso->idmodulo  = (int) $tmpArray[2];
    					$modPermiso->idpermiso = (int) $tmpArray[3];
    					$modPermiso->save();  
    					DB::commit ();
    				}
    			}
    		}
    		catch ( \Exception $e ) {
    	 		DB::rollback ();
     			throw $e;
	    	}    
    	}
    }
          
    
    /**
     * Metodo para actualizar la información del permiso
     * @param  $permiso
     * @param array $modulos
     * @throws \Exception
     */
    function updatePermiso(Permiso $permiso,$modulos, Permiso $permisoA) {
    }
        
    /**
     * Metodo para eliminar un permiso
     * @param array $results
     * @return boolean
     */
    function eliminaPermisoModulo($results){
	}
    
	
  /**
   * Metodo que elimina un permiso
   * @param int $id
   * @throws \Exception
   */  
    function eliminaPerfil($id){
    }
	/**
	 * Metodo qye regresa el objeto permiso de un id solicitado
	 * @param int $id
	 */
    function getPerfil($id){
    	return  Perfil::findOrFail ( $id );
    }
    
    
    function getAllModuloPermisosIds(){
    	$results = DB::table('modulo')
    	->join('modulo_permiso','modulo_permiso.idmodulo','=','modulo.idmodulo')
    	->join('permiso','modulo_permiso.idpermiso','=','permiso.idpermiso')
    	->select('modulo.parent','modulo_permiso.idmodulo','modulo_permiso.idpermiso')
    	->orderBy('modulo_permiso.idmodulo','asc','modulo_permiso.idpermiso','desc')
    	->get();
    	return $results;
    }
    
    
    function getArrayPermisos($modulosPermisos){    	
    	$array = array();
    	if(count($modulosPermisos) > 0){
    		foreach($modulosPermisos as $moduloPermiso){
    			if(!array_key_exists($moduloPermiso->parent,$array)){
    				$array[$moduloPermiso->parent] = 0;
    			}
    			$array[$moduloPermiso->idmodulo] []= $moduloPermiso->idpermiso;
    		}
    	}    	
    	return $array;
    }
}