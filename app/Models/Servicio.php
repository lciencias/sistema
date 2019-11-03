<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:29 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Servicio
 * 
 * @property int $idcliente
 * @property int $idservicio
 * @property \Carbon\Carbon $fecha
 * 
 * @property \sistema\Models\Cliente $cliente
 * @property \Illuminate\Database\Eloquent\Collection $detalle_servicios
 *
 * @package sistema\Models
 */
class Servicio extends Eloquent
{
	protected $table = 'servicio';
	protected $primaryKey = 'idservicio';
	public $timestamps = false;

	protected $casts = [
		'idcliente' => 'int'
	];

	protected $dates = [
		'fecha'
	];

	protected $fillable = [
		'idcliente',
		'fecha'
	];

	public function cliente()
	{
		return $this->belongsTo(\sistema\Models\Cliente::class, 'idcliente');
	}

	public function detalle_servicios()
	{
		return $this->hasMany(\sistema\Models\DetalleServicio::class, 'idservicio');
	}
}
