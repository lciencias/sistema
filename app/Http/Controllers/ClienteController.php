<?php

namespace sistema\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use sistema\Jobs\EnviaEmailAltaClienteJob;
use sistema\Models\Cliente;
use sistema\Policies\Constantes;
use sistema\Repositories\ClienteRepository;
use sistema\Models\User;
use sistema\Models\Direccion;
use sistema\Models\Municipio;


class ClienteController extends Controller
{
    private $clienteRepository;
    private $controller;
    private $moduloId;
	 
    /**
     * Metodo Constructor de la Clase Clientes
     * @param ClienteRepository $clienteRepository
     */
    public function __construct(ClienteRepository $clienteRepository)
    {
    	parent::__construct();
        $this->clienteRepository = $clienteRepository;
        $this->controller = Constantes::CONTROLLER_CLIENTE;
        $this->moduloId = Constantes::MODULO_ID_CLIENTE;
    }
    
    /**
     * Metodo que muestra el listado de clientes
     * @param Request $request
     */
    public function index(Request $request){
        Session::forget('message-warning');
        if ($request) {
            return view ( 'gestion.cliente.indexCliente', [
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
    	$opciones   = $this->parametros($request, 'idcliente');
    	$view = $this->buscaClientes($request,$opciones,$moduloId);
    	return response()->json(view('gestion.cliente.busquedaCliente',$view)->render());
    }
    

    /**
     * Realiza el llamado para la busqueda de clientes
     * @param Request $request
     * @param $opciones
     * @param $moduloId
     */
    private function buscaClientes(Request $request,$opciones,$moduloId){
    	$session    = Session()->get('permisos');
    	$filtros  	= $this->generaFiltrosClientes($request);
    	$total      = $this->clienteRepository->findByCount($filtros);
    	$clientes = $this->clienteRepository->findByColumn( $filtros,$opciones );
    	return [
    			"idcliente"            => $request->get('idcliente'),
    			"total"      		   => $total,
    			"leyenda"    		   => $this->clienteRepository->generaLetrero($total,count($clientes),$opciones),
    			"nombreComercialClienteBusca"   => $request->get('nombreComercialClienteBusca'),
    	        "razonSocialClienteBusca"   => $request->get('razonSocialClienteBusca'),
    			"rfcClienteBusca"      => $request->get('rfcClienteBusca'),
    			"activoClienteBusca"   => $request->get('activoClienteBusca'),
    			"clientes"        	   => $clientes,
    			"moduloId" 		       => $moduloId,
    			"noPage" 	  		   => $opciones['nopage'],
    			"noRegs" 	   		   => $opciones['noregs'],
    			"isAdmin" 		       => $this->isAdmin,
    			"sessionPermisos"      => $session[$moduloId],
    			"catEstatus"      => array(1 => 'Activo',0=> 'No Activo')
    	];
    }
    
    /**
     * Metodo que genera el filtro para la busqueda de clientes
     * @param Request $request
     * @return multitype:multitype:string
     */
    private function generaFiltrosClientes(Request $request){
    	$filtros   = array ();
    	$idcliente            = $request->get('idcliente');
    	$nombre               = $request->get('nombreComercialClienteBusca');
    	$razonSocial         = $request->get('razonSocialClienteBusca');
    	$rfc                  = $request->get('rfcClienteBusca');
    	$activo               = $request->get('activoClienteBusca');
    
    
    	if(trim($idcliente) != ''){
    		$filtros[] = ['cliente.idcliente', '=', $idcliente];
    	}
    	if(trim($nombre) != ''){
    		$filtros[] = ['cliente.nombre_comercial', 'LIKE', '%' . $nombre . '%'];
    	}
    	if(trim($razonSocial) != ''){
    	    $filtros[] = ['cliente.razon_social', 'LIKE', '%' . $razonSocial . '%'];
    	}
    	if(trim($rfc) != ''){
    		$filtros[] = ['cliente.rfc', 'LIKE', '%' . $rfc . '%'];
    	}
    	if(trim($activo) != ''){
    		$filtros[] = ['cliente.activo', '=', $activo];
    	}
    	return $filtros;
    }
    
    
    /**
     * Metodo para crear una cliente
     */
    public function create()
    {
    	Session()->put('editar',false);
    	$cliente = new Cliente();
    	$direccionFiscal = new Direccion();
    	$direccionComercial = new Direccion();
    	$usuario = new User();
    	$session    = Session()->get('permisos');    	 
    	$estados = $this->clienteRepository->getAllEstados();
        return view("gestion.cliente.createCliente", [
                "sessionPermisos" => $session[$this->moduloId],
        		"cliente" => $cliente,
                "usuario" => $usuario,
                "direccionFiscal" => $direccionFiscal,
                "direccionComercial" => $direccionComercial,
                "municipiosFiscal" => array(),
                "municipiosComercial" => array(),
                "idEstadoComercial" => null,
                "idEstadoFiscal" => null,
                "estados" => $estados,
        		"clienteIdUser" => Session::get ( 'userEnterprise' ),
                "idModulo" => $this->moduloId,
        		"imagen" => null] );
    }
    
    /**
     * Metodo que guarda la información de una cliente
     * @param $request
     */
 	public function store (Request $request)
    {
        try {
        	$passwordO = $this->generaPass();
        	$password  = bcrypt ($passwordO);            	
        	$cliente = $this->clienteRepository->saveCliente( $request ,$password);
//         	if($request->file('image') != null){
// 	        	$cargaImagen = new CargaArchivos($request, $cliente->idcliente);
//     	    }        	
    	    $job = new EnviaEmailAltaClienteJob($cliente, $passwordO);
    	    $this->dispatch($job);
    	    	
	        Session::flash ( 'message', Lang::get ( 'general.success' ) );
        } catch ( \Exception $e ) {
        	$this->log->error ($e);
        	Session::flash ( 'message-error', Lang::get ( 'general.error' ) );
        }
        return Redirect::to ( 'gestion/cliente');

    }
    
    /**
     * Metodo para obtener los datos de una cliente que se edita
     * @param int $id
     */
    public function edit($id)
    {
    	$id =  Crypt::decrypt($id);
    	Session()->put('editar',true);
    	$cliente = $this->clienteRepository->getCliente($id);
    	$usuario = User::where('idcliente','=',$cliente->idcliente)->get()->first();
    	$municipiosFiscal = null;
    	$municipiosComercial = null;
    	$idEstadoComercial = null;
    	$idEstadoFiscal = null;
//     	if(trim($cliente->logotipo) != ''){
//     		$imagen =   $cliente->logotipo;
//     	}
    	
    	if($cliente->direccionFiscal->idmunicipio != null) {
    	    $munFiscal = Municipio::findOrFail($cliente->direccionFiscal->idmunicipio);
    	    $idEstadoFiscal = $munFiscal->idestado;
    	    $municipiosFiscal = $this->clienteRepository->getMunicipiosEstado($idEstadoFiscal);
    	}
    	
    	if($cliente->direccionComercial->idmunicipio != null) {
    	    $munComercial = Municipio::findOrFail($cliente->direccionComercial->idmunicipio);
    	    $idEstadoComercial = $munComercial->idestado;
    	    $municipiosComercial = $this->clienteRepository->getMunicipiosEstado($idEstadoComercial);
    	}
    	
    	
    	$session    = Session()->get('permisos');
    	$estados = $this->clienteRepository->getAllEstados();
    	return view("gestion.cliente.editCliente",[
    	        "sessionPermisos" => $session[$this->moduloId],
    	        "idModulo"=>$this->moduloId,
    	        "estados" => $estados,
    	        "direccionFiscal" => $cliente->direccionFiscal,
    	        "direccionComercial" => $cliente->direccionComercial,
    	        "municipiosFiscal" => $municipiosFiscal,
    	        "municipiosComercial" => $municipiosComercial,
    	        "idEstadoComercial" => $idEstadoComercial,
    	        "idEstadoFiscal" => $idEstadoFiscal,
    			"clienteIdUser" => Session::get ( 'userEnterprise' ),
    			"cliente"=>$cliente,
    	        "usuario"=>$usuario,
    			"imagen" => NULL]);
    }
    
    /**
     * Metodo que actualiza la información de la cliente
     * @param $request
     */
 	public function update(Request $request, $id)
    {    
    	try {
    		if($request){   
//     			if($request->file('image') != null){
//     				$cargaImagen = new CargaArchivos($request, $id);
//     			}
    			$this->clienteRepository->updateCliente( $request ,$id);
    			Session::flash ( 'message', Lang::get ( 'general.success' ) );
    			
    		}
    	} 
		catch (\Exception $e) {
            $this->log->error($e);
			Session::flash ( 'message-error', Lang::get ( 'general.error' ) );
		}
		finally {
			 return Redirect::to('gestion/cliente');
		}
    }
    
    
    
    /**
     * Metodo para activar la cliente
     * @param Request $request
     */
    public function activar(Request $request){
    	$exito = 0;
    	$msg   = Lang::get ( 'general.error' );
    	try{
    		$id = $request->get('idCliente');
    		if((int) $id > 0){
    			$this->clienteRepository->activaCliente($id);
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
     * Para desactivar la cliente
     * @param Request $request
     * @return string
     */
    public function desactivar(Request $request){
    	$exito = 0;
    	$msg   = Lang::get ( 'general.error' );
    	try{
    		$id = $request->get('idCliente');
    		if((int) $id > 0){
    			$this->clienteRepository->eliminaCliente($id);
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
     * Para generar el un password dummy para el usuario representante de la cliente
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
    	if(!$this->clienteRepository->validaMailRepresentante($request->get('email_representante'), $request->get('idusuario'))){
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
    	if(!$this->clienteRepository->validaNombreElemento($request)){
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
                $municipios = $this->clienteRepository->getComportamientosCompetencia($id);
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