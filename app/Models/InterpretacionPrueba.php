<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 30 Sep 2019 22:11:25 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class InterpretacionPrueba
 * 
 * @property int $interpretacion_prueba
 * @property string $concepto
 * @property string $decripcion
 * 
 * @property \Illuminate\Database\Eloquent\Collection $interpretacion_respuestas
 *
 * @package sistema\Models
 */
class InterpretacionPrueba extends Eloquent
{
	protected $table = 'interpretacion_prueba';
	protected $primaryKey = 'interpretacion_prueba';
	public $timestamps = false;

	protected $fillable = [
		'concepto',
		'decripcion'
	];

	public function interpretacion_respuestas()
	{
		return $this->hasMany(\sistema\Models\InterpretacionRespuesta::class, 'interpretacion_prueba');
	}
}
