<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:27 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Acceso
 * 
 * @property int $idacceso
 * @property string $usuario
 * @property \Carbon\Carbon $fecha
 * @property string $status
 * @property string $ip
 * @property string $explorador
 * @property string $so
 * @property int $idusuario
 * 
 * @property \sistema\Models\User $user
 *
 * @package sistema\Models
 */
class Acceso extends Eloquent
{
	protected $table = 'acceso';
	protected $primaryKey = 'idacceso';
	public $timestamps = false;

	protected $casts = [
		'idusuario' => 'int'
	];

	protected $dates = [
		'fecha'
	];

	protected $fillable = [
		'usuario',
		'fecha',
		'status',
		'ip',
		'explorador',
		'so',
		'idusuario'
	];

	public function user()
	{
		return $this->belongsTo(\sistema\Models\User::class, 'idusuario');
	}
}
