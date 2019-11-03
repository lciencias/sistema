<?php

namespace sistema\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use sistema\Jobs\EnviaEmailReporteErroresJob;
use sistema\Models\ReporteError;
use sistema\Repositories\PerfilRepository;

class CronReporteErroresController extends Controller {
	private $repository;
	
	public function __construct(PerfilRepository $repository) {
		parent::__construct ();
		$this->repository = $repository;
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request) {
		try {
			$this->log->info("Se inicia el proceso de envío de notificaciones de los errores generados");
			
			//Se obtienen los errores no reportados
			$errores  = $this->repository->getAllErrores();
			
			if(count($errores) > 0) {
				try{
					$this->dispatch(new EnviaEmailReporteErroresJob(($errores)));
					//Se actualiza el estatus a reportado
					foreach ($errores as $error) {
						$actualizaError = ReporteError::findOrFail($error->idreporte_error);
						$actualizaError->reportado = true;
						$actualizaError->save();
					}
				}
				catch(\Exception $e){
					$this->log->error("Error:  ".$e->getMessage()."\n".$e->getMessage());
				}
			} else {
				$this->log->info("No existen errores por enviar");
			}
			
		} catch ( \Exception $e ) {
			$this->log->error ( $e->getMessage () . "\n" . $e->getMessage() );
		} finally {
			return Redirect::to ( 'home' );
		}
	}
	
	public function generaFiltros(Request $request){
		$filtros   = array ();
		$fecha = new Carbon();
		$filtros[] = ['mi_tarea.fecha_limite', '<=',$fecha->format('Y-m-d H:i:s')];
		$filtros[] = ['mi_tarea.reportada', '=', false];
		$filtros[] = ['mi_tarea.estatus', '!=', '3'];
		return $filtros;
	}
}
