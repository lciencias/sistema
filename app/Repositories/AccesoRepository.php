<?php 
namespace sistema\Repositories;

use Illuminate\Support\Facades\DB;
use sistema\Repositories\Eloquent\Repository;


/**
 * Clase de servicio de acceso a datos de perfiles
 * @author Miguel Molina 05/04/2017
 *
 */
class AccesoRepository extends Repository {

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'sistema\Models\Acceso';
    }
        
    /**
     * Metodo que sirve para regresar el objeto de un usuario 
     * @param int $id
     */
    function findAccesos($usuario,$fechaInicial,$fechaFinal){
    	return DB::table('acceso')
    	//->where('users_modulo.iduser',$id)
    	->get();
    }
            	
}