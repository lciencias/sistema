<?php

namespace sistema\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use sistema\Policies\Constantes;
use sistema\Repositories\CalificarRepository;
use Illuminate\Support\Facades\Crypt;
use sistema\Models\ViewEjercicio;


class CalificarController extends Controller
{
    private $evaluacionRepository;
    function __construct(CalificarRepository $calificarRepository){
        parent::__construct();
        $this->calificarRepository = $calificarRepository;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        Session::forget('message-warning');
        if ($request) {
            $controller = $this->calificarRepository->obtenerNombreController($request);
            $moduloId   = $this->calificarRepository->obtenModuloId($controller);
            return view ( 'evaluacion.calificar.indexCalificar', [
                "isAdmin"     => Session::get ('isAdmin'),
                "moduloId"    => $moduloId,
                "controller"  => $controller
            ] );
        }
    }
  
    /**
     * Metodo para buscar usuarios
     * @param Request $request
     */
    
    public function buscar(Request $request){
        Session::forget('message-warning');
        $moduloId   = $request->get('idModulo');
        $opciones   = $this->parametros($request, 'idcandidato_proyecto_ejercicio');
        $view 		= $this->buscaEjercicios($request,$opciones,$moduloId);
        return response()->json(view('evaluacion.calificar.busquedaCalificar',$view)->render());
    }
    
    private function buscaEjercicios(Request $request,$opciones,$moduloId){
        $session    = Session()->get('permisos');
        $tipo       = $this->calificarRepository->obtenTipoEjercicios();
        $proyectos  = $this->calificarRepository->obtenProyectos();
        $filtros    = $this->generaFiltrosEjercicios($request);
        $total      = $this->calificarRepository->findByCount($filtros);
        $ejercicios = $this->calificarRepository->findByColumn($filtros,$opciones);
        return ["ejercicios" => $ejercicios,
                "total"      => $total,
                "leyenda"    => $this->calificarRepository->generaLetrero($total,count($ejercicios),$opciones),
                "sessionPermisos" => $session[$moduloId],
                "filtros"     => $filtros,
                "moduloId"    => $moduloId,
                "tipo"        => $tipo,
                "proyectos"   => $proyectos,
                "noPage" 	  => $opciones['nopage'],
                "noRegs" 	  => $opciones['noregs'],
                "nombre"  	  => $request->get('nombre'),
                "paterno" 	  => $request->get('paterno'),
                "materno" 	  => $request->get('materno'),
                "fechaFin"    => $request->get('fechaFin'),
                "idProyecto"  => $request->get('idProyecto'),
                "idEjercicio" => $request->get('idEjercicio'),
                "estatus"     => $request->get('estatus'),
                "catEstatus"  => Constantes::CATALOGO_ESTATUS_CALIFICAR,
                "isAdmin"     => Session::get ('isAdmin'),
        ];
    }
    
    
    private function generaFiltrosEjercicios(Request $request){
        $filtros   = array ();
        $nombre       = trim($request->get('nombre'));
        $paterno      = trim($request->get('paterno'));
        $materno      = trim($request->get('materno'));
        $idProyecto   = (int) $request->get('idProyecto') + 0;
        $idEjercicio  = (int) $request->get('idEjercicio') + 0;
        $fechaFin     = trim($request->get('fechaFin'));
        $estatus      = (int) $request->get('estatus') + 0;
        $filtros []   = ['view_ejercicio.iduser', '=',Session::get('idUser')];
        if(trim($nombre) != ''){
            $filtros[] = ['view_ejercicio.nombre', 'LIKE', '%' . $nombre . '%'];
        }
        if(trim($paterno) != ''){
            $filtros[] = ['view_ejercicio.paterno', 'LIKE', '%' . $paterno . '%'];
        }
        if(trim($materno) != ''){
            $filtros[] = ['view_ejercicio.materno', 'LIKE', '%' . $materno . '%'];
        }
        if($idProyecto > 0){
            $filtros[] = ['view_ejercicio.idproyecto', '=',$idProyecto];
        }
        if($idEjercicio > 0){
            $filtros[] = ['view_ejercicio.idtipo_ejercicio', '=',$idEjercicio];
        }
        if(trim($fechaFin) != "") {
        $filtros[] = ['view_ejercicio.fecha_fin', '=', $fechaFin];
        }
        if($estatus > 0){
            $filtros[] = ['view_ejercicio.estatus', '=',$estatus];
        }
        return $filtros;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){}
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){}
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){}
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $cliente = null;
        $id =  Crypt::decrypt($id);
        if (( int ) $id > 0) {
            try {
                Session ()->put ( 'editar', true );
                $candidatoProyectoEjercicio = $this->calificarRepository->obtenRegistro( $id );
                $cliente      = $this->calificarRepository->obtenCliente($candidatoProyectoEjercicio['idcliente']);
                $competencias = $this->calificarRepository->obtenCompetencias();
                $registros    = $this->calificarRepository->obtenRegistros($candidatoProyectoEjercicio['idtipo_ejercicio_cliente']);
                $calificacion = $this->calificarRepository->obtenCalificacion($candidatoProyectoEjercicio['idtipo_ejercicio_cliente']);
                $caliNumerica = $this->calificarRepository->obtenCalificacionNumerica($candidatoProyectoEjercicio['idtipo_ejercicio_cliente']);
                $totalCalific = $this->calificarRepository->obtenTotal();
                $registros    = $this->calificarRepository->uneArrays($registros,$calificacion,'calif');
                $registros    = $this->calificarRepository->uneArrays($registros,$caliNumerica,'combo');
                //$this->calificarRepository->debug($registros);
                $idEjercicio  = $candidatoProyectoEjercicio['idcandidato_proyecto_ejercicio'];
                return view ( "evaluacion.calificar.editCalificar", [
                    "candidatoProyectoEjercicio" => $candidatoProyectoEjercicio,
                    "idProyectoEjercicio" => $idEjercicio,
                    "totalCalific" => $totalCalific,
                    "competencias" => $competencias,
                    "calificacion" => $calificacion,
                    "registros" => $registros,
                    "cliente" => $cliente                    
                ] );
            }
            catch ( \Exception $e ) {
                die("msg:  ".$e->getMessage());                
            }
        }
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){}
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){}
    
    
}