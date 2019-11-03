<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:29 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class TipoEjercicioClienteComportamiento
 * 
 * @property int $idtipo_ejercicio_cliente_comportamiento
 * @property int $idcomportamiento
 * @property int $idtipo_ejercicio_cliente
 * 
 * @property \sistema\Models\Comportamiento $comportamiento
 * @property \sistema\Models\TipoEjercicioCliente $tipo_ejercicio_cliente
 *
 * @package sistema\Models
 */
class TipoEjercicioClienteComportamiento extends Eloquent
{
	protected $table = 'tipo_ejercicio_cliente_comportamiento';
	protected $primaryKey = 'idtipo_ejercicio_cliente_comportamiento';
	public $timestamps = false;

	protected $casts = [
		'idcomportamiento' => 'int',
		'idtipo_ejercicio_cliente' => 'int'
	];

	protected $fillable = [
		'idcomportamiento',
		'idtipo_ejercicio_cliente'
	];

	public function comportamiento()
	{
		return $this->belongsTo(\sistema\Models\Comportamiento::class, 'idcomportamiento');
	}

	public function tipo_ejercicio_cliente()
	{
		return $this->belongsTo(\sistema\Models\TipoEjercicioCliente::class, 'idtipo_ejercicio_cliente');
	}
}
