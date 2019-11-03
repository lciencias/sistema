<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 06 Oct 2019 12:42:44 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class EscalaCalificacionCompetenca
 * 
 * @property int $idescala_calificacion_competencia
 * @property string $descripcion
 * @property float $rango_minimo
 * @property float $ramgo_maximo
 * 
 * @property \Illuminate\Database\Eloquent\Collection $calificacion_comportamientos
 *
 * @package sistema\Models
 */
class EscalaCalificacionCompetencia extends Eloquent
{
	protected $primaryKey = 'idescala_calificacion_competencia';
	public $timestamps = false;

	protected $casts = [
		'rango_minimo' => 'float',
		'ramgo_maximo' => 'float'
	];

	protected $fillable = [
		'descripcion',
		'rango_minimo',
		'ramgo_maximo'
	];

	public function calificacion_comportamientos()
	{
		return $this->hasMany(\sistema\Models\CalificacionComportamiento::class, 'idescala_calificacion_competencia');
	}
}
