<?php namespace sistema\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use sistema\Models\Candidato;
use sistema\Models\User;
use sistema\Policies\Constantes;
use sistema\Repositories\Eloquent\Repository;
use sistema\Models\Direccion;

class CandidatoRepository extends Repository {

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    private  $campoBase = "nombre";
	
    function model()
    {
        return 'sistema\Models\Candidato';
    }
    
    
    function saveCandidato(Request $request,$password) {
    	DB::beginTransaction ();
    	try {
    	    $direccion = null;
    	    
    	    $direccion = new Direccion();
    	    if($request->get('delMunCandidato') != null && $request->get('delMunCandidato') != '')
    	       $direccion->idmunicipio  = $request->get('delMunCandidato');
    	    $direccion->calle           = trim($request->get('calleCandidato'));
    	    $direccion->no_exterior     = trim($request->get('noExtCandidato'));
    	    $direccion->no_interior     = trim($request->get('noIntCandidato'));
    	    $direccion->colonia         = trim($request->get('coloniaCandidato'));
    	    $direccion->edificio        = trim($request->get('edificioCandidato'));
    	    $direccion->cp              = trim($request->get('cpCandidato'));
    	    $direccion->save();
    	    
    	    
    		 $candidato = new Candidato;
    		 $candidato->nombre                  = trim($request->get('nombreCandidato'));
    		 $candidato->paterno                 = trim($request->get('paternoCandidato'));
    		 $candidato->materno                 = trim($request->get('maternoCandidato'));
    		 $candidato->no_empleado             = trim($request->get('noEmpleadoeCandidato'));
    		 $candidato->genero                  = $request->get('generoCandidato');
    		 $candidato->idcliente               = $request->get('idclienteCandidato');
       		 $candidato->activo                  = true;
       		 $candidato->rfc                     = trim($request->get('rfcCandidato')); 
       		 $candidato->curp                    = trim($request->get('curpCandidato'));
       		 $candidato->tel_cel                 = trim($request->get('celCandidato'));
       		 $candidato->tel_oficina             = trim($request->get('telOficinaCandidato'));
       		 $candidato->ext_tel_oficina         = trim($request->get('extOficinaCandidato'));
       		 
       		 
       		 if($direccion != null)
       		     $candidato->iddireccion         = $direccion->iddireccion;
       		     
       		 
       		          		 
       		 $user = $this->creaUsuarioDeCandidato($request,$password);
       		 $candidato->iduser = $user->id;
       		 $candidato->save();
       		 
       		 
       		 //Bitacora
       		 $controller = $this->obtenerNombreController($request);
       		 $moduloId   = $this->obtenModuloId($controller);
       		 $this->insertaBitacora(Constantes::ACCION_ALTA, null, $candidato->getAttributes(), $candidato->idcandidato,$controller,$moduloId,Session::get('idUser'),array(),$this->campoBase);
       		 
    		DB::commit ();
    	} catch ( \Exception $e ) {
    		DB::rollback ();
    		throw new \Exception('Error al guardar Candidato: ' . $e);
    		 
    	}
    	 return $candidato;
    }
    
    function updateCandidato(Request $request,$id) {
    	DB::beginTransaction ();
    	try {
    	    
    	    $dirComercial = null;
    	    $dirFiscal = null;
    	    
    		 $candidato  = $this->getCandidato($id);
    		 $candidatoA = clone $candidato;
    		 $candidato->nombre_comercial        = trim($request->get('nombreComercialCandidato'));
    		 $candidato->razon_social            = trim($request->get('razonSocialCandidato'));
    		 $candidato->activo                  = true;
    		 $candidato->rfc                     = trim($request->get('rfcCandidato'));
    		 $candidato->puesto_responsable      = trim($request->get('puestoResponsable'));
    		 $candidato->area_responsable        = trim($request->get('areaResponsable'));
    		 $candidato->tel_cel_responsable     = trim($request->get('celResponsable'));
    		 $candidato->tel_oficina_responsable = trim($request->get('telOficinaResponsable'));
    		 $candidato->ext_tel_responsable     = trim($request->get('extOficinaResponsable'));
    		 $candidato->nombre_admon            = trim($request->get('nombreContactoAdmon'));
    		 $candidato->paterno_admon           = trim($request->get('paternoContactoAdmon'));
    		 $candidato->materno_admon           = trim($request->get('maternoContactoAdmon'));
    		 $candidato->tel_cel_admon           = trim($request->get('celContactoAdmon'));
    		 $candidato->email_admon             = trim($request->get('emailContactoAdmon'));
    		 $candidato->tel_oficina_admon       = trim($request->get('telOficinaContactoAdmon'));
    		 $candidato->ext_tel_admon           = trim($request->get('extOficinaContactoAdmon'));
       		 $candidato->update();   
       		 
       		 if($candidato->iddireccion_comercial != null) 
       		     $dirComercial = Direccion::findOrFail($candidato->iddireccion_comercial);
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
       		     
   		     
   		     if($candidato->iddireccion_comercial != null)
   		         $dirFiscal = Direccion::findOrFail($candidato->iddireccion_fiscal);
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
	         
	         $usuario = User::where('idcandidato','=',$candidato->idcandidato)->get()->first();
	         $usuario->name = trim($request->get ('nombreResponsable'));
	         $usuario->paterno = trim($request->get ('paternoResponsable'));
	         $usuario->materno = trim($request->get ('maternoResponsable'));
	         $usuario->save();
       		 //Bitacora
//        		 $controller = $this->obtenerNombreController($request);
//        		 $moduloId   = $this->obtenModuloId($controller);
//        		 $this->insertaBitacora(Constantes::ACCION_ACTUALIZAR, $candidatoA->getAttributes(), $candidato->getAttributes(), $candidato->idcandidato,$controller,$moduloId,Session::get('idUser'),array(),$this->campoBase);
       		 
       		 
    		 DB::commit ();
    	} catch ( \Exception $e ) {
    		DB::rollback ();
    		throw new \Exception('Error al actualizar Candidato: ' . $e);
    	}    
    }
    
