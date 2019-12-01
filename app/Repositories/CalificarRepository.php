<?php 
namespace sistema\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use sistema\Repositories\Eloquent\Repository;
use sistema\Policies\Constantes;
use sistema\Models\CandidatoProyectoEjercicio;
use sistema\Models\TipoEjercicioClienteComportamiento;
use sistema\Models\ResultadoCandidatoEjercicio;
use sistema\Models\DetalleResultadoCandidatoEjercicio;


/**
 * Clase de servicio de acceso a datos de perfiles
 * @author Miguel Molina 05/04/2017
 *
 */
class CalificarRepository extends Repository {

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public static $campoBase = 'nombre';
    private $totalCalificaciones = 0;
    
    function model()
    {
        return 'sistema\Models\ViewEjercicio';
    }
    
    public function obtenTipoEjercicios(){
        return DB::table( 'tipo_ejercicio' )->where('activo','=',Constantes::ACTIVO)->orderBy('idtipo_ejercicio')->get();
    }

    public function obtenProyectos(){
        return DB::table( 'proyecto' )->where('activo','=',Constantes::ACTIVO)->orderBy('idproyecto')->get();
    }
    
    public function obtenRegistro( $id ){
        return $this->eliminaIndiceConKey(DB::table( 'view_ejercicio' )->where('idcandidato_proyecto_ejercicio','=',$id)->get()->toArray());
    }
        
    public function obtenCliente($idcliente){
        return $this->eliminaIndiceConKey(DB::table( 'cliente' )->where('idcliente','=',$idcliente)->get()->toArray());
    }
    
    public function obtenCompetencias(){
        return $this->convierteCatalogo(DB::table( 'competencia' )->select('idcompetencia','nombre')  ->where('activo','=',Constantes::ACTIVO)->get()->toArray());
    }
    
    public function obtenRegistros($idtipo_ejercicio_cliente){
    return $this->agrupaCompetencia(TipoEjercicioClienteComportamiento
            ::join('comportamiento','comportamiento.idcomportamiento','=','tipo_ejercicio_cliente_comportamiento.idcomportamiento')
            ->join('competencia','competencia.idcompetencia', '=', 'comportamiento.idcompetencia')
            ->join('tipo_competencia','tipo_competencia.idtipo_competencia', '=', 'competencia.idtipo_competencia')
            ->select('tipo_ejercicio_cliente_comportamiento.idtipo_ejercicio_cliente_comportamiento',
                'tipo_ejercicio_cliente_comportamiento.idcomportamiento',
                'tipo_ejercicio_cliente_comportamiento.idtipo_ejercicio_cliente',
                'comportamiento.idcompetencia','comportamiento.nombre as comportamiento',
                'competencia.idtipo_competencia','competencia.nombre as competencia',
                'tipo_competencia.nombre as tipo_competencia')
            ->where('tipo_ejercicio_cliente_comportamiento.idtipo_ejercicio_cliente', '=', $idtipo_ejercicio_cliente)
            ->orderBy('competencia.idcompetencia','asc')
            ->get()->toArray());    
    }
    
    public function obtenCalificacion($idtipo_ejercicio_cliente){
        return $this->agrupaCalificacion(TipoEjercicioClienteComportamiento
            ::join('comportamiento','comportamiento.idcomportamiento','=','tipo_ejercicio_cliente_comportamiento.idcomportamiento')
            ->join('calificacion_comportamiento','calificacion_comportamiento.idcomportamiento', '=', 'comportamiento.idcomportamiento')
            ->select('tipo_ejercicio_cliente_comportamiento.idcomportamiento',
                'calificacion_comportamiento.idcalificacion_comportamiento','calificacion_comportamiento.calificacion_texto',
                'calificacion_comportamiento.idescala_calificacion_competencia')
            ->where('tipo_ejercicio_cliente_comportamiento.idtipo_ejercicio_cliente', '=', $idtipo_ejercicio_cliente)
            ->orderBy('comportamiento.idcomportamiento','asc')
            ->orderBy('calificacion_comportamiento.idcalificacion_comportamiento','asc')
            ->get()->toArray());
    }
    
