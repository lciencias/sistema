<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:29 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ResultadoCandidatoEjercicio
 * 
 * @property int $idresultado_candidato_ejercicio
 * @property int $idcompetencia
 * @property int $idcandidato_proyecto_ejercicio
 * @property float $calificacion_final
 * 
 * @property \sistema\Models\Competencium $competencium
 * @property \sistema\Models\CandidatoProyectoEjercicio $candidato_proyecto_ejercicio
 * @property \Illuminate\Database\Eloquent\Collection $detalle_resultado_candidato_ejercicios
 *
 * @package sistema\Models
 */
class ResultadoCandidatoEjercicio extends Eloquent
{
	protected $table = 'resultado_candidato_ejercicio';
	protected $primaryKey = 'idresultado_candidato_ejercicio';
	public $timestamps = false;

	protected $casts = [
		'idcompetencia' => 'int',
		'idcandidato_proyecto_ejercicio' => 'int',
		'calificacion_final' => 'float'
	];

	protected $fillable = [
		'idcompetencia',
		'idcandidato_proyecto_ejercicio',
		'calificacion_final'
	];

	public function competencium()
	{
		return $this->belongsTo(\sistema\Models\Competencium::class, 'idcompetencia');
	}

	public function candidato_proyecto_ejercicio()
	{
		return $this->belongsTo(\sistema\Models\CandidatoProyectoEjercicio::class, 'idcandidato_proyecto_ejercicio');
	}

	public function detalle_resultado_candidato_ejercicios()
	{
		return $this->hasMany(\sistema\Models\DetalleResultadoCandidatoEjercicio::class, 'idresultado_candidato_ejercicio');
	}
}
