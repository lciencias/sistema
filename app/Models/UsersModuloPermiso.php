<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:29 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class UsersModuloPermiso
 * 
 * @property int $iduser_modulo_permiso
 * @property int $iduser_modulo
 * @property int $idpermiso
 * @property int $permitido
 * 
 * @property \sistema\Models\Permiso $permiso
 * @property \sistema\Models\UsersModulo $users_modulo
 *
 * @package sistema\Models
 */
class UsersModuloPermiso extends Eloquent
{
	protected $table = 'users_modulo_permiso';
	protected $primaryKey = 'iduser_modulo_permiso';
	public $timestamps = false;

	protected $casts = [
		'iduser_modulo' => 'int',
		'idpermiso' => 'int',
		'permitido' => 'int'
	];

	protected $fillable = [
		'iduser_modulo',
		'idpermiso',
		'permitido'
	];

	public function permiso()
	{
		return $this->belongsTo(\sistema\Models\Permiso::class, 'idpermiso');
	}

	public function users_modulo()
	{
		return $this->belongsTo(\sistema\Models\UsersModulo::class, 'iduser_modulo');
	}
}
