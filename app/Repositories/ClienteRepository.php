<?php namespace sistema\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use sistema\Models\Cliente;
use sistema\Models\User;
use sistema\Policies\Constantes;
use sistema\Repositories\Eloquent\Repository;
use sistema\Models\Direccion;

class ClienteRepository extends Repository {

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    private  $campoBase = "nombre";
	
    function model()
    {
        return 'sistema\Models\Cliente';
    }
    
    
    function saveCliente(Request $request,$password) {
    	DB::beginTransaction ();
    	try {
    	    $dirComercial = null;
    	    $dirFiscal = null;
    	    
    	    $dirComercial = new Direccion();
    	    if($request->get('delMunComercial') != null && $request->get('delMunComercial') != '')
    	       $dirComercial->idmunicipio  = $request->get('delMunComercial');
    	    $dirComercial->calle           = trim($request->get('calleComercial'));
    	    $dirComercial->no_exterior     = trim($request->get('noExtComercial'));
    	    $dirComercial->no_interior     = trim($request->get('noIntComercial'));
    	    $dirComercial->colonia         = trim($request->get('coloniaComercial'));
    	    $dirComercial->edificio        = trim($request->get('edificioComercial'));
    	    $dirComercial->cp              = trim($request->get('cpComercial'));
    	    $dirComercial->save();
    	    
    	    $dirFiscal = new Direccion();
    	    if($request->get('delMunFiscal') != null && $request->get('delMunFiscal') != '')
    	        $dirFiscal->idmunicipio = $request->get('delMunFiscal');
    	    $dirFiscal->calle           = trim($request->get('calleFiscal'));
    	    $dirFiscal->no_exterior     = trim($request->get('noExtFiscal'));
    	    $dirFiscal->no_interior     = trim($request->get('noIntFiscal'));
    	    $dirFiscal->colonia         = trim($request->get('coloniaFiscal'));
    	    $dirFiscal->edificio        = trim($request->get('edificioFiscal'));
    	    $dirFiscal->cp              = trim($request->get('cpFiscal'));
    	    $dirFiscal->save();
    	    
    		 $cliente = new Cliente;
    		 $cliente->nombre_comercial        = trim($request->get('nombreComercialCliente'));
    		 $cliente->razon_social            = trim($request->get('razonSocialCliente'));
       		 $cliente->activo                  = true;
       		 $cliente->rfc                     = trim($request->get('rfcCliente')); 
       		 $cliente->puesto_responsable      = trim($request->get('puestoResponsable'));
       		 $cliente->area_responsable        = trim($request->get('areaResponsable'));
       		 $cliente->tel_cel_responsable     = trim($request->get('celResponsable'));
       		 $cliente->tel_oficina_responsable = trim($request->get('telOficinaResponsable'));
       		 $cliente->ext_tel_responsable     = trim($request->get('extOficinaResponsable'));
       		 $cliente->nombre_admon            = trim($request->get('nombreContactoAdmon'));
       		 $cliente->paterno_admon           = trim($request->get('paternoContactoAdmon'));
       		 $cliente->materno_admon           = trim($request->get('maternoContactoAdmon'));
       		 $cliente->tel_cel_admon           = trim($request->get('celContactoAdmon'));
       		 $cliente->email_admon             = trim($request->get('emailContactoAdmon'));
       		 $cliente->tel_oficina_admon       = trim($request->get('telOficinaContactoAdmon'));
       		 $cliente->ext_tel_admon           = trim($request->get('extOficinaContactoAdmon'));
       		 
       		 if($dirComercial != null)
       		     $cliente->iddireccion_comercial = $dirComercial->iddireccion;
       		     
       		 if($dirFiscal != null)
       		     $cliente->iddireccion_fiscal = $dirFiscal->iddireccion;
       		 
       		 $cliente->save();         		 
       		 $this->creaUsuarioDeCliente($request,$password,$cliente->idcliente);
       		 
//        		 if($request->file('image') != null && trim($request->file('image')->getClientOriginalName()) != ''){
//        		 	$cliente->logotipo				 = $cliente->idcliente . "-". $request->file('image')->getClientOriginalName();
//        		 }
       		 
       		 
       		 
       		 //Bitacora
       		 $controller = $this->obtenerNombreController($request);
       		 $moduloId   = $this->obtenModuloId($controller);
       		 $this->insertaBitacora(Constantes::ACCION_ALTA, null, $cliente->getAttributes(), $cliente->idcliente,$controller,$moduloId,Session::get('idUser'),array(),$this->campoBase);
       		 
    		DB::commit ();
    	} catch ( \Exception $e ) {
    		DB::rollback ();
    		throw new \Exception('Error al guardar Cliente: ' . $e);
    		 
    	}
    	 return $cliente;
    }
    
