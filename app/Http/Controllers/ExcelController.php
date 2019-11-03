<?php

namespace sistema\Http\Controllers;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use sistema\Repositories\UsuarioRepository;

class ExcelController extends Controller
{
	private $repository;
	public $data = array();
	public $arrayMonths = array();

	public function __construct(UsuarioRepository $repository, $data = array()) {
		$this->repository = $repository;
		$this->data = $data;
		$this->arrayMonths = array (
			'01'=>'Enero',
			'02'=>'Febrero',
			'03'=>'Marzo',
			'04'=>'Abril',
			'05'=>'Mayo',
			'06'=>'Junio',
			'07'=>'Julio',
			'08'=>'Agosto',
			'09'=>'Septiembre',
			'10'=>'Octubre',
			'11'=>'Noviembre',
			'12'=>'Diciembre'
		);
	}
	
	public function index($id)
	{
		try {
			$registros = null;
			$nmModulo  = $this->repository->obtenerNombreModulo($id);
			switch($nmModulo){
				case "Usuarios":
					$this->exportarUsuarios();
					break;
				case "Perfiles":
					$this->exportarPerfiles();
					break;
				case "Empresas":
					$this->exportarEmpresas();
					break;
				case "Accesos":
					$this->exportarAccesos();
					break;
				case "Movimientos":
					$this->exportarMovimientos();
					break;
				case "Mis Tareas":
					$this->exportarTareas();
					break;
				case "Talentos":
				    $this->exportarTalentos();
					break;
			
			}
			return view ( 'seguridad.usuario.index', ["usuarios" => $registros, "nmModulo" => $nmModulo]);
		} catch ( \Exception $e ) {
			Session::flash ( 'message-error', Lang::get ( 'general.error' ) );
		}
		
	}


	private function exportarAccesos(){
		Excel::create('Accesos', function($excel) {
			$excel->sheet("Accesos", function($sheet) {
				$registros = $this->repository->getAllAccesosXls();
				$registros = $this->repository->getArray($registros);
				$sheet->fromArray($registros);
			});
		})->export('xls');
		
	}
	private function exportarMovimientos(){
		Excel::create('Movimientos', function($excel) {
			$excel->sheet("Movimientos", function($sheet) {
				$registros = $this->repository->getAllMovimientosXls();
				$registros = $this->repository->getArray($registros);
				$sheet->fromArray($registros);
			});
		})->export('xls');
		
	}
	
	private function exportarUsuarios(){
		Excel::create('Usuarios', function($excel) {
			$excel->sheet("Usuarios", function($sheet) {
				$registros = $this->repository->getAllUsuariosXls();
				$registros = $this->repository->getArray($registros);				
				$sheet->fromArray($registros);
			});
		})->export('xls');		
	}


	
	private function exportarPerfiles(){
		Excel::create('Perfiles', function($excel) {
			$excel->sheet("Perfiles", function($sheet) {
				$registros = $this->repository->getAllPerfilesXls();
				$registros = $this->repository->getArray($registros);
				$sheet->fromArray($registros);
			});
		})->export('xls');		
	}
	
	private function exportarEmpresas(){
		Excel::create('sistema Excel', function($excel) {
			$excel->sheet("Empresas", function($sheet) {
				$registros = $this->repository->getAllEmpresasXls();
				$registros = $this->repository->getArray($registros);
				$sheet->fromArray($registros);
			});
		})->export('xls');		
	}
	
	private function exportarTareas(){
		Excel::create('sistema Excel', function($excel) {
			$excel->sheet("Tareas", function($sheet) {
				$registros = $this->repository->getAllTareasXls();
				$registros = $this->repository->getArray($registros);
				$sheet->fromArray($registros);
			});
		})->export('xls');	
	}
	
	private function exportarTalentos(){
	    Excel::create('Talentos', function($excel) {
	        $excel->sheet("Talentos", function($sheet) {
	            $this->estilos($sheet,'A1:C1');
	            $registros = $this->repository->getAllTalentosXls();
	            $registros = $this->repository->getArray($registros);
	            $sheet->fromArray($registros);
	        });
	    })->export('xls');
	    
	}
	
	
	public function estilos($sheet,$rango){
	    $sheet->setHeight(1, 20);
	    $sheet->cell($rango, function($cells) {
	        $cells->setFontColor('#000000');
	        $cells->setBackground('#ffc000');
	        $cells->setAlignment('center');
	        $cells->setFontSize(14);
	        $cells->setFontWeight('bold');
	        
	    });
	                                                
	}
	
}