    public function obtenCalificacionNumerica($idtipo_ejercicio_cliente){
        return $this->agrupaCalificacionBiz(TipoEjercicioClienteComportamiento
         ::join('comportamiento','comportamiento.idcomportamiento','=','tipo_ejercicio_cliente_comportamiento.idcomportamiento')
         ->join('calificacion_comportamiento','calificacion_comportamiento.idcomportamiento', '=', 'comportamiento.idcomportamiento')
         ->join('calificacion_escala','calificacion_escala.idescala_calificacion_competencia', '=', 'calificacion_comportamiento.idescala_calificacion_competencia')
            ->select('tipo_ejercicio_cliente_comportamiento.idcomportamiento','calificacion_escala.idcalificacion_escala','calificacion_escala.calificacion')
         ->where('tipo_ejercicio_cliente_comportamiento.idtipo_ejercicio_cliente', '=', $idtipo_ejercicio_cliente)
         ->orderBy('comportamiento.idcomportamiento','asc')
         ->orderBy('calificacion_escala.calificacion','asc')
         ->get()->toArray());
    }
    
    
    public function obtenCandidatoProyectoEjercicios(){
        return CandidatoProyectoEjercicio::
        join('candidato_proyecto','candidato_proyecto.idcandidato_proyecto','=','candidato_proyecto_ejercicio.idcandidato_proyecto')
        ->join('ejercicio','ejercicio.idejercicio', '=','candidato_proyecto_ejercicio.idejercicio')
        ->join('tipo_ejercicio_cliente','tipo_ejercicio_cliente.idtipo_ejercicio_cliente', '=','candidato_proyecto_ejercicio.idtipo_ejercicio_cliente')
        ->join('candidato','candidato.idcandidato', '=', 'candidato_proyecto.idcandidato')
        ->join('proyecto','proyecto.idproyecto', '=', 'candidato_proyecto_ejercicio.idproyecto')
        ->select('candidato_proyecto_ejercicio.idcandidato_proyecto',
            'candidato_proyecto_ejercicio.idejercicio','candidato_proyecto_ejercicio.idcandidato_proyecto','candidato_proyecto_ejercicio.iduser',
            'candidato_proyecto_ejercicio.estatus','candidato_proyecto_ejercicio.idtipo_ejercicio_cliente','ejercicio.nombre as ejercicio',
            'candidato.nombre as candidato','candidato.paterno as paterno','candidato.materno as materno','proyecto.nombre as proyecto',
            'proyecto.fecha_fin','DATEDIFF("' . Carbon::now() . '",proyecto.fecha_fin) as dias')
        ->where('candidato_proyecto_ejercicio.iduser',Session::get('idUser'))
        ->orderBy('candidato.nombre asc','candidato.paterno asc','candidato.materno asc')
        ->get()->toArray();
    }
    
    public function uneArrays($registros,$calificacion,$campo){
        $array = array();
        foreach($registros as $idCompe => $registro){
            foreach($registro as $idComp => $data){
                if(key_exists($idComp, $calificacion)){
                    $data[$campo] = $calificacion[$idComp];
                    $array[$idCompe][$idComp] = $data;
                }
            }
        }
        return $array;
    }
    
    public function obtenTotal(){
        return $this->totalCalificaciones;
    }
    
    public function guardaRegistros($request, $id){
        $this->guardaCompetencias($request);
        $this->actualizaCandadidatoProyectoEjercicio($request);
    }
    