    function updateCliente(Request $request,$id) {
    	DB::beginTransaction ();
    	try {
    	    
    	    $dirComercial = null;
    	    $dirFiscal = null;
    	    
    		 $cliente  = $this->getCliente($id);
    		 $clienteA = clone $cliente;
    		 $cliente->nombre_comercial        = trim($request->get('nombreComercialCliente'));
    		 $cliente->razon_social            = trim($request->get('razonSocialCliente'));
    		 $cliente->activo                  = true;
    		 $cliente->rfc                     = trim($request->get('rfcCliente'));
    		 $cliente->puesto_responsable      = trim($request->get('puestoResponsable'));
    		 $cliente->area_responsable        = trim($request->get('areaResponsable'));
    		 $cliente->tel_cel_responsable     = trim($request->get('celResponsable'));
    		 $cliente->tel_oficina_responsable = trim($request->get('telOficinaResponsable'));
    		 $cliente->ext_tel_responsable     = trim($request->get('extOficinaResponsable'));
    		 $cliente->nombre_admon            = trim($request->get('nombreContactoAdmon'));
    		 $cliente->paterno_admon           = trim($request->get('paternoContactoAdmon'));
    		 $cliente->materno_admon           = trim($request->get('maternoContactoAdmon'));
    		 $cliente->tel_cel_admon           = trim($request->get('celContactoAdmon'));
    		 $cliente->email_admon             = trim($request->get('emailContactoAdmon'));
    		 $cliente->tel_oficina_admon       = trim($request->get('telOficinaContactoAdmon'));
    		 $cliente->ext_tel_admon           = trim($request->get('extOficinaContactoAdmon'));
       		 $cliente->update();   
       		 
       		 if($cliente->iddireccion_comercial != null) 
       		     $dirComercial = Direccion::findOrFail($cliente->iddireccion_comercial);
       		 else 
       		     $dirComercial = new Direccion();
       		 
       		
       		 if($request->get('delMunComercial') != null && $request->get('delMunComercial') != '')
       		     $dirComercial->idmunicipio  = $request->get('delMunComercial');
   		     $dirComercial->calle           = trim($request->get('calleComercial'));
   		     $dirComercial->no_exterior     = trim($request->get('noExtComercial'));
   		     $dirComercial->no_interior     = trim($request->get('noIntComercial'));
   		     $dirComercial->colonia         = trim($request->get('coloniaComercial'));
   		     $dirComercial->edificio        = trim($request->get('edificioComercial'));
   		     $dirComercial->cp              = trim($request->get('cpComercial'));
   		     $dirComercial->save();
       		     
   		     
   		     if($cliente->iddireccion_comercial != null)
   		         $dirFiscal = Direccion::findOrFail($cliente->iddireccion_fiscal);
	         else
	             $dirFiscal = new Direccion();
   		         
   		     if($request->get('delMunFiscal') != null && $request->get('delMunFiscal') != '')
   		         $dirFiscal->idmunicipio = $request->get('delMunFiscal');
	         $dirFiscal->calle           = trim($request->get('calleFiscal'));
	         $dirFiscal->no_exterior     = trim($request->get('noExtFiscal'));
	         $dirFiscal->no_interior     = trim($request->get('noIntFiscal'));
	         $dirFiscal->colonia         = trim($request->get('coloniaFiscal'));
	         $dirFiscal->edificio        = trim($request->get('edificioFiscal'));
	         $dirFiscal->cp              = trim($request->get('cpFiscal'));
	         $dirFiscal->save();
	         
	         $usuario = User::where('idcliente','=',$cliente->idcliente)->get()->first();
	         $usuario->name = trim($request->get ('nombreResponsable'));
	         $usuario->paterno = trim($request->get ('paternoResponsable'));
	         $usuario->materno = trim($request->get ('maternoResponsable'));
	         $usuario->save();
       		 //Bitacora
//        		 $controller = $this->obtenerNombreController($request);
//        		 $moduloId   = $this->obtenModuloId($controller);
//        		 $this->insertaBitacora(Constantes::ACCION_ACTUALIZAR, $clienteA->getAttributes(), $cliente->getAttributes(), $cliente->idcliente,$controller,$moduloId,Session::get('idUser'),array(),$this->campoBase);
       		 
       		 
    		 DB::commit ();
    	} catch ( \Exception $e ) {
    		DB::rollback ();
    		throw new \Exception('Error al actualizar Cliente: ' . $e);
    	}    
    }
    
