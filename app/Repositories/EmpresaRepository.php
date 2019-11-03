<?php namespace sistema\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use sistema\Http\Requests\EmpresaFormRequest;
use sistema\Models\Empresa;
use sistema\Models\User;
use sistema\Policies\Constantes;
use sistema\Repositories\Eloquent\Repository;

class EmpresaRepository extends Repository {

    /**
     * Specify Model class name
     *
     * @return mixed
     */
	private $campoBase;
	function __construct(){
		$this->campoBase = "nombre";
	}
	
    function model()
    {
        return 'sistema\Models\Empresa';
    }
    
    
    function saveEmpresa(Request $request,$password) {
    	$rfc = "";
    	DB::beginTransaction ();
    	try {
             $accion = "";
//     		 $rfc     = strtoupper(trim($request->get('rfc1').$request->get('rfc2').$request->get('rfc3')));
    		 $email   = strtolower($request->get('emailRepresentanteEmpresa')); 
    		 if($request->get('id') == null) {
                $empresa = new Empresa;
                $accion = 'alta';
    		 } else{
    		     $empresa = Empresa::findOrFail($request->get('id'));
    		     $accion = 'edicion';
    		 }
    		     
       		 $empresa->nombre 				 = $request->get('nombreEmpresa');
       		 $empresa->direccion 			 = $request->get('direccionEmpresa');
       		 $empresa->activo 				 = true;
       		 $empresa->nombre_representante  = $request->get('nombreRepresentanteEmpresa'); 
       		 $empresa->paterno_representante = $request->get('paternoRepresentanteEmpresa');
       		 $empresa->materno_representante = $request->get('maternoRepresentanteEmpresa');
       		 $empresa->email_representante   = $email;
       		 $empresa->rfc                   = $request->get('rfcEmpresa');
       		 $empresa->razon_social			 = $request->get('razonSocialEmpresa');
       		 $empresa->save();        
       		 
       		 if($accion == 'alta')
       		    $this->creaUsuarioDeEmpresa($request,$password,$empresa->idempresa);
       		 
       		 if($request->file('image') != null && trim($request->file('image')->getClientOriginalName()) != ''){
       		 	$empresa->logotipo				 = $empresa->idempresa . "-". $request->file('image')->getClientOriginalName();
       		 }
       		 //Bitacora
       		 $controller = $this->obtenerNombreController($request);
       		 $moduloId   = $this->obtenModuloId($controller);
       		 $this->insertaBitacora(Constantes::ACCION_ALTA, null, $empresa->getAttributes(), $empresa->idempresa,$controller,$moduloId,Session::get('idUser'),array(),$this->campoBase);
       		 
    		DB::commit ();
    	} catch ( \Exception $e ) {
    		DB::rollback ();
    		throw new \Exception('Error al guardar Empresa: ' . $e);
    		 
    	}
    	 return $empresa;
    }
    
    function updateEmpresa(EmpresaFormRequest $request,$id) {
    	$rfc = "";    	 
    	DB::beginTransaction ();
    	try {
    		 if( (trim($request->get('rfc1')) != '') && (trim($request->get('rfc2')) != '') ){
    		 	$rfc     = strtoupper(trim($request->get('rfc1').$request->get('rfc2').$request->get('rfc3')));
    		 }
    		 $email    = strtolower($request->get('email_representante'));    		 
    		 $empresa  = $this->getEmpresa($id);
    		 $empresaA = clone $empresa;
       		 $empresa->nombre 				 = $request->get('nombre');
       		 $empresa->direccion 			 = $request->get('direccion');
       		 $empresa->activo 				 = true;
       		 $empresa->nombre_representante  = $request->get('nombre_representante'); 
       		 $empresa->paterno_representante = $request->get('paterno_representante');
       		 $empresa->materno_representante = $request->get('materno_representante');
       		 $empresa->email_representante   = $email;
       		 $empresa->rfc                   = $rfc;
       		 $empresa->razon_social			 = $request->get('razon_social');
       		 if($request->file('image') != null && trim($request->file('image')->getClientOriginalName()) != ''){
       		 	$empresa->logotipo				 = $empresa->idempresa . "-" . $request->file('image')->getClientOriginalName();
       		 }       		 
       		 $empresa->update();    
       		 //Bitacora
       		 $controller = $this->obtenerNombreController($request);
       		 $moduloId   = $this->obtenModuloId($controller);
       		 $this->insertaBitacora(Constantes::ACCION_ACTUALIZAR, $empresaA->getAttributes(), $empresa->getAttributes(), $empresa->idempresa,$controller,$moduloId,Session::get('idUser'),array(),$this->campoBase);
       		 
       		 
    		 DB::commit ();
    	} catch ( \Exception $e ) {
    		DB::rollback ();
    		throw new \Exception('Error al actualizar Empresa: ' . $e);
    	}    
    }
    