    public function recuperaCalificaciones($idEjercicio){
        return $this->generaFormato(ResultadoCandidatoEjercicio
        ::join('detalle_resultado_candidato_ejercicio','detalle_resultado_candidato_ejercicio.idresultado_candidato_ejercicio', '=' ,'resultado_candidato_ejercicio.idresultado_candidato_ejercicio')
        ->where('resultado_candidato_ejercicio.idcandidato_proyecto_ejercicio','=', $idEjercicio)
            ->select('resultado_candidato_ejercicio.idresultado_candidato_ejercicio',
                     'resultado_candidato_ejercicio.idcompetencia',
                     'detalle_resultado_candidato_ejercicio.iddetalle_resultado_candidato_ejercicio',
                     'detalle_resultado_candidato_ejercicio.idcomportamiento',
                     'detalle_resultado_candidato_ejercicio.calificacion',
                     'detalle_resultado_candidato_ejercicio.idcalificacion_comportamiento')
        ->orderBy('resultado_candidato_ejercicio.idcompetencia','asc')
        ->orderBy('detalle_resultado_candidato_ejercicio.idcomportamiento','asc')
        ->get()->toArray());
    }
    
    private function generaFormato($datas){
        $regreso = array();
        foreach($datas as $data){
            $clave = $data['idcompetencia'].'-'.$data['idcomportamiento'];            
            $regreso[$clave]['calificacion'] = $data['calificacion'];
            $regreso[$clave]['idresultado_candidato_ejercicio'] = $data['idresultado_candidato_ejercicio'];
            $regreso[$clave]['idcalificacion_comportamiento'] = $data['idcalificacion_comportamiento'];
            $regreso[$clave]['iddetalle_resultado_candidato_ejercicio'] = $data['iddetalle_resultado_candidato_ejercicio'];
        }
        return $regreso;
    }
    
    
    private function guardaCompetencias($request){
        $id      = $request->input('id');
        $estatus = $request->input('estatus');
        $input = $request->all();
        $competencias = $comportamientos = array();
        if($estatus > 0){  // ya existen comentarios, los eliminos
            $this->eliminaCalificaciones($input);
        }        
        foreach($input as $key => $value){
            if( substr($key,0,2) == "s-"){
            $tmp = explode('-',$key);
            $idCompetencia = $tmp[2];
            $idComportamiento = $tmp[3];
            $keyRadio = "r-".$id."-".$idCompetencia."-".$idComportamiento;
            $competencias[$idCompetencia][] = $value;
            $comportamientos[$idCompetencia]['idCompetencia'] = $idCompetencia;
            $comportamientos[$idCompetencia]['idComportamiento'] = $idComportamiento;
            $comportamientos[$idCompetencia]['calificacion'] = $value;
            $comportamientos[$idCompetencia]['idcalificacion_comportamiento'] = $input[$keyRadio];
            $comportamientos[$idCompetencia]['idresultado_candidato_ejercicio'] = null;
            }
        }
        if(count($competencias) > 0){
              $this->insertaResultados($competencias ,$id, $comportamientos);
        }    
    }
    
    private function eliminaCalificaciones($input){
        $arrayResultados = $arrayDetalles = array();
        foreach($input as $key => $value){
            if( substr($key,0,2) == "s-"){
                $tmp = explode('-',$key);                
                $arrayResultados[] = $tmp[5];
                $arrayDetalles[]   = $tmp[6];                
            }
        }
        if(count($arrayDetalles) > 0){
            foreach($arrayDetalles as $idDetalle){
                $this->eliminaDetalle($idDetalle);
            }
        }
        if(count($arrayResultados) > 0){
            foreach($arrayResultados as $idResultado){
                $this->eliminaResultado($idResultado);
            }
        }
    }
    
    private function eliminaDetalle($idDetalle){
        DetalleResultadoCandidatoEjercicio::destroy($idDetalle);
    }
    
    private function eliminaResultado($idResultado){
        ResultadoCandidatoEjercicio::destroy($idResultado);
        
    }
    
