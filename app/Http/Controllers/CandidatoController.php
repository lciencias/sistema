<?php

namespace sistema\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use sistema\Jobs\EnviaEmailAltaCandidatoJob;
use sistema\Models\Candidato;
use sistema\Models\Direccion;
use sistema\Models\Municipio;
use sistema\Models\User;
use sistema\Policies\Constantes;
use sistema\Repositories\CandidatoRepository;


class CandidatoController extends Controller
{
    private $candidatoRepository;
    private $controller;
    private $moduloId;
    private $clientes;
    private $estados;
	 
    /**
     * Metodo Constructor de la Clase Candidatos
     * @param CandidatoRepository $candidatoRepository
     */
    public function __construct(CandidatoRepository $candidatoRepository)
    {
    	parent::__construct();
        $this->candidatoRepository = $candidatoRepository;
        $this->controller = Constantes::CONTROLLER_CANDIDATO;
        $this->moduloId = Constantes::MODULO_ID_CANDIDATO;
        $this->clientes = $this->candidatoRepository->getClientes(Constantes::ESTATUS_TODOS);
        $this->estados = $this->candidatoRepository->getAllEstados();
    }
    
    /**
     * Metodo que muestra el listado de candidatos
     * @param Request $request
     */
    public function index(Request $request){
        Session::forget('message-warning');
        if ($request) {
            return view ( 'gestion.candidato.indexCandidato', [
                "isAdmin"     => $this->isAdmin,
                "moduloId"    => $this->moduloId,
                "controller"  => $this->controller
            ] );
        }
    }
    
    
    
    /**
     * Envia los resultados de la busqueda a la vista
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscar(Request $request){
    	Session::forget('message-warning');
    	$moduloId   = $request->get('idModulo');
    	$opciones   = $this->parametros($request, 'idcandidato');
    	$view = $this->buscaCandidatos($request,$opciones,$moduloId);
    	return response()->json(view('gestion.candidato.busquedaCandidato',$view)->render());
    }
    

    /**
     * Realiza el llamado para la busqueda de candidatos
     * @param Request $request
     * @param $opciones
     * @param $moduloId
     */
    private function buscaCandidatos(Request $request,$opciones,$moduloId){
    	$session    = Session()->get('permisos');
    	$filtros  	= $this->generaFiltrosCandidatos($request);
    	$total      = $this->candidatoRepository->findByCount($filtros);
    	$candidatos = $this->candidatoRepository->findByColumn( $filtros,$opciones );
    	return [
    			"idcandidato"            => $request->get('idcandidato'),
    			"total"      		   => $total,
    			"leyenda"    		   => $this->candidatoRepository->generaLetrero($total,count($candidatos),$opciones),
    			"nombreCandidatoBusca"   => $request->get('nombreCandidatoBusca'),
    	        "paternoCandidatoBusca"   => $request->get('paternoCandidatoBusca'),
        	    "maternoCandidatoBusca"   => $request->get('maternoCandidatoBusca'),
        	    "noEmpleadoCandidatoBusca"   => $request->get('noEmpleadoCandidatoBusca'),
    			"idclienteCandidatoBusca"      => $request->get('idclienteCandidatoBusca'),
    			"activoCandidatoBusca"   => $request->get('activoCandidatoBusca'),
    			"candidatos"        	   => $candidatos,
    	        "clientes" => $this->clientes,
    			"moduloId" 		       => $moduloId,
    			"noPage" 	  		   => $opciones['nopage'],
    			"noRegs" 	   		   => $opciones['noregs'],
    			"isAdmin" 		       => $this->isAdmin,
    			"sessionPermisos"      => $session[$moduloId],
    			"catEstatus"      => Constantes::CATALOGO_ESTATUS
    	      
    	];
    }
    
