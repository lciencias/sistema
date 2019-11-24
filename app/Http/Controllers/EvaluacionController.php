<?php

namespace sistema\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use sistema\Repositories\EvaluacionRepository;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;


class EvaluacionController extends Controller
{
    
    private $evaluacionRepository;
    function __construct(EvaluacionRepository $evaluacionRepository){
        parent::__construct();
        $this->evaluacionRepository = $evaluacionRepository;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pruebas = array();
        Session::forget('message-warning');
        $controller    = $this->evaluacionRepository->obtenerNombreController($request);
        $moduloId      = $this->evaluacionRepository->obtenModuloId($controller);
        $titulo        = "El candidato no tiene perfil de puesto asignado";
        $perfilPuestos = $this->evaluacionRepository->obtenPerfilPuestos();
        Session()->put('perfilPuestos',$perfilPuestos);
        
        if($perfilPuestos != null && (int) $perfilPuestos['idperfil_puesto'] > 0){            
            $pruebas = $this->evaluacionRepository->obtenPuestos($perfilPuestos);
            $titulo  = "El candidato tiene ".count($pruebas)." pruebas asignadas.";
        }
        return view ( 'evaluacion.index', ["moduloId"  => $moduloId,"isAdmin"   => $this->isAdmin,"idRol" => $this->idRol, 
                    'titulo' => $titulo, 'pruebas' => $pruebas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            if($request->isMethod('post') && trim($request->get('valores') ) != ''){		
                $this->evaluacionRepository->insertaCuestionario($request->get('valores'));
                Session::flash ( 'message', Lang::get ( 'general.success' ) );
            }else{
                Session::flash ( 'message-error', Lang::get ( 'general.error' ) );
            }
        }
        catch ( \Exception $e ) {
            $this->log->error($e);
            Session::flash ( 'message-error', Lang::get ( 'general.error' ) );
        } finally {
        return Redirect::to ( 'evaluacion/evaluacion' );
        }
    }

    public function evalua($id){
        $titulo = "";
        $horaActual = date("Y-m-d H:i:s");
        Session()->put('horaActual',$horaActual);
        $preguntas = $opciones = array();
        if((int) $id > 0){
            $titulo = "Cuestionario";
            try{
                if($this->evaluacionRepository->revisaPruebaUsuario($id)){
                    $preguntas = $this->evaluacionRepository->obtenPreguntas($id);
                    $ids = $this->obtenIdPreguntas($preguntas);
                    $preguntas = $this->separaEnSecciones($preguntas);                   
                    $opciones  = $this->evaluacionRepository->obtenOpciones($ids);
                    $opciones  = $this->ordenaOpciones($opciones);        
                    Session()->put('iniciaCuestionario',$horaActual);
                }                
                return view ( "evaluacion.cuestionario", [
                    "isAdmin" => $this->isAdmin, "titulo" => $titulo,
                    "preguntas" => $preguntas, "opciones" => $opciones,"horaActual" => substr($horaActual,11,8)
                        ] );
            }
            catch(\Exception $e){
                $this->log->error($e);
            }
        }
    }
    
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    private function obtenIdPreguntas($preguntas){
        $idsPreguntas = array();
        if(count($preguntas)>0){
            foreach($preguntas as $pregunta){
                if(!in_array($pregunta['idpregunta_prueba'],$idsPreguntas)){
                    $idsPreguntas[] = $pregunta['idpregunta_prueba'];
                }
            }
        }
        return $idsPreguntas;
    }
    
    
    private function separaEnSecciones($preguntas){
        $regreso = array();
        if(count($preguntas)>0){
            foreach($preguntas as $pregunta){
                $seccion = $pregunta['seccion'];
                $regreso[$seccion][] = $pregunta;
            }
        }
        return $regreso;
    }
    
    private function ordenaOpciones($opciones){
        $regreso = array();
        if(count($opciones)>0){
            foreach($opciones as $opcion){
                $idpregunta_prueba = $opcion['idpregunta_prueba'];
                $orden = $opcion['orden'];
                $regreso[$idpregunta_prueba][$orden] = $opcion;           
            }
        }
        return $regreso;
    }
}
