<?php namespace sistema\Repositories;
use sistema\Repositories\Eloquent\Repository;


/**
 * Clase de servicio de acceso a datos de perfiles
 * @author Miguel Molina 05/04/2017
 *
 */
class BusquedaRepository extends Repository {
		
    function model(){    	
    	return 'sistema\Models\Empresa';
    }
    
	
    
}