    /**
     * Metodo que genera el filtro para la busqueda de candidatos
     * @param Request $request
     * @return multitype:multitype:string
     */
    private function generaFiltrosCandidatos(Request $request){
    	$filtros   = array ();
    	$idcliente = $request->get('idclienteCandidatoBusca');
    	$nombre = $request->get('nombreCandidatoBusca');
    	$paterno = $request->get('paternoCandidatoBusca');
    	$materno = $request->get('maternoCandidatoBusca');
    	$noEmpleado = $request->get('noEmpleadoCandidatoBusca');
    	$activo = $request->get('activoCandidatoBusca');
    
    	if($idcliente != ''){
    	    $filtros[] = ['candidato.idcliente', '=', $idcliente];
    	}
    	if(trim($nombre) != ''){
    	    $filtros[] = ['candidato.nombre', 'LIKE', '%' . $nombre . '%'];
    	}
    	if(trim($paterno) != ''){
    	    $filtros[] = ['candidato.paterno', 'LIKE', '%' . $paterno . '%'];
    	}
    	if(trim($materno) != ''){
    	    $filtros[] = ['candidato.materno', 'LIKE', '%' . $materno . '%'];
    	}
    	if(trim($noEmpleado) != ''){
    	    $filtros[] = ['candidato.materno', 'LIKE', '%' . $noEmpleado . '%'];
    	}
    	if(trim($activo) != ''){
    		$filtros[] = ['candidato.activo', '=', $activo];
    	}
    	return $filtros;
    }
    
    
    /**
     * Metodo para crear una candidato
     */
    public function create()
    {
    	Session()->put('editar',false);
    	$candidato = new Candidato();
    	$direccion = new Direccion();
    	$usuario = new User();
    	$session    = Session()->get('permisos');    	 
        return view("gestion.candidato.createCandidato", [
                "sessionPermisos" => $session[$this->moduloId],
        		"candidato" => $candidato,
                "clientes" => $this->clientes,
                "catEstadoCivil"      => Constantes::CATALOGO_ESTADO_CIVIL,
                "catGenero"      => Constantes::CATALOGO_GENERO,
                "usuario" => $usuario,
                "direccion" => $direccion,
                "municipios" => array(),
                "idEstado" => null,
                "estados" => $this->estados,
                "catTipoSangre"      => Constantes::CATALOGO_TIPO_SANGRE,
                "catParentescos"      => Constantes::CATALOGO_PARENTESCOS,
                "telefonos"      => array(),
                "documentos"      => array(),
        		"candidatoIdUser" => Session::get ( 'userEnterprise' ),
                "idModulo" => $this->moduloId,
        		"imagen" => null] );
    }
    
    /**
     * Metodo que guarda la información de una candidato
     * @param $request
     */
 	public function store (Request $request)
    {
        try {
        	$passwordO = $this->generaPass();
        	$password  = bcrypt ($passwordO);            	
        	$candidato = $this->candidatoRepository->saveCandidato( $request ,$password);
    	    $job = new EnviaEmailAltaCandidatoJob($candidato, $passwordO);
    	    $this->dispatch($job);
    	    	
	        Session::flash ( 'message', Lang::get ( 'general.success' ) );
        } catch ( \Exception $e ) {
        	$this->log->error ($e);
        	Session::flash ( 'message-error', Lang::get ( 'general.error' ) );
        }
        return Redirect::to ( 'gestion/candidato');

    }
    
    /**
     * Metodo para obtener los datos de una candidato que se edita
     * @param int $id
     */
    public function edit($id)
    {
    	$id =  Crypt::decrypt($id);
    	Session()->put('editar',true);
    	$candidato = $this->candidatoRepository->getCandidato($id);
    	$usuario = User::findOrFail($candidato->iduser);
    	$municipios= null;
    	$idEstado = null;
    	
    	if($candidato->direccion->idmunicipio != null) {
    	    $munFiscal = Municipio::findOrFail($candidato->direccion->idmunicipio);
    	    $idEstado = $munFiscal->idestado;
    	    $municipios = $this->candidatoRepository->getMunicipiosEstado($idEstado);
    	}
    	
    	$telefonos =   $this->candidatoRepository->getTelefonosCandidato($id);
    	$documentos =   $this->candidatoRepository->getDocumentosCandidato($id);
    	$session    = Session()->get('permisos');
    	
    	return view("gestion.candidato.editCandidato",[
    	        "sessionPermisos" => $session[$this->moduloId],
    	        "idModulo"=>$this->moduloId,
    	        "estados" => $this->estados,
    	        "direccion" => $candidato->direccion,
    	        "municipios" => $municipios,
    	        "idEstado" => $idEstado,
    	        "clientes" => $this->clientes,
    	        "catEstadoCivil"      => Constantes::CATALOGO_ESTADO_CIVIL,
    	        "catGenero"      => Constantes::CATALOGO_GENERO,
    	       "catTipoSangre"      => Constantes::CATALOGO_TIPO_SANGRE,
    	       "catParentescos"      => Constantes::CATALOGO_PARENTESCOS,
    	        "telefonos"      => $telefonos,
    	       "documentos"      => $documentos,
    			"candidatoIdUser" => Session::get ( 'userEnterprise' ),
    			"candidato"=>$candidato,
    	        "usuario"=>$usuario,
    			"imagen" => NULL]);
    }
    
