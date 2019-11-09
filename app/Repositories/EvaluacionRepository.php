<?php 
namespace sistema\Repositories;

use Illuminate\Support\Facades\Session;
use sistema\Repositories\Eloquent\Repository;
use sistema\Models\Candidato;
use sistema\Models\PerfilPuestoPrueba;
use sistema\Models\Prueba;
use sistema\Models\PruebaInterpretacion;
use sistema\Policies\Constantes;
use sistema\Models\OpcionPregunta;
use sistema\Models\ResultadoCandidatoPrueba;
use sistema\Models\DetalleResultadoCandidatoPrueba;


/**
 * Clase de servicio de acceso a datos de perfiles
 * @author Miguel Molina 05/04/2017
 *
 */
class EvaluacionRepository extends Repository {

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'sistema\Models\Candidato';
    }
        
    /**
     * Metodo que sirve para regresar el objeto de un usuario 
     * @param int $id
     */
    function obtenPerfilPuestos(){
        return $this->eliminaIndiceConKey(Candidato::join('candidato_proyecto','candidato_proyecto.idcandidato', '=', 'candidato.idcandidato')
        ->select('candidato.idcandidato','candidato_proyecto.idcandidato_proyecto','candidato_proyecto.idperfil_puesto','candidato_proyecto.idproyecto','candidato_proyecto.fecha_registro')
        ->where('candidato.iduser',Session::get('idUser'))
        ->get()->toArray());
    }
    
    function obtenPuestos($perfilPuesto){
       return PerfilPuestoPrueba::join('prueba','prueba.idprueba','=','perfil_puesto_pruebas.idprueba')
        ->where('perfil_puesto_pruebas.idperfil_puesto',$perfilPuesto['idperfil_puesto'])
        ->get();        
    }
    
    function obtenPruebaInterpretacion($pruebaId){
        return $this->eliminaIndiceConKey(PruebaInterpretacion::join('prueba','prueba.idprueba','=','prueba_interpretacion.idprueba')
            ->select('prueba_interpretacion.idprueba_interpretacion','prueba_interpretacion.idprueba','prueba_interpretacion.resultado','prueba_interpretacion.interpretacion','prueba.nombre')
            ->where('prueba_interpretacion.idprueba',$pruebaId)
            ->get()->toArray());
    }

    function revisaPruebaUsuario($id){
        return true;
    }
    
    function obtenPreguntas($id){
        return Prueba::join('pregunta_prueba','pregunta_prueba.idprueba','=','prueba.idprueba')
        ->select('prueba.idprueba','prueba.nombre','pregunta_prueba.seccion','pregunta_prueba.idpregunta_prueba','pregunta_prueba.pregunta','pregunta_prueba.orden')
        ->where('prueba.idprueba', $id)
        ->where('pregunta_prueba.activo',Constantes::ACTIVO)
        ->orderBy('pregunta_prueba.orden')
        ->get()->toArray();
    }
    
    function obtenOpciones($ids){
        return OpcionPregunta::leftjoin('prueba_resultado','prueba_resultado.idprueba_resultado', '=', 'opcion_pregunta.idprueba_resultado')        
        ->select('opcion_pregunta.idopcion_pregunta','opcion_pregunta.idpregunta_prueba','opcion_pregunta.opcion','opcion_pregunta.orden','opcion_pregunta.idprueba_resultado',
                'prueba_resultado.resultado','prueba_resultado.idprueba')
        ->whereIn('opcion_pregunta.idpregunta_prueba', $ids)
        ->orderBy('opcion_pregunta.idpregunta_prueba','asc','opcion_pregunta.orden','asc')
        ->get()->toArray();
    }

    function insertaCuestionario($cuestionario){
        $valores      = explode('|', $cuestionario);
        $idResultadoCandidatoPruebaPrimaria = 0;
        $pruebaInterpretacion = array();
        foreach($valores as $indice => $valor){
            if($valor != ''){
                if($indice == 0){
                    $pruebaId = $this->obtenValorCadena($valor, Constantes::ACTIVO);
                    $pruebaInterpretacion = $this->obtenPruebaInterpretacion($pruebaId);
                    $idResultadoCandidatoPruebaPrimaria = $this->insertaResultadoCandidatoPrueba($pruebaInterpretacion);
                }
                $this->insertaPregunta($valor, $idResultadoCandidatoPruebaPrimaria);
            }
        }
    }

    private function calculaTiempoContestado(){
        return ceil(strtotime( date("Y-m-d H:i:s") ) -  strtotime( Session()->get('horaActual') ) / 60);
    }
    
    private function insertaResultadoCandidatoPrueba($pruebaInterpretacion){
        try{
            $perfilPuesto = Session()->get('perfilPuestos');
            $resultado    = new ResultadoCandidatoPrueba();
            $resultado->idprueba_interpretacion = (int) $pruebaInterpretacion['idprueba_interpretacion'];
            $resultado->idcandidato_proyecto = $perfilPuesto['idcandidato_proyecto'];
            $resultado->tiempo = $this->calculaTiempoContestado();
            $resultado->save();
            return $resultado->idresultado_candidato_prueba;
        }
        catch(\Exception $e){
            die("Error:  ".$e->getMessage());
        }
    }
    
    private function insertaPregunta($valor, $idResultadoCandidatoPruebaPrimaria){
        try{
            $idOpcionPregunta = $this->obtenValorCadena($valor, Constantes::ACCION_ACTUALIZAR);
            $detalleResultadoCandidatoPrueba = new DetalleResultadoCandidatoPrueba();
            $detalleResultadoCandidatoPrueba->idopcion_pregunta = $idOpcionPregunta;
            $detalleResultadoCandidatoPrueba->idresultado_candidato_prueba = $idResultadoCandidatoPruebaPrimaria;
            $detalleResultadoCandidatoPrueba->save();
        }
        catch(\Exception $e){
            die("Error:  ".$e->getMessage());
        }
    }
    
    
    private function obtenValorCadena($valor, $indice){
        $valores = explode('-', $valor);
        return $valores[$indice];
    }

}