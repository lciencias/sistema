<?php

namespace sistema\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use  Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Logger;
use  Illuminate\Support\Facades\Mail;
use sistema\Models\Modulo;
use  Illuminate\Support\Facades\Session;
use sistema\Repositories\ArchivoRepository;
use sistema\Policies\Constantes;


class ArchivoController extends Controller
{
	private $extensiones;
	private $maximumSize;
	public $message;
	private $archivoRepository;
	private $path;
	private $pathUpload;

	/**
	 * Constructor de la clase Archivo
	 * @param ArchivoRepository $archivoRepository
	 */
	function __construct(ArchivoRepository $archivoRepository){
		parent::__construct();
		$this->archivoRepository = $archivoRepository;
		$this->extensiones  	 = array('jpeg','png','jpg','gif','bmp','pdf');
		$this->maximumSize  	 = 1400000;
		$this->message 			 = array(0 => 'Exito al cargar el archivo',1 => 'Error al cargar el archivo',2 => 'El peso del archivo es excesivo', 3 => 'Archivo no permitido para cargar');
		$this->path 			 = public_path('/images');
		$this->pathUpload		 = public_path('/upload');
	}
    
    /**
     * Metodo para buscar los accesos en los logs
     * @param Request $request
     */
	public function index(Request $request) {
		Session::forget('message-warning');
// 		$archivos   = $this->archivoRepository->getArchivos();


		/*if (count($archivos) == 0){
			Session::flash ( 'message-warning', Lang::get ( 'general.noResults' ) );
		}*/
		return view ( 'catalogos.archivos.index', ["archivos" => $archivos,"path" => Constantes::PATH_WEB]);
	}
	
	
	/**
	 * Metodo que almacena el archivo en filesystem y en base de datos
	 * @param Request $request
	 */
	public function CargaArchivo(Request $request){			
		$file = $request->file('file');
		try{
			if($file != null){
				if($file->getSize() <= $this->maximumSize){
					if(in_array(trim(strtolower($file->getClientOriginalExtension())),$this->extensiones)){
						$this->upload($file);
						$this->store($file);
						Session::flash ( 'message', Lang::get ( 'general.success' ) );
						$this->idSuccess = Constantes::SUCCESS;
					}else{
						Session::flash ( 'message-error', Lang::get ($this->message[Constantes::ERROR_TYPE]) );
					}
				}else{
					Session::flash ( 'message-error', Lang::get ($this->message[Constantes::ERROR_WEIGHT]) );
				}
			}else{
				Session::flash ( 'message-error', Lang::get ($this->message[Constantes::ERROR_UPLOAD]) );
			}
		}catch ( \Exception $e ) {
			$this->log->error ($e);
			Session::flash ( 'message-error', Lang::get ( 'general.error' ) );
		} finally {
			return Redirect::to ( 'catalogos/archivos' );
		}
	}
	
	
	public function edit($id){
		$headers = ['Content-Type: application/pdf'];
		$archivo = $this->archivoRepository->getArchivo($id);
		$array   = $this->decodificaBase64($archivo->contenido, $archivo->nombre);
		if((int)$array['exito'] == 1){
			$filename = "upload/".$array['file'];
			header("Content-Type: application/force-download");
			header("Content-Disposition: attachment; filename=\"$filename\"");			
			readfile($filename);
			@unlink($filename);
		}else{
			Session::flash ( 'message-error', Lang::get ( 'general.error' ) );
		}
		return Redirect::to ( 'catalogos/archivos' );
	}
	
	/**
	 * Metodo que se encarga de subir el archivo al filesystem
	 * @param $file
	 */
	private function upload($file){
		try{
			/*if(file_exists($this->path, $file->getClientOriginalName())){
				@unlink($this->path, $file->getClientOriginalName());
			}*/
			$file->move($this->path, $file->getClientOriginalName());
		}
		catch (\Exception $ex){
			$this->log->error ($e);
			throw new \Exception('Error al cargar el archivo: '.$e);
		}
	}
	
	/**
	 * Metodo que se encarga de alamacenar el archivo en Base de datos
	 * @param  $file
	 */
	
	private function store($file){
		$string = $this->codificaBase64($this->path."/".$file->getClientOriginalName());
		try{
			$this->archivoRepository->saveArchivo($file,$string);
		}
		catch ( \Exception $e ) {
			$this->log->error ($e);
			throw new \Exception('Error al guardar el archivo: '.$e);
		}
	}
	
	
	/**
	 * Metodo que realiza el insert a la base de datos
	 * @param $file_content
	 */
	private function codificaBase64($file_content){
		try{
			$binary = base64_encode(file_get_contents($file_content));
		}catch(\Exception $ex){
			$this->log->error ($e);
			$binary = $ex->getMessage();
		}
		return $binary;
	}
	
	/**
	 * Metodo de decodifica la cadena y la convierte en archivo
	 * @param string codificada
	 * @return string nombre del archivo
	 */
	private function decodificaBase64($file_content,$filename){
		$array = array();
		$array['exito'] = 0;
		try{
			$pdf_decoded = base64_decode ($file_content);
			$f = fopen (Constantes::PATH_SYSTEM_UPLOAD.$filename,'w');
			fwrite ($f,$pdf_decoded);
			fclose ($f);
			$array['exito'] = 1;
			$array['sis'] = Constantes::PATH_SYSTEM_UPLOAD.$filename;
			$array['web'] = Constantes::PATH_WEB_UPLOAD.$filename;
			$array['file'] = $filename;
		}
		catch(\Exception $ex){
			$this->log->error ($e);
		}
		return $array;
	}
}
