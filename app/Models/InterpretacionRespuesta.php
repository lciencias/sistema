<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 30 Sep 2019 22:11:26 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class InterpretacionRespuesta
 * 
 * @property int $idinterpretacion_respuestas
 * @property int $interpretacion_prueba
 * @property int $idopcion_pregunta
 * 
 * @property \sistema\Models\OpcionPreguntum $opcion_preguntum
 *
 * @package sistema\Models
 */
class InterpretacionRespuesta extends Eloquent
{
	protected $primaryKey = 'idinterpretacion_respuestas';
	public $timestamps = false;

	protected $casts = [
		'interpretacion_prueba' => 'int',
		'idopcion_pregunta' => 'int'
	];

	protected $fillable = [
		'interpretacion_prueba',
		'idopcion_pregunta'
	];

	public function interpretacion_prueba()
	{
		return $this->belongsTo(\sistema\Models\InterpretacionPrueba::class, 'interpretacion_prueba');
	}

	public function opcion_preguntum()
	{
		return $this->belongsTo(\sistema\Models\OpcionPregunta::class, 'idopcion_pregunta');
	}
}
