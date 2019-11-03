<?php

namespace sistema\Http\Controllers;

use Illuminate\Container\Container as App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use sistema\Repositories\UsuarioRepository;

class BusquedasController extends Controller
{	
	private $repository;
	private $controller;
	public function __construct(UsuarioRepository $repository){		
		parent::__construct();
		$this->controller = null;
		$this->repository = $repository;
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
    	if((int) $request->get('idModulo') > 0){
	    	try{
	    		$controlador = $this->repository->regresaControlador($request->get('idModulo'));
	    		$this->cargaRepositorio($controlador);
	    		$this->cargaController($controlador, $request);    
	    		if($this->repository != null  && $this->controller != null){
	    			return $this->controller->buscar($request);
	    		}
	    	}
	    	catch ( \Exception $e ) {
	    		$this->log->error($e);
	    		Session::flash ( 'message-error', Lang::get ( 'general.error' ) );
	    		return "";
	    	}
    	}    	
    }
    
    public function cargaRepositorio($controlador){
    	$app = new App();
    	$repository = $controlador->identificador."Repository";
    	$clase      = 'sistema\\Repositories\\'.$repository;
    	$filename   = Session::get('path_sis')."app/Repositories/".$repository.".php";
    	if (file_exists($filename)){
    		try{
    			require_once($filename);
    			$class =  new \ReflectionClass($clase);
    			if ($class->IsInstantiable()){
    				$this->repository = $class->newInstance($app);
    				$this->repository->model();
    			}
    		}
    		catch(\Exception $e){
    			 throw new \Exception($e->getMessage());
    		}
    	}
    }
    public function cargaController($controlador){
    	$arrayMetodos = array();
    	$controller = $controlador->identificador."Controller";
    	$clase      = 'sistema\\Http\\Controllers\\'.$controller;
    	$filename   = Session::get('path_sis')."app/Http/Controllers/".$controller.".php";
    	if (file_exists($filename)){
    		try{
    			require_once($filename);
    			$class =  new \ReflectionClass($clase);
    			if ($class->IsInstantiable()){
    				$metodos=$class->getMethods();
    				foreach($metodos as $met){
    					$arrayMetodos[]= $met->name;
    				}
    				if(in_array('buscar',$arrayMetodos)){
    					$this->controller = $class->newInstance($this->repository);
    				}
    			}
    		}
    		catch(\Exception $e){
    			die($e->getMessage());
    			throw new \Exception($e->getMessage());
    		}
    	}
    	 
    }
}