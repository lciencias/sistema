<?php

namespace sistema\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use sistema\Http\Requests\EmpresaFormRequest;
use sistema\Models\Empresa;
use sistema\Policies\Constantes;
use sistema\Repositories\EmpresaRepository;
use Illuminate\Support\Facades\Session;
use sistema\Jobs\EnviaEmailAltaEmpresaJob;
use Illuminate\Support\Facades\Crypt;


class EmpresaController extends Controller
{
    private $empresaRepository;
	 
    /**
     * Metodo Constructor de la Clase Empresas
     * @param EmpresaRepository $empresaRepository
     */
    public function __construct(EmpresaRepository $empresaRepository)
    {
    	parent::__construct();
        $this->empresaRepository = $empresaRepository;
    }
    
    /**
     * Metodo que muestra el listado de empresas
     * @param Request $request
     */
    public function index(Request $request){
        Session::forget('message-warning');
        if ($request) {
            $controller = $this->empresaRepository->obtenerNombreController($request);
            $moduloId   = $this->empresaRepository->obtenModuloId($controller);
            return view ( 'seguridad.empresa.indexEmpresa', [
                "isAdmin"     => $this->isAdmin,
                "moduloId"    => $moduloId,
                "controller"  => $controller
            ] );
        }
    }
    
//     public function parametros(Request $request){
//     	$noPage = Constantes::NOPAGINA;
//     	$noRegs = Constantes::getPaginator();
//     	$orden  = Constantes::ORDEN;
//     	$campo  = "idempresa";
//     	if($request){
//     		if( (int) $request->get('noRegs') > 0){
//     			$noRegs = $request->get('noRegs');
//     		}
//     		if( (int) $request->get('page') > 0){
//     			$noPage = $request->get('page');
//     		}
//     		if( trim($request->get('orden')) != ''){
//     			$orden = $request->get('orden');
//     		}
//     	}
//     	return array('campo' => $campo,'orden' => $orden, 'nopage' => $noPage, 'noregs' => $noRegs);
//     }
    
    
    /**
     * Envia los resultados de la busqueda a la vista
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscar(Request $request){
    	Session::forget('message-warning');
    	$moduloId   = $request->get('idModulo');
    	$opciones   = $this->parametros($request, 'idEmpresa');
    	$view = $this->buscaEmpresas($request,$opciones,$moduloId);
    	return response()->json(view('seguridad.empresa.busquedaEmpresa',$view)->render());
    }
    

    /**
     * Realiza el llamado para la busqueda de empresas
     * @param Request $request
     * @param $opciones
     * @param $moduloId
     */
    private function buscaEmpresas(Request $request,$opciones,$moduloId){
    	$session    = Session()->get('permisos');
    	$filtros  	= $this->generaFiltrosEmpresas($request);
    	$total      = $this->findByCount($filtros);
    	$empresas = $this->findByColumn( $filtros,$opciones );
    	$empresa = new Empresa();
    	return [
    			"idempresa"            => $request->get('idempresa'),
    			"total"      		   => $total,
    			"leyenda"    		   => $this->empresaRepository->generaLetrero($total,count($empresas),$opciones),
    			"nombreEmpresaBusca"               => $request->get('nombreEmpresaBusca'),
    			"rfcEmpresaBusca"                  => $request->get('rfcEmpresaBusca'),
    			"nombreRepresentanteBusca" => $request->get('nombreRepresentanteBusca'),
    			"emailRepresentanteBusca"  => $request->get('emailRepresentanteBusca'),
    			"activo"               => $request->get('activo'),
    			"empresas"        	   => $empresas,
    			"moduloId" 		       => $moduloId,
    	    "idModulo" 		       => $moduloId,
    	    "empresa" 		       => $empresa,
    	    "empresaIdUser" => Session::get ( 'userEnterprise' ),
    	    "imagen" => '',
    			"noPage" 	  		   => $opciones['nopage'],
    			"noRegs" 	   		   => $opciones['noregs'],
    			"isAdmin" 		       => $this->isAdmin,
    			"sessionPermisos"      => $session[$moduloId],
    			"catEstatus"      => array(1 => 'Activo',0=> 'No Activo')
    	];
    }
    
