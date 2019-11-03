<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:28 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class CandidatoProyectoEjercicio
 * 
 * @property int $idcandidato_proyecto_ejercicio
 * @property int $idejercicio
 * 
 * @property \sistema\Models\Ejercicio $ejercicio
 * @property \Illuminate\Database\Eloquent\Collection $resultado_candidato_ejercicios
 *
 * @package sistema\Models
 */
class CandidatoProyectoEjercicio extends Eloquent
{
	protected $table = 'candidato_proyecto_ejercicio';
	protected $primaryKey = 'idcandidato_proyecto_ejercicio';
	public $timestamps = false;

	protected $casts = [
		'idejercicio' => 'int'
	];

	protected $fillable = [
		'idejercicio'
	];

	public function ejercicio()
	{
		return $this->belongsTo(\sistema\Models\Ejercicio::class, 'idejercicio');
	}

	public function resultado_candidato_ejercicios()
	{
		return $this->hasMany(\sistema\Models\ResultadoCandidatoEjercicio::class, 'idcandidato_proyecto_ejercicio');
	}
}
