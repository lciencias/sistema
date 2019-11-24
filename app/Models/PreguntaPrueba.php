<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:29 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PreguntaPrueba
 * 
 * @property int $idpregunta_prueba
 * @property int $idprueba
 * @property string $pregunta
 * @property int $orden
 * @property bool $activo
 * 
 * @property \sistema\Models\Prueba $prueba
 * @property \Illuminate\Database\Eloquent\Collection $opcion_pregunta
 *
 * @package sistema\Models
 */
class PreguntaPrueba extends Eloquent
{
	protected $table = 'pregunta_prueba';
	protected $primaryKey = 'idpregunta_prueba';
	public $timestamps = false;

	protected $casts = [
		'idprueba' => 'int',
		'orden' => 'int',
		'activo' => 'bool'
	];

	protected $fillable = [
		'idprueba',
		'pregunta',
		'orden',
		'activo'
	];

	public function prueba()
	{
		return $this->belongsTo(\sistema\Models\Prueba::class, 'idprueba');
	}

	public function opcion_pregunta()
	{
		return $this->hasMany(\sistema\Models\OpcionPregunta::class, 'idpregunta_prueba');
	}
}
