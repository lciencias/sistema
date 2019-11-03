<?php

namespace sistema\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Lang;
use Illuminate\Http\Request;
use sistema\Policies\Constantes;
use Maatwebsite\Excel\Facades\Excel;
use Logger;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public $resultsExcel;
    public $isAdmin;
    public $idEmpresaUser;
    public $idRol;
    public $message;
    public $results;
    private $extensiones;
    private $extensionesRenovacion;
    private $maximumSize;
    public $log;
    public $idSucursal;

    public function __construct() {
    	//Se configura el uso de log4php
    	Logger::configure ( './xml/config.xml' );
    	$this->log = \Logger::getLogger ( 'main' );
    	
        $carbon = new \Carbon\Carbon();
        Session()->put('fechaAcceso',$carbon->now());
        $this->guardaRuta();       
        $this->middleware ( 'auth' );
        if($_SERVER['REDIRECT_URL'] != '/sistema/public/auth/reset/reset' && $_SERVER['REDIRECT_URL'] != '/sistema/public/auth/reset/update'){
        	$this->middleware ( 'dummy' );
        }
        $this->message       = "";
        $this->idRol         = Session::get ('userIdPerfil');      
        $this->idEmpresaUser = Session::get ( 'userEnterprise' );
        $this->isAdmin       = Session::get ('isAdmin');
        $this->resultsExcel  = array();
        $this->extensiones   = array('xls','xls');
        $this->extensionesRenovacion = array('pdf','png','jpg','jpeg','gif','bmp');
        $this->maximumSize   = 1400000;
        
        if(Session::get ( 'idSucursal' ) == ""  || Session::get ( 'idSucursal' ) == null) {
        	$this->idSucursal = 0;
        } else {
        	$this->idSucursal = Session::get ( 'idSucursal' );
        }
    }
    
    
    

    public function cargaArchivo($file,$filename){
    	try{    		
    		$this->eliminaArchivo($filename);
    		$file->move('../public/upload/', $filename);
    	}
    	catch (\Exception $e){
    		$this->log->error($e);
    		throw new \Exception('Error al cargar el archivo: '.$e);
    	}
    }
    
    public function recuperaInformacionExcel($filename){
    	$this->results = $tmp = array();
    	$contador= 0;
    	try{
    		Excel::load(Constantes::PATH_SYSTEM_UPLOAD.$filename, function($reader) use (&$excel,$contador) {
    			$objExcel      = $reader->getExcel();
    			$sheet         = $objExcel->getSheet(0);
    			$highestRow    = $sheet->getHighestRow();
    			$highestColumn = $sheet->getHighestColumn();
    			$header = $sheet->rangeToArray('A2:'.$highestColumn.'2',NULL, TRUE, FALSE);
    			for ($row = 3; $row <= $highestRow; $row++)
    			{
    				$rowData = $sheet->rangeToArray('A'.$row.':'.$highestColumn.$row,NULL, TRUE, FALSE);
    				$tmp = array();
    				$contadorBiz = 1;
    				foreach($rowData as $data){
    					foreach($data as $id => $value){
    						$clave = $header[0][$id];    						
    						$tmp[$clave] = $value;
    						$contadorBiz++;
	    				}
    				}
    				$this->results[$contador] = $tmp;
    				$contador++;
    			}    			
    		});
    	}
    	catch (\Exception $ex){
    		$this->log->error($e);    		
    	}
    	return $this->results;    	 
    }
    
    
    public function eliminaArchivo($filename){
    	try{
    		if(file_exists(Constantes::PATH_SYSTEM_UPLOAD.$filename))
    			@unlink(Constantes::PATH_SYSTEM_UPLOAD, $filename);
    	}
    	catch (\Exception $ex){
    		$this->log->error($e);
    		throw new \Exception('Error al cargar el archivo: '.$e);
    	}
    }
    
    public function generaArchivoZip($filenames,$filenameZip){
    	try{
    		$zip = new \ZipArchive();
    		$zip->open(Constantes::PATH_SYSTEM_UPLOAD.$filenameZip,\ZipArchive::CREATE);
    		foreach($filenames as $idAseguradora => $data){
    			$zip->addFile(Constantes::PATH_SYSTEM_UPLOAD.$data['file'], $data['file']);
    		}
    		$zip->close();
    	}
    	catch(\Exception $e){
    		$this->log->error($e);
    		return $null;
    	}
    }
    
    public function generaArchivoExcel($filename , $resultsBk){
    	$this->resultsExcel = $resultsBk;
    	if(file_exists(Constantes::PATH_SYSTEM_UPLOAD.$filename.".xls")){
    		@unlink(Constantes::PATH_SYSTEM_UPLOAD.$filename.".xls");
    	}
    	Excel::create($filename, function($excel)  {
    		$excel->sheet("Cotizaciones de renovacion", function($sheet) {
    			$this->estilos($sheet,Constantes::ACTIVO);
    			$sheet->setColumnFormat(array(
    					'AV' => \PHPExcel_Style_NumberFormat::FORMAT_TEXT,
    					'AW' => \PHPExcel_Style_NumberFormat::FORMAT_TEXT,
    					'AZ' => \PHPExcel_Style_NumberFormat::FORMAT_TEXT,
    			));
    			$sheet->fromArray($this->resultsExcel, null, 'A2', false,true);
    			$sheet->cell('AU2', function($cell) {
    				$cell->setValue('No poliza E');
    			});
    			
    			$sheet->cell('AV2', function($cell) {    				
    				$cell->setValue('Fecha inicio vigencia E');
    			});
    			$sheet->cell('AW2', function($cell) {
    				$cell->setValue('Fecha fin vigencia E');
    			});
    			$sheet->cell('AX2', function($cell) {
    				$cell->setValue('Prima neta E');
    			});
    			$sheet->cell('AY2', function($cell) {
    				$cell->setValue('Prima total E');
    			});
    			$sheet->cell('AZ2', function($cell) {
    				$cell->setValue('Fecha emision');
    			});
    			$sheet->cell('BA2', function($cell) {
    				$cell->setValue('Nombre PDF');
    			});    			
    		});
    	})->store('xls', storage_path('../public/upload/'));
    }
    
    
    public function descargaArchivo($filenameZip){
    	header("Content-type: application/octet-stream");
    	header("Content-disposition: attachment; filename=$filenameZip");
    	readfile(Constantes::PATH_SYSTEM_UPLOAD.$filenameZip);
    }
    
    public function validaPesoArchivo($pesoArchivo){
		$exito = true;
    	if((double) $pesoArchivo > (double) $this->maximumSize){
    		$this->log->info(Lang::get ('leyendas.carga.error2'));
    		$this->message =  Lang::get ('leyendas.carga.error2');
    		$exito = false;
    	}
    	return $exito;
    }
    
    public function validaExtensionArchivo($extensionArchivo){
    	$exito = true;
    	if(!in_array(trim(strtolower($extensionArchivo)),$this->extensiones)){
    		$this->log->info(Lang::get ('leyendas.carga.error3'));
    		$this->message =  Lang::get ('leyendas.carga.error3');
    		$exito = false;
    	}
    	return $exito;    	 
    }
    
    public function validaExtensionArchivoRenovaciones($extensionArchivo){
    	$exito = true;
    	if(!in_array(trim(strtolower($extensionArchivo)),$this->extensionesRenovacion)){
    		$this->log->info(Lang::get ('leyendas.carga.error3'));
    		$this->message =  Lang::get ('leyendas.carga.error3');
    		$exito = false;
    	}
    	return $exito;
    }
    public function estilos($sheet,$opcion){
    	$sheet->setHeight(1, 25);
    	$sheet->mergeCells('A1:J1');
    	$sheet->cell('A1', function($cell) {
    		$cell->setValue('CREDITO');
    		 
    	});
    	$sheet->cells('A1:J1', function ($cells) {
    		$cells->setBackground('#ffc000');
    		$cells->setAlignment('center');
    		$cells->setFontColor('#ffffff');
    		$cells->setFontSize(18);
    		$cells->setFontWeight('bold');
    	});
    	$sheet->mergeCells('K1:AB1');
    	$sheet->cell('k1', function($cell) {
    		$cell->setValue('CLIENTE');
    	});
    	$sheet->cells('K1:AB1', function ($cells) {
    		$cells->setBackground('#92D050');
    		$cells->setAlignment('center');
    		$cells->setFontColor('#ffffff');
    		$cells->setFontSize(18);
    		$cells->setFontWeight('bold');
    	});
    	$sheet->mergeCells('AC1:AK1');
    	$sheet->cell('AC1', function($cell) {
    		$cell->setValue('POLIZA');
    	});
    	$sheet->cells('AC1:AK1', function ($cells) {
    		$cells->setBackground('#0070C0');
    		$cells->setAlignment('center');
    		$cells->setFontColor('#ffffff');
    		$cells->setFontSize(18);
    		$cells->setFontWeight('bold');
    	});
    	$sheet->mergeCells('AL1:AR1');
    	if($opcion == 1){
    		$sheet->cell('AL1', function($cell) {
    			$cell->setValue('UNIDAD');
    		});
    	}else{
    		$sheet->cell('AL1', function($cell) {
    			$cell->setValue('INMUEBLE');
    		});
    	}
    	$sheet->cells('AL1:AR1', function ($cells) {
    		$cells->setBackground('#ff0000');
    		$cells->setAlignment('center');
    		$cells->setFontColor('#ffffff');
    		$cells->setFontSize(18);
    		$cells->setFontWeight('bold');
    	});
    	$sheet->mergeCells('AS1:AT1');
    	$sheet->cell('AS1', function($cell) {
    		$cell->setValue('COTIZACION');
    	});
    	$sheet->cells('AS1:AT1', function ($cells) {
    		$cells->setBackground('#00B0F0');
    		$cells->setAlignment('center');
    		$cells->setFontColor('#ffffff');
    		$cells->setFontSize(18);
    		$cells->setFontWeight('bold');
    	});
    		$sheet->mergeCells('AU1:BA1');
    		$sheet->cell('AU1', function($cell) {
    			$cell->setValue('EMISION');
    		});
    		$sheet->cells('AU1:BA1', function ($cells) {
    			$cells->setBackground('#FF8000');
    			$cells->setAlignment('center');
    			$cells->setFontColor('#ffffff');
    			$cells->setFontSize(18);
    			$cells->setFontWeight('bold');
    		});
    			 
    }

    public function guardaRuta(){
    	$path = $_SERVER['REDIRECT_URL'];
    	$tmp  = explode('/',$path);
    	$lon  = count($tmp);
    	$ultimo    = $tmp[($lon - Constantes::ACTIVO)];
    	$penultimo = $tmp[($lon - Constantes::PERFIL_ADMIN_EMPRESA)];
    	if(trim(strtolower($penultimo)) == 'renovacion' || trim(strtolower($penultimo)) == 'gestion'){
    		Session()->put('route',$ultimo);
//     		$this->log->info("Session:   ".Session()->get('route'));
    	}
    	
    }
    
    /**
     * Metodo que genera el password aleatoriamente
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
    
    public function parametros(Request $request, $campoOrder){
    	$noPage = Constantes::NOPAGINA;
    	$noRegs = Constantes::getPaginator();
    	$orden  = Constantes::ORDEN;
    	$campo  = $campoOrder;
    	if($request){
    		if( (int) $request->get('noRegs') > 0){
    			$noRegs = $request->get('noRegs');
    		}
    		if( (int) $request->get('page') > 0){
    			$noPage = $request->get('page');
    		}
    		if( trim($request->get('orden')) != ''){
    			$orden = $request->get('orden');
    		}
    	}
    	return array('campo' => $campo,'orden' => $orden, 'nopage' => $noPage, 'noregs' => $noRegs);
    }
    


   public function encriptar($string, $key) {
    	//     	$key = "MICLAVE_123456789";
    	$result = '';
    	$test = array();
    	for($i=0; $i<strlen($string); $i++) {
    		$char = substr($string, $i, 1);
    		$keychar = substr($key, ($i % strlen($key))-1, 1);
    		$char = chr(ord($char)+ord($keychar));
    
    		$test[$char]= ord($char)+ord($keychar);
    		$result.=$char;
    	}
    	return urlencode(base64_encode($result));
    }
    
    public function desencriptar($string, $key) {
    	//     	$key = "MICLAVE_123456789";
    	$result = '';
    	$string = base64_decode(urldecode($string));
    	for($i=0; $i<strlen($string); $i++) {
    		$char = substr($string, $i, 1);
    		$keychar = substr($key, ($i % strlen($key))-1, 1);
    		$char = chr(ord($char)-ord($keychar));
    		$result.=$char;
    	}
    	return $result;
    }
    
    
    
   
}
