<?php

namespace sistema\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use sistema\Policies\Constantes;
use sistema\Repositories\CalificarRepository;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;

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
        Session()->put('editar',true);
        $cliente = null;
        $id =  Crypt::decrypt($id);
        if (( int ) $id > 0) {
            try {
                Session ()->put ( 'editar', true );
                $calificaciones = $caliNumerica = array();
                $candidatoProyectoEjercicio = $this->calificarRepository->obtenRegistro( $id );
                $cliente      = $this->calificarRepository->obtenCliente($candidatoProyectoEjercicio['idcliente']);
                $competencias = $this->calificarRepository->obtenCompetencias();
                $registros    = $this->calificarRepository->obtenRegistros($candidatoProyectoEjercicio['idtipo_ejercicio_cliente']);
                $calificacion = $this->calificarRepository->obtenCalificacion($candidatoProyectoEjercicio['idtipo_ejercicio_cliente']);                
                $totalCalific = $this->calificarRepository->obtenTotal();
                $registros    = $this->calificarRepository->uneArrays($registros,$calificacion,'calif');
                $idEjercicio  = $candidatoProyectoEjercicio['idcandidato_proyecto_ejercicio'];
                if((int) $candidatoProyectoEjercicio['estatus']> 0){ // consulto datos
                    $caliNumerica = $this->calificarRepository->obtenCalificacionNumerica($id,$candidatoProyectoEjercicio['estatus']);
                    $calificaciones = $this->calificarRepository->recuperaCalificaciones($idEjercicio);
                }
                return view ( "evaluacion.calificar.editCalificar", [
                    "candidatoProyectoEjercicio" => $candidatoProyectoEjercicio,
                    "idProyectoEjercicio" => $idEjercicio,
                    "totalCalific" => $totalCalific,
                    "competencias" => $competencias,
                    "calificacion" => $calificacion,
                    "caliNumerica" => $caliNumerica,
                    "registros" => $registros,
                    "calificaciones" => $calificaciones,
                    "idEstatus" => $candidatoProyectoEjercicio['estatus'],
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
    public function update(Request $request, $id){
        try{
            $id =  Crypt::decrypt($id);
            $this->calificarRepository->guardaRegistros($request, $id);
            Session::flash ( 'message', Lang::get ( 'general.success' ) );            
        }
        catch ( \Exception $e ) {
            $this->log->error($e);
            Session::flash ( 'message-error', Lang::get ( 'general.error' ) );
        }
        finally {
            return Redirect::to ( 'evaluacion/calificar' );
        }
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){}
    
    
    public function buscaCalificaciones(Request $request){
        $exito = 0;
        $calificaciones = array();
        try{
            $id = $request->get('idCalComp_');
            if((int) $id > 0){
                $calificaciones = $this->calificarRepository->getCalificacionesNumericas($id);
                $exito = 1;
            }
        }
        catch (\Exception $e) {
            $this->log->error($e->getMessage()."<br>".$e->getMessage());
        }
        finally {
            return json_encode(array('exito' => $exito,'calificaciones' => $calificaciones, 'total' => count($calificaciones)));
        }   
    }
}
