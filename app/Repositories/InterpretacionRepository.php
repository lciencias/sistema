<?php namespace sistema\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use sistema\Policies\Constantes;
use sistema\Repositories\Eloquent\Repository;
use sistema\Models\PruebaInterpretacion;

class InterpretacionRepository extends Repository {

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    private $campoBase = "idprueba_interpretacion";
    private $controller = Constantes::CONTROLLER_INTERPRETACION;
	
    function model()
    {
        return 'sistema\Models\PruebaInterpretacion';
    }
    
    function recuperaInterpretacionCandidato(){
        return DB::table("prueba_interpretacion")
        ->join('resultado_candidato_prueba','resultado_candidato_prueba.idprueba_interpretacion', '=', 'prueba_interpretacion.idprueba_interpretacion')
        ->join('candidato_proyecto','candidato_proyecto.idcandidato_proyecto', '=', 'resultado_candidato_prueba.idcandidato_proyecto')
        ->join('prueba','prueba.idprueba','=','prueba_interpretacion.idprueba')
        ->join('perfil_puesto','perfil_puesto.idperfil_puesto','=','candidato_proyecto.idperfil_puesto')
        ->join('candidato','candidato.idcandidato','=','candidato_proyecto.idcandidato')
        ->select('prueba_interpretacion.idprueba_interpretacion','prueba_interpretacion.idprueba','prueba_interpretacion.resultado','prueba_interpretacion.interpretacion',
               'candidato.nombre as candidato', 'candidato.paterno', 'candidato.materno','prueba.nombre as prueba','perfil_puesto.nombre as perfil')
        ->where('candidato.iduser',12)
        ->get()->toArray();
    }
    
}