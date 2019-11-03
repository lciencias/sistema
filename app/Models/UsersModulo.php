<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:29 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class UsersModulo
 * 
 * @property int $iduser_modulo
 * @property int $iduser
 * @property int $idmodulo
 * @property bool $permitido
 * 
 * @property \sistema\Models\Modulo $modulo
 * @property \sistema\Models\User $user
 * @property \Illuminate\Database\Eloquent\Collection $permisos
 *
 * @package sistema\Models
 */
class UsersModulo extends Eloquent
{
	protected $table = 'users_modulo';
	protected $primaryKey = 'iduser_modulo';
	public $timestamps = false;

	protected $casts = [
		'iduser' => 'int',
		'idmodulo' => 'int',
		'permitido' => 'bool'
	];

	protected $fillable = [
		'iduser',
		'idmodulo',
		'permitido'
	];

	public function modulo()
	{
		return $this->belongsTo(\sistema\Models\Modulo::class, 'idmodulo');
	}

	public function user()
	{
		return $this->belongsTo(\sistema\Models\User::class, 'iduser');
	}

	public function permisos()
	{
		return $this->belongsToMany(\sistema\Models\Permiso::class, 'users_modulo_permiso', 'iduser_modulo', 'idpermiso')
					->withPivot('iduser_modulo_permiso', 'permitido');
	}
}
