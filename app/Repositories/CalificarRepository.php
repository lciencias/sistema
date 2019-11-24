<?php 
namespace sistema\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use sistema\Repositories\Eloquent\Repository;
use sistema\Policies\Constantes;
use sistema\Models\CandidatoProyectoEjercicio;
use sistema\Models\TipoEjercicioClienteComportamiento;


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
}