    /**
     * Metodo para eliminar una candidato
     * @param  $id
     * @throws \Exception
     */
     function eliminaCandidato($id){
     	DB::beginTransaction ();
     	try {
     		$candidato = $this->getCandidato($id);
     		$candidatoA = clone $candidato;
     		$candidato->activo = 0;
     		$candidato->update();
     		//Bitacora
     		$moduloId   = $this->obtenModuloId(Constantes::CONTROLLER_EMPRESA);
     		$this->insertaBitacora(Constantes::ACCION_ELIMINAR, $candidatoA->getAttributes(), $candidato->getAttributes(), $candidato->idcandidato,Constantes::CONTROLLER_EMPRESA,$moduloId,Session::get('idUser'),array(),$this->campoBase);     		 
     		DB::commit ();
    	} catch ( \Exception $e ) {
    		DB::rollback ();
    		throw new \Exception('Error al actualizar Candidato: ' . $e);
    	}         		
     }
    
     /**
      * Metodo para activar una candidato
      * @param int $id
      * @throws \Exception
      */
    function activaCandidato($id){
    	if((int) $id > 0){
    		DB::beginTransaction ();
    		try{
     			$candidato = $this->getCandidato($id);
     			$candidatoA = clone $candidato;
     			$candidato->activo = 1;
     			$candidato->update();
     			//Bitacora
     			$moduloId   = $this->obtenModuloId(Constantes::CONTROLLER_EMPRESA);
     			$this->insertaBitacora(Constantes::ACCION_ACTIVAR, $candidatoA->getAttributes(), $candidato->getAttributes(), $candidato->idcandidato,Constantes::CONTROLLER_EMPRESA,$moduloId,Session::get('idUser'),array(),$this->campoBase);     			
    			DB::commit ();
    		}
    		catch ( \Exception $e ) {
    			DB::rollback ();
    			throw new \Exception('Error al restablecer a la Candidato con id:'.$id.' -> ' . $e);
    		}
    	}
    }
    
    
    function getPerfiles($idCandidato){
    	if((int)$idCandidato > 0){
    		try{
    			return  DB::table('perfil')->where('idcandidato','=',$idCandidato)->orderBy('nombre')->get();
    		}catch(\Exception $e){
    			throw new \Exception('Error al buscar los perfiles de una candidato '.$e);
    		}
    	}
    	 
    }
    /**
     * Metodo que se ecarga de generar la candidato
     * @param  $request
     * @param string $password
     * @param  $idCandidato
     * @throws \Exception
     */
    function creaUsuarioDeCandidato(Request $request,$password){
    	if ($request) {
    		try{    			 
		    	$usuario = new User ();
		    	$usuario->idperfil = Constantes::PERFIL_ADMIN_CANDIDATO;
		    	$usuario->name 	   = trim($request->get ('nombreCandidato'));
		    	$usuario->paterno = trim($request->get ('paternoCandidato'));
		    	$usuario->materno = trim($request->get ('maternoCandidato'));
		    	$usuario->email    = strtolower(trim($request->get ('emailCandidato')));
		    	$usuario->password = $password;
		    	$usuario->dummy    = true;
		    	$usuario->activo   = true;
		    	$usuario->idcliente =  $request->get('idclienteCandidato');;
		    	$usuario->idempresa = Session::get('idUser');
		    	$usuario->save ();
		    	return $usuario;
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
    function getCandidato($id){
    	return  Candidato::findOrFail ( $id );
    }
    
    
    /**
     * Metodo para validar si el nombre de la candidato ya se encuentra registrado
     * @param String $nombre
     * @param String $idcandidato
     * @return boolean
     */
    function validaNombre($nombre, $idcandidato){
    	$existe = false;
    	$results = DB::table('candidato')->where('nombre','=',$nombre);
    	if($idcandidato != null && $idcandidato != '')
    		$results = $results->where('idcandidato','!=',$idcandidato);
    
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
            throw $e;
        }
        
    }
    
    function getTelefonosCandidato($idCandidato){
        try{
            return  DB::table('telefono_candidato')->where('idcandidato','=',$idCandidato)->orderBy('idtelefono_candidato')->get();
        }catch(\Exception $e){
            throw $e;
        }
        
    }
    
    function getDocumentosCandidato($idCandidato){
        try{
            return  DB::table('archivo_candidato')->where('idcandidato','=',$idCandidato)->orderBy('nombre')->select('archivo_candidato.idarchivo','archivo_candidato.nombre')->get();
        }catch(\Exception $e){
            throw $e;
        }
        
    }
}