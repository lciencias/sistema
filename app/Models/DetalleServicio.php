<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:28 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class DetalleServicio
 * 
 * @property int $iddetalle_servicio
 * @property int $idservicio
 * @property int $idprueba
 * @property int $no_pruebas
 * @property int $no_usadas
 * @property int $no_sin_usar
 * @property \Carbon\Carbon $fecha_vencimiento
 * 
 * @property \sistema\Models\Prueba $prueba
 * @property \sistema\Models\Servicio $servicio
 *
 * @package sistema\Models
 */
class DetalleServicio extends Eloquent
{
	protected $table = 'detalle_servicio';
	protected $primaryKey = 'iddetalle_servicio';
	public $timestamps = false;

	protected $casts = [
		'idservicio' => 'int',
		'idprueba' => 'int',
		'no_pruebas' => 'int',
		'no_usadas' => 'int',
		'no_sin_usar' => 'int'
	];

	protected $dates = [
		'fecha_vencimiento'
	];

	protected $fillable = [
		'idservicio',
		'idprueba',
		'no_pruebas',
		'no_usadas',
		'no_sin_usar',
		'fecha_vencimiento'
	];

	public function prueba()
	{
		return $this->belongsTo(\sistema\Models\Prueba::class, 'idprueba');
	}

	public function servicio()
	{
		return $this->belongsTo(\sistema\Models\Servicio::class, 'idservicio');
	}
}