    /**
     * Metodo para eliminar una cliente
     * @param  $id
     * @throws \Exception
     */
     function eliminaCliente($id){
     	DB::beginTransaction ();
     	try {
     		$cliente = $this->getCliente($id);
     		$clienteA = clone $cliente;
     		$cliente->activo = 0;
     		$cliente->update();
     		//Bitacora
     		$moduloId   = $this->obtenModuloId(Constantes::CONTROLLER_EMPRESA);
     		$this->insertaBitacora(Constantes::ACCION_ELIMINAR, $clienteA->getAttributes(), $cliente->getAttributes(), $cliente->idcliente,Constantes::CONTROLLER_EMPRESA,$moduloId,Session::get('idUser'),array(),$this->campoBase);     		 
     		DB::commit ();
    	} catch ( \Exception $e ) {
    		DB::rollback ();
    		throw new \Exception('Error al actualizar Cliente: ' . $e);
    	}         		
     }
    
     /**
      * Metodo para activar una cliente
      * @param int $id
      * @throws \Exception
      */
    function activaCliente($id){
    	if((int) $id > 0){
    		DB::beginTransaction ();
    		try{
     			$cliente = $this->getCliente($id);
     			$clienteA = clone $cliente;
     			$cliente->activo = 1;
     			$cliente->update();
     			//Bitacora
     			$moduloId   = $this->obtenModuloId(Constantes::CONTROLLER_EMPRESA);
     			$this->insertaBitacora(Constantes::ACCION_ACTIVAR, $clienteA->getAttributes(), $cliente->getAttributes(), $cliente->idcliente,Constantes::CONTROLLER_EMPRESA,$moduloId,Session::get('idUser'),array(),$this->campoBase);     			
    			DB::commit ();
    		}
    		catch ( \Exception $e ) {
    			DB::rollback ();
    			throw new \Exception('Error al restablecer a la Cliente con id:'.$id.' -> ' . $e);
    		}
    	}
    }
    
    
    function getPerfiles($idCliente){
    	if((int)$idCliente > 0){
    		try{
    			return  DB::table('perfil')->where('idcliente','=',$idCliente)->orderBy('nombre')->get();
    		}catch(\Exception $e){
    			throw new \Exception('Error al buscar los perfiles de una cliente '.$e);
    		}
    	}
    	 
    }
    /**
     * Metodo que se ecarga de generar la cliente
     * @param  $request
     * @param string $password
     * @param  $idCliente
     * @throws \Exception
     */
    function creaUsuarioDeCliente(Request $request,$password,$idCliente){
    	if ($request) {
    		try{    			 
		    	$usuario = new User ();
		    	$usuario->idperfil = Constantes::PERFIL_ADMIN_CLIENTE;
		    	$usuario->name 	   = trim($request->get ('nombreResponsable'));
		    	$usuario->paterno = trim($request->get ('paternoResponsable'));
		    	$usuario->materno = trim($request->get ('maternoResponsable'));
		    	$usuario->email    = strtolower(trim($request->get ('emailResponsable')));
		    	$usuario->password = $password;
		    	$usuario->dummy    = true;
		    	$usuario->activo   = true;
		    	$usuario->idcliente = $idCliente;
		    	$usuario->idempresa = Session::get('idUser');
		    	$usuario->save ();
    		}
	    	catch ( \Exception $e ) {
     			throw $e;
	    	}   
    	}
    }
    
    /**
     * Metodo que regresa un objeto de tu
     * @param int $id
     */
    function getCliente($id){
    	return  Cliente::findOrFail ( $id );
    }
    
    
    /**
     * Metodo para validar si el nombre de la cliente ya se encuentra registrado
     * @param String $nombre
     * @param String $idcliente
     * @return boolean
     */
    function validaNombre($nombre, $idcliente){
    	$existe = false;
    	$results = DB::table('cliente')->where('nombre','=',$nombre);
    	if($idcliente != null && $idcliente != '')
    		$results = $results->where('idcliente','!=',$idcliente);
    
    	$results = $results->get();
    	if(count($results) > 0){
    		$existe = true;
    	}
    	return $existe;
    }
    
    

    /**
     * Metodo para validar si el mail del usuario representante ya se encuentra registrado
     * @param String $mail
     * @param String $idusuario
     * @return boolean
     */
    function validaMailRepresentante($mail, $idusuario){
    	$existe = false;
    	$results = DB::table('users')->where('email','=',$mail);
    	if($idusuario != null && $idusuario != '' && $idusuario != '0')
    		$results = $results->where('id','!=',$idusuario);
    
    		$results = $results->get();
    		if(count($results) > 0){
    			$existe = true;
    		}
    		return $existe;
    }
    
    
    function getMunicipiosEstado($idEstado){
        try{
            return  DB::table('municipio')->where('idestado','=',$idEstado)->orderBy('nombre')->get();
        }catch(\Exception $e){
            throw new \Exception('Error al buscar los municipios de un estado '.$e);
        }
        
    }
}