    /**
     * Metodo que genera el filtro para la busqueda de empresas
     * @param Request $request
     * @return multitype:multitype:string
     */
    private function generaFiltrosEmpresas(Request $request){
    	$filtros   = array ();
    	$idempresa            = $request->get('idempresa');
    	$nombre               = $request->get('nombreEmpresaBusca');
    	$rfc                  = $request->get('rfcEmpresaBusca');
    	$nombre_representante = $request->get('nombreRepresentanteBusca');
    	$email_representante  = $request->get('emailRepresentanteBusca');
    	$activo               = $request->get('activo');
    
    
    	if(trim($idempresa) != ''){
    		$filtros[] = ['empresa.idempresa', '=', $idempresa];
    	}
    	if(trim($nombre) != ''){
    		$filtros[] = ['empresa.nombre', 'LIKE', '%' . $nombre . '%'];
    	}
    	if(trim($rfc) != ''){
    		$filtros[] = ['empresa.rfc', 'LIKE', '%' . $rfc . '%'];
    	}
    	if(trim($nombre_representante) != ''){
    		$filtros[] = ['empresa.nombre_representante', 'LIKE', '%' . $nombre_representante . '%'];
    	}
    	if(trim($email_representante) != ''){
    		$filtros[] = ['empresa.email_representante', 'LIKE', '%' . $email_representante . '%'];
    	}
    	if(trim($activo) != ''){
    		$filtros[] = ['empresa.activo', '=', $activo];
    	}
    	return $filtros;
    }
    
    
    /**
     * Metodo para crear una empresa
     */
    public function create()
    {
    	Session()->put('editar',false);
    	$empresa = new Empresa();
    	$empresa->nombre = '';
    	$empresa->descripcion = '';
    	$rfc = $imagen = '';    	
    	$moduloId   = $this->empresaRepository->obtenModuloId(Constantes::CONTROLLER_EMPRESA);
    	$session    = Session()->get('permisos');    	 
        return view("seguridad.empresa.createEmpresa", [
        		"sessionPermisos" => $session[$moduloId],
        		"empresa" => $empresa,
        		"rfc" => $rfc,
        		"empresaIdUser" => Session::get ( 'userEnterprise' ),
        		"idModulo" => $moduloId,
        		"imagen" => $imagen] );
    }
    
    /**
     * Metodo que guarda la información de una empresa
     * @param EmpresaFormRequest $request
     */
 	public function store (EmpresaFormRequest $request)
    {
        try {
        	if(!$this->empresaRepository->validaEmail($request->get('email_representante'))){
        		$passwordO = $this->generaPass();
        		$password  = bcrypt ($passwordO);            	
        		$empresa = $this->empresaRepository->saveEmpresa( $request ,$password);
        		if($request->file('image') != null){
	        		$cargaImagen = new CargaArchivos($request, $empresa->idempresa);
    	    	}        	
    	    	$job = new EnviaEmailAltaEmpresaJob($empresa, $passwordO);
    	    	$this->dispatch($job);
    	    	
	        	Session::flash ( 'message', Lang::get ( 'general.success' ) );
        	}else{
        		Session::flash ( 'message-error', Lang::get ( 'El elemento email ya está en uso.' ) );
        	}
        } catch ( \Exception $e ) {
        	$this->log->error ($e);
        	Session::flash ( 'message-error', Lang::get ( 'general.error' ) );
        }
        return Redirect::to ( 'seguridad/empresa');

    }
    
    public function storeAjax (Request $request)
    {
        try {
            $this->log->debug($request->get('nombreEmpresa'));
//             if(!$this->empresaRepository->validaEmail($request->get('emailRepresentanteEmpresa'))){
                $passwordO = $this->generaPass();
                $password  = bcrypt ($passwordO);
                $empresa = $this->empresaRepository->saveEmpresa( $request ,$password);
                if($request->file('image') != null){
                    $cargaImagen = new CargaArchivos($request, $empresa->idempresa);
                }
                $job = new EnviaEmailAltaEmpresaJob($empresa, $passwordO);
                $this->dispatch($job);
                
                $msg   =  Lang::get ( 'general.success' );
//             }else{
//                 $exito = 0;
//                 $msg   =  'El elemento email ya está en uso.';
//             }
            $exito = 1;
        } catch ( \Exception $e ) {
            $this->log->error ($e);
            $exito = 0;
            $msg   =  Lang::get ( 'general.error' );
        }
        return json_encode(array('exito' => $exito,'msg' => $msg));
        
    }
    
    /**
     * Metodo para obtener los datos de una empresa que se edita
     * @param int $id
     */
    public function edit($id)
    {
        $this->log->debug($request->get('idEmpresa'));
        $id =  Crypt::decrypt($request->get('idEmpresa'));
        $this->log->debug($id);
    	Session()->put('editar',true);
    	$rfc = $imagen = '';
    	$empresa = $this->empresaRepository->getEmpresa($id);
    	if(trim($empresa->rfc) != ''){
    		$rfc = $empresa->rfc;
    	}
    	
    	if(trim($empresa->logotipo) != ''){
    		$imagen =   $empresa->logotipo;
    	}
    	
    	$moduloId   = $this->empresaRepository->obtenModuloId(Constantes::CONTROLLER_EMPRESA);
    	$session    = Session()->get('permisos');
    	return view("seguridad.empresa.editEmpresa",[
    			"sessionPermisos" => $session[$moduloId],
    			"idModulo"=>$moduloId,
    			"empresaIdUser" => Session::get ( 'userEnterprise' ),
    			"empresa"=>$empresa,
    			"rfc" => $rfc,
    			"imagen" => $imagen]);
    }
    
