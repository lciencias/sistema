<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:29 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PerfilModuloPermiso
 * 
 * @property int $idperfil_modulo_permiso
 * @property int $idperfil_modulo
 * @property int $idpermiso
 * 
 * @property \sistema\Models\Permiso $permiso
 * @property \sistema\Models\PerfilModulo $perfil_modulo
 *
 * @package sistema\Models
 */
class PerfilModuloPermiso extends Eloquent
{
	protected $table = 'perfil_modulo_permiso';
	protected $primaryKey = 'idperfil_modulo_permiso';
	public $timestamps = false;

	protected $casts = [
		'idperfil_modulo' => 'int',
		'idpermiso' => 'int'
	];

	protected $fillable = [
		'idperfil_modulo',
		'idpermiso'
	];

	public function permiso()
	{
		return $this->belongsTo(\sistema\Models\Permiso::class, 'idpermiso');
	}

	public function perfil_modulo()
	{
		return $this->belongsTo(\sistema\Models\PerfilModulo::class, 'idperfil_modulo');
	}
}
