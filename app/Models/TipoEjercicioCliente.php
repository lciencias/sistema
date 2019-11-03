<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:29 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class TipoEjercicioCliente
 * 
 * @property int $idtipo_ejercicio_cliente
 * @property int $idtipo_ejercicio
 * @property int $idcliente
 * @property bool $activo
 * 
 * @property \sistema\Models\TipoEjercicio $tipo_ejercicio
 * @property \sistema\Models\Cliente $cliente
 * @property \Illuminate\Database\Eloquent\Collection $comportamientos
 *
 * @package sistema\Models
 */
class TipoEjercicioCliente extends Eloquent
{
	protected $table = 'tipo_ejercicio_cliente';
	protected $primaryKey = 'idtipo_ejercicio_cliente';
	public $timestamps = false;

	protected $casts = [
		'idtipo_ejercicio' => 'int',
		'idcliente' => 'int',
		'activo' => 'bool'
	];

	protected $fillable = [
		'idtipo_ejercicio',
		'idcliente',
		'activo'
	];

	public function tipo_ejercicio()
	{
		return $this->belongsTo(\sistema\Models\TipoEjercicio::class, 'idtipo_ejercicio');
	}

	public function cliente()
	{
		return $this->belongsTo(\sistema\Models\Cliente::class, 'idcliente');
	}

	public function comportamientos()
	{
		return $this->belongsToMany(\sistema\Models\Comportamiento::class, 'tipo_ejercicio_cliente_comportamiento', 'idtipo_ejercicio_cliente', 'idcomportamiento')
					->withPivot('idtipo_ejercicio_cliente_comportamiento');
	}
}
