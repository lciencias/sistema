<?php 
namespace sistema\Repositories;
use Illuminate\Support\Facades\DB;
use sistema\Models\Archivo;
use sistema\Repositories\Eloquent\Repository;


/**
 * Clase de servicio de acceso a datos de perfiles
 * @author Miguel Angel Molina 26/01/2018
 *
 */
class ArchivoRepository extends Repository {

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'sistema\Models\Archivo';
    }
    
    
    
    /**
     * Metodo que guarda el archivo en base de datos
     * @param $archivo
     * @throws \Exception
     */
    function saveArchivo($file,$string) { 
    	if ($file) {
    		DB::beginTransaction ();
    		try{
    			$archivo = new Archivo();
    			$archivo->nombre    = $file->getClientOriginalName();
    			$archivo->tipo      = trim(strtolower($file->getClientOriginalExtension()));
    			$archivo->contenido = $string;
    			$archivo->save ();
    			DB::commit ();
    			return $archivo;
    		}
	    	catch ( \Exception $e ) {
    	 		DB::rollback ();
     			throw new \Exception('Error al crear al Usuario '.$e);
	    	}    
    	}
    }
    
    function getArchivos(){
    	return DB::table('archivo')->get();
    }
    
    /**
     * Metodo qye regresa el objeto user
     * @param int $id
     */
    function getArchivo($id){
    	return  Archivo::findOrFail ( $id );
    	/*return DB::table('archivo')
    	->where('archivo.idarchivo',$id)
    	->get();*/    	
    }
 
    
    	
}