    /**
     * Metodo para eliminar una empresa
     * @param  $id
     * @throws \Exception
     */
     function eliminaEmpresa($id){
     	DB::beginTransaction ();
     	try {
     		$empresa = $this->getEmpresa($id);
     		$empresaA = clone $empresa;
     		$empresa->activo = 0;
     		$empresa->update();
     		//Bitacora
     		$moduloId   = $this->obtenModuloId(Constantes::CONTROLLER_EMPRESA);
     		$this->insertaBitacora(Constantes::ACCION_ELIMINAR, $empresaA->getAttributes(), $empresa->getAttributes(), $empresa->idempresa,Constantes::CONTROLLER_EMPRESA,$moduloId,Session::get('idUser'),array(),$this->campoBase);     		 
     		DB::commit ();
    	} catch ( \Exception $e ) {
    		DB::rollback ();
    		throw new \Exception('Error al actualizar Empresa: ' . $e);
    	}         		
     }
    
     /**
      * Metodo para activar una empresa
      * @param int $id
      * @throws \Exception
      */
    function activaEmpresa($id){
    	if((int) $id > 0){
    		DB::beginTransaction ();
    		try{
     			$empresa = $this->getEmpresa($id);
     			$empresaA = clone $empresa;
     			$empresa->activo = 1;
     			$empresa->update();
     			//Bitacora
     			$moduloId   = $this->obtenModuloId(Constantes::CONTROLLER_EMPRESA);
     			$this->insertaBitacora(Constantes::ACCION_ACTIVAR, $empresaA->getAttributes(), $empresa->getAttributes(), $empresa->idempresa,Constantes::CONTROLLER_EMPRESA,$moduloId,Session::get('idUser'),array(),$this->campoBase);     			
    			DB::commit ();
    		}
    		catch ( \Exception $e ) {
    			DB::rollback ();
    			throw new \Exception('Error al restablecer a la Empresa con id:'.$id.' -> ' . $e);
    		}
    	}
    }
    
    
    function getPerfiles($idEmpresa){
    	if((int)$idEmpresa > 0){
    		try{
    			return  DB::table('perfil')->where('idempresa','=',$idEmpresa)->orderBy('nombre')->get();
    		}catch(\Exception $e){
    			throw new \Exception('Error al buscar los perfiles de una empresa '.$e);
    		}
    	}
    	 
    }
    /**
     * Metodo que se ecarga de generar la empresa
     * @param EmpresaFormRequest $request
     * @param string $password
     * @param  $idEmpresa
     * @throws \Exception
     */
    function creaUsuarioDeEmpresa(Request $request,$password,$idEmpresa){
    	if ($request) {
    		try{    			 
		    	$usuario = new User ();
		    	$usuario->idperfil = Constantes::PERFIL_ADMIN_EMPRESA;
		    	$usuario->name 	   = trim($request->get ('nombreRepresentanteEmpresa'))." ".trim($request->get ('paternoRepresentanteEmpresa'))." ".trim($request->get ('maternoRepresentanteEmpresa'));
		    	$usuario->email    = strtolower(trim($request->get ('emailRepresentanteEmpresa')));
		    	$usuario->password = $password;
		    	$usuario->dummy    = true;
		    	$usuario->activo   = true;
		    	$usuario->idempresa= $idEmpresa;
		    	$usuario->save ();
    		}
	    	catch ( \Exception $e ) {
     			throw new \Exception('Error al crear al Usuario '.$e);
	    	}   
    	}
    }
    
    /**
     * Metodo que regresa un objeto de tu
     * @param int $id
     */
    function getEmpresa($id){
    	return  Empresa::findOrFail ( $id );
    }
    
    
    /**
     * Metodo para validar si el nombre de la empresa ya se encuentra registrado
     * @param String $nombre
     * @param String $idempresa
     * @return boolean
     */
    function validaNombre($nombre, $idempresa){
    	$existe = false;
    	$results = DB::table('empresa')->where('nombre','=',$nombre);
    	if($idempresa != null && $idempresa != '')
    		$results = $results->where('idempresa','!=',$idempresa);
    
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
}