    /**
     * Metodo que actualiza la información de la candidato
     * @param $request
     */
 	public function update(Request $request, $id)
    {    
    	try {
    		if($request){   
//     			if($request->file('image') != null){
//     				$cargaImagen = new CargaArchivos($request, $id);
//     			}
    			$this->candidatoRepository->updateCandidato( $request ,$id);
    			Session::flash ( 'message', Lang::get ( 'general.success' ) );
    			
    		}
    	} 
		catch (\Exception $e) {
            $this->log->error($e);
			Session::flash ( 'message-error', Lang::get ( 'general.error' ) );
		}
		finally {
			 return Redirect::to('gestion/candidato');
		}
    }
    
    
    
    /**
     * Metodo para activar la candidato
     * @param Request $request
     */
    public function activar(Request $request){
    	$exito = 0;
    	$msg   = Lang::get ( 'general.error' );
    	try{
    		$id = $request->get('idCandidato');
    		if((int) $id > 0){
    			$this->candidatoRepository->activaCandidato($id);
    			$exito = 1;
    			$msg   = Lang::get ( 'general.success' );
    		}
    	}
    	catch (\Exception $e) {
            $this->log->error($e);
    	}
    	finally {
    		return json_encode(array('exito' => $exito,'msg' => $msg));
    	}    
    }
    
    /**
     * Para desactivar la candidato
     * @param Request $request
     * @return string
     */
    public function desactivar(Request $request){
    	$exito = 0;
    	$msg   = Lang::get ( 'general.error' );
    	try{
    		$id = $request->get('idCandidato');
    		if((int) $id > 0){
    			$this->candidatoRepository->eliminaCandidato($id);
    			$exito = 1;
    			$msg   = Lang::get ( 'general.success' );
    		}
    	}
    	catch (\Exception $e) {
            $this->log->error($e);
    	}
    	finally {
    		return json_encode(array('exito' => $exito,'msg' => $msg));
    	}
    }
    
    
    /**
     * Para generar el un password dummy para el usuario representante de la candidato
     * {@inheritDoc}
     * @see \sistema\Http\Controllers\Controller::generaPass()
     */
    public function generaPass() {
    	// Se define una cadena de caractares. Te recomiendo que uses esta.
    	$cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    	// Obtenemos la longitud de la cadena de caracteres
    	$longitudCadena = strlen ( $cadena );
    
    	// Se define la variable que va a contener la contraseña
    	$pass = "";
    	// Se define la longitud de la contraseña, en mi caso 10, pero puedes poner la longitud que quieras
    	$longitudPass = 10;
    
    	// Creamos la contraseña
    	for($i = 1; $i <= $longitudPass; $i ++) {
    		// Definimos numero aleatorio entre 0 y la longitud de la cadena de caracteres-1
    		$pos = rand ( 0, $longitudCadena - 1 );
    
    		// Vamos formando la contraseña en cada iteraccion del bucle, añadiendo a la cadena $pass la letra correspondiente a la posicion $pos en la cadena de caracteres definida.
    		$pass .= substr ( $cadena, $pos, 1 );
    	}
    	return $pass;
    }
    
   
    
    
    /**
     * Valida si ay existe el mail del representante
     * @param Request $request
     */
    public function validaMailRepresentante(Request $request) {
    	$response;
    	if(!$this->candidatoRepository->validaMailRepresentante($request->get('email_representante'), $request->get('idusuario'))){
    		$response = array('valid' => true ,  'message' => '');
    	} else {
    		$response = array('valid' => false,  'message' => Lang::get ( 'leyendas.usuario.mail.existente' ));
    	}
    	print_r(json_encode($response));
    }
    
    
    /**
     * Valida si el nombre de un elemento ya esta registrado es funcion de udo generico
     * @param Request $request
     */
    public function validaNombreElemento(Request $request) {
    	$response;
    	if(!$this->candidatoRepository->validaNombreElemento($request)){
    		$response = array('valid' => true ,  'message' => '');
    	} else {
    		$response = array('valid' => false,  'message' => Lang::get ( 'leyendas.nombreExistente' ));
    	}
    	print_r(json_encode($response));
    }
    
    
    
    
    /**
     * Metodo que busca los municipios asociados a un estado
     * @param Request $request
     */
    public function buscaMunicipios(Request $request){
        $exito = 0;
        $municipios = array();
        try{
            $id = $request->get('idEstado');
            if((int) $id > 0){
                $municipios = $this->candidatoRepository->getComportamientosCompetencia($id);
                $exito = 1;
            }
        }
        catch (\Exception $e) {
            $this->log->error($e->getMessage()."<br>".$e->getMessage());
        }
        finally {
            return json_encode(array('exito' => $exito,'municipios' => $municipios));
        }
        
    }
    
    
}