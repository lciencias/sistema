<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:29 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PerfilModulo
 * 
 * @property int $idperfil_modulo
 * @property int $idperfil
 * @property int $idmodulo
 * 
 * @property \sistema\Models\Modulo $modulo
 * @property \sistema\Models\Perfil $perfil
 * @property \Illuminate\Database\Eloquent\Collection $permisos
 *
 * @package sistema\Models
 */
class PerfilModulo extends Eloquent
{
	protected $table = 'perfil_modulo';
	protected $primaryKey = 'idperfil_modulo';
	public $timestamps = false;

	protected $casts = [
		'idperfil' => 'int',
		'idmodulo' => 'int'
	];

	protected $fillable = [
		'idperfil',
		'idmodulo'
	];

	public function modulo()
	{
		return $this->belongsTo(\sistema\Models\Modulo::class, 'idmodulo');
	}

	public function perfil()
	{
		return $this->belongsTo(\sistema\Models\Perfil::class, 'idperfil');
	}

	public function permisos()
	{
		return $this->belongsToMany(\sistema\Models\Permiso::class, 'perfil_modulo_permiso', 'idperfil_modulo', 'idpermiso')
					->withPivot('idperfil_modulo_permiso');
	}
}