    public function editAjax(Request $request)
    {
        $empresa = new Empresa();
        try {
            $this->log->debug($request->get('idEmpresa'));
            $id =  Crypt::decrypt($request->get('idEmpresa'));
            $this->log->debug($id);
            
            $empresa = $this->empresaRepository->getEmpresa($id);
                
            $msg   =  Lang::get ( 'general.success' );
            $exito = 1;
        } catch ( \Exception $e ) {
            $this->log->error ($e);
            $exito = 0;
            $msg   =  Lang::get ( 'general.error' );
        }
        return json_encode(array('exito' => $exito,'msg' => $msg, 'empresa' => $empresa));
        
    }
    
    /**
     * Metodo que actualiza la información de la empresa
     * @param EmpresaFormRequest $request
     */
 	public function update(EmpresaFormRequest $request, $id)
    {    
    	try {
    	    //son cambios para pruebas 
    		if($request){   
    			if($request->file('image') != null){
    				$cargaImagen = new CargaArchivos($request, $id);
    			}
    			$this->empresaRepository->updateEmpresa( $request ,$id);
    			Session::flash ( 'message', Lang::get ( 'general.success' ) );
    			
    		}
    	} 
		catch (\Exception $e) {
            $this->log->error($e);
			Session::flash ( 'message-error', Lang::get ( 'general.error' ) );
		}
		finally {
			 return Redirect::to('seguridad/empresa');
		}
    }
    
    
    
    /**
     * Metodo para activar la empresa
     * @param Request $request
     */
    public function activar(Request $request){
    	$exito = 0;
    	$msg   = Lang::get ( 'general.error' );
    	try{
    		$id = $request->get('idEmpresa');
    		if((int) $id > 0){
    			$this->empresaRepository->activaEmpresa($id);
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
     * Para desactivar la empresa
     * @param Request $request
     * @return string
     */
    public function desactivar(Request $request){
    	$exito = 0;
    	$msg   = Lang::get ( 'general.error' );
    	try{
    		$id = $request->get('idEmpresa');
    		if((int) $id > 0){
    			$this->empresaRepository->eliminaEmpresa($id);
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
     * Para generar el un password dummy para el usuario representante de la empresa
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
     * Metodo para enviar por correo las claves de acceso
     * @param array $request
     */
    private function enviaAcceso($request,$passwordO){
    	$data = array('name' =>$request->nombre_representante, 'password'=>$passwordO, 'email' => $request->email_representante);
    	$template = 'seguridad.empresa.emailTemplates.nuevo';
    	$titulo   = 'Alta de Empresa.';
    	Mail::send($template, $data, function($message) use ($request) {
    		$message->to($request->email_representante, $request->nombre_representante)->subject('Ventas');
    		$message->from('enviomails.test2017@gmail.com','Administrador');
    	});
    }
    
    
    
    /**
     * Valida si ay existe el mail del representante
     * @param Request $request
     */
    public function validaMailRepresentante(Request $request) {
    	$response;
    	if(!$this->empresaRepository->validaMailRepresentante($request->get('email_representante'), $request->get('idusuario'))){
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
    	if(!$this->empresaRepository->validaNombreElemento($request)){
    		$response = array('valid' => true ,  'message' => '');
    	} else {
    		$response = array('valid' => false,  'message' => Lang::get ( 'leyendas.nombreExistente' ));
    	}
//     	print_r(json_encode($response));
    	return json_encode($response);
    }
    
    
    public function findByCount($array){
    	$result = Empresa::where('idempresa','>=','1');
    	if(count($array) > 0){
    		foreach($array as $tmp){
    			if(trim($tmp[1]) == 'IN'){
    				$result = $result->whereIn($tmp[0],$tmp[2]);
    			}else{
    				$result = $result->where($tmp[0], $tmp[1], $tmp[2]);
    			}
    		}
    	}
    	$result = $result->get()->count();
    	return $result;
    
    }
    
    
    public function findByColumn($array,$opciones){
    	$result = Empresa::where('idempresa','>=','1');
    	if(count($array) > 0){
    		foreach($array as $tmp){
    			if(trim($tmp[1]) == 'IN'){
    				$result = $result->whereIn($tmp[0],$tmp[2]);
    			}else{
    				$result = $result->where($tmp[0], $tmp[1], $tmp[2]);
    			}
    		}
    	}
    	$result = $result->orderBy($opciones['campo'],$opciones['orden'])->paginate ($opciones['noregs']);
    	return $result;
    }
    
    
    /**
     * Metodo que busca los perfiles asociados a una empresa
     * @param Request $request
     */
    public function buscaPerfiles(Request $request){
    	$exito = 0;
    	$perfiles = array();
    	try{
    		$id = $request->get('idEmpresa');
    		if((int) $id > 0){
    			$perfiles = $this->empresaRepository->getPerfiles($id);
    			$exito = 1;
    		}
    	}
    	catch (\Exception $e) {
    		$this->log->error ($e);
    	}
    	finally {
    		return json_encode(array('exito' => $exito,'perfiles' => $perfiles));
    	}
    
    }
    
    
}