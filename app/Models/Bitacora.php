<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:28 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Bitacora
 * 
 * @property int $id
 * @property int $iduser
 * @property int $idmodulo
 * @property string $nombre_modulo
 * @property \Carbon\Carbon $fecha
 * @property int $idregistro
 * @property string $nombre_registro
 * @property string $tipo_movimiento
 * @property string $estado_anterior
 * @property string $estado_despues
 * @property string $ip
 * 
 * @property \sistema\Models\Modulo $modulo
 * @property \sistema\Models\User $user
 *
 * @package sistema\Models
 */
class Bitacora extends Eloquent
{
	protected $table = 'bitacora';
	public $timestamps = false;

	protected $casts = [
		'iduser' => 'int',
		'idmodulo' => 'int',
		'idregistro' => 'int'
	];

	protected $dates = [
		'fecha'
	];

	protected $fillable = [
		'iduser',
		'idmodulo',
		'nombre_modulo',
		'fecha',
		'idregistro',
		'nombre_registro',
		'tipo_movimiento',
		'estado_anterior',
		'estado_despues',
		'ip'
	];

	public function modulo()
	{
		return $this->belongsTo(\sistema\Models\Modulo::class, 'idmodulo');
	}

	public function user()
	{
		return $this->belongsTo(\sistema\Models\User::class, 'iduser');
	}
}