    private function insertaResultados($competencias,$id, $comportamientos){
        $noCalificaciones = $califCompetencia = $totalCalificacion = 0;
        foreach($competencias as $idCompetencia => $calificaciones){
            $noCalificaciones = $califCompetencia = $totalCalificacion = 0;
            foreach($calificaciones as $total){
                $califCompetencia = $califCompetencia + $total;
                $noCalificaciones ++;
            }
            $totalCalificacion = number_format($califCompetencia / $noCalificaciones, 1,'.',',');
            $resultado = $this->insertaCompetencia($id,$idCompetencia,$totalCalificacion);
            $comportamientos[$idCompetencia]['idresultado_candidato_ejercicio'] = $resultado->idresultado_candidato_ejercicio;
        }
        $this->guardaComportamiento($id,$comportamientos);
    }
    
    private function insertaCompetencia($id,$idCompetencia,$totalCalificacion){
        DB::beginTransaction ();
        try {
            $resultado = new ResultadoCandidatoEjercicio();
            $resultado->idcompetencia = $idCompetencia;
            $resultado->idcandidato_proyecto_ejercicio = $id;
            $resultado->calificacion_promedio = $totalCalificacion;
            $resultado->save();
            DB::commit();
        }
        catch ( \Exception $e ) {
            die("error:  ".$e->getMessage());
            DB::rollback ();
            die("error:  ".$e->getMessage());
            throw new \Exception('Error al guardar Espacio: ' . $e);
        }
        return $resultado;
    }
    
    private function insertaComportamiento($id,$comportamiento){
        DB::beginTransaction ();
        try {
            $resultado= new DetalleResultadoCandidatoEjercicio();
            $resultado->idresultado_candidato_ejercicio = $comportamiento['idresultado_candidato_ejercicio'];
            $resultado->idcomportamiento = $comportamiento['idComportamiento'];
            $resultado->calificacion = $comportamiento['calificacion'];    
            $resultado->idcalificacion_comportamiento = $comportamiento['idcalificacion_comportamiento'];
            $resultado->save();
            DB::commit();
        }
        catch ( \Exception $e ) {
            DB::rollback ();
            die("error:  ".$e->getMessage());
            throw new \Exception('Error al guardar Espacio: ' . $e);
        }
        
    }
    
    private function guardaComportamiento($id,$comportamientos){
        foreach($comportamientos as $comportamiento){
            $this->insertaComportamiento($id,$comportamiento);
        }
    }
    
    private function actualizaCandadidatoProyectoEjercicio($request){
        DB::beginTransaction ();
        try{
            $candidatoProyectoEjercicio = $this->getCandadidatoProyectoEjercicio($request->input('id'));
            $candidatoProyectoEjercicio->estatus = $request->input('estatus');
            $candidatoProyectoEjercicio->observaciones = $request->input('observaciones');
            $candidatoProyectoEjercicio->update();
            DB::commit ();
        }
        catch ( \Exception $e ) {
            DB::rollback ();
            throw new \Exception('Error al restablecer a la espacio con id:'.$id.' -> ' . $e);
        }
    }
    
    private function agrupaCompetencia($array){
        $regreso = array();
        foreach($array as $data){
            $idCompetencia    = $data['idcompetencia'];
            $idComportamiento = $data['idcomportamiento'];
            $regreso[$idCompetencia][$idComportamiento] = $data;
        }
        return $regreso;
    }
    
    private function agrupaCalificacion($array){
        $regreso = array();
        foreach($array as $data){
            $idComportamiento = $data['idcomportamiento'];
            $regreso[$idComportamiento][] = $data;            
        }
        $this->totalCalificaciones = count($regreso[$idComportamiento]);
        return $regreso;
    }
    private function agrupaCalificacionBiz($array){
        $regreso = array();
        foreach($array as $data){
                $idComportamiento = $data['idcomportamiento'];
                $idcalificacion_escala = $data['idcalificacion_escala'];
                $regreso[$idComportamiento][$idcalificacion_escala] = $data['calificacion'];
        }
        return $regreso;
    }
    
    private function convierteCatalogo($array){
        $regreso = array();
        foreach($array as $obj){
            $regreso[$obj->idcompetencia] = $obj->nombre;
        }
        return $regreso;
    }
    
    function getCandadidatoProyectoEjercicio($id){
        return  CandidatoProyectoEjercicio::findOrFail( $id );
    }
}