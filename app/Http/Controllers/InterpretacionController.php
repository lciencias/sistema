<?php

namespace sistema\Http\Controllers;

use Illuminate\Http\Request;
use  Illuminate\Support\Facades\Session;
use sistema\Repositories\InterpretacionRepository;


class InterpretacionController extends Controller
{
	private $extensiones;
	private $maximumSize;
	public $message;
	private $archivoRepository;
	private $path;
	private $pathUpload;

	/**
	 * Constructor de la clase Archivo
	 * @param InterpretacionRepository $interpretacionRepository
	 */
	function __construct(InterpretacionRepository $interpretacionRepository){
		parent::__construct();
		$this->interpretacionRepository = $interpretacionRepository;
	}
    
    /**
     * Metodo para buscar los accesos en los logs
     * @param Request $request
     */
	public function index(Request $request) {
		Session::forget('message-warning');
		return view ( 'catalogos.archivos.index');
	}
	
	
	public function edit(){
	}
	
	
	/**
	 * Metodo que se encarga de alamacenar el archivo en Base de datos
	 * @param  $file
	 */	
	private function store(){
	}
	
}