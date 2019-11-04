<?php 
namespace sistema\Repositories;

use Illuminate\Support\Facades\Session;
use sistema\Repositories\Eloquent\Repository;
use sistema\Models\Candidato;
use sistema\Models\PerfilPuestoPrueba;
use sistema\Models\Prueba;
use sistema\Policies\Constantes;
use sistema\Models\OpcionPregunta;


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
        ->select('candidato_proyecto.idperfil_puesto','candidato_proyecto.idproyecto','candidato_proyecto.idcandidato')
        ->where('candidato.iduser',Session::get('idUser'))
        ->get()->toArray());
    }
    
    function obtenPuestos($perfilPuesto){
       return PerfilPuestoPrueba::join('prueba','prueba.idprueba','=','perfil_puesto_pruebas.idprueba')
        ->where('perfil_puesto_pruebas.idperfil_puesto',$perfilPuesto['idperfil_puesto'])
        ->get();        
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
                'prueba_resultado.resultado')
        ->whereIn('opcion_pregunta.idpregunta_prueba', $ids)
        ->orderBy('opcion_pregunta.idpregunta_prueba','asc','opcion_pregunta.orden','asc')
        ->get()->toArray();
    }       	
}