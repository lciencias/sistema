<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:28 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Modulo
 * 
 * @property int $idmodulo
 * @property string $nombre
 * @property string $identificador
 * @property string $recurso
 * @property int $parent
 * @property string $admin
 * @property bool $activo
 * @property string $clase
 * @property int $orden
 * 
 * @property \Illuminate\Database\Eloquent\Collection $bitacoras
 * @property \Illuminate\Database\Eloquent\Collection $permisos
 * @property \Illuminate\Database\Eloquent\Collection $perfils
 * @property \Illuminate\Database\Eloquent\Collection $users
 *
 * @package sistema\Models
 */
class Modulo extends Eloquent
{
	protected $table = 'modulo';
	protected $primaryKey = 'idmodulo';
	public $timestamps = false;

	protected $casts = [
		'parent' => 'int',
		'activo' => 'bool',
		'orden' => 'int'
	];

	protected $fillable = [
		'nombre',
		'identificador',
		'recurso',
		'parent',
		'admin',
		'activo',
		'clase',
		'orden'
	];

	public function bitacoras()
	{
		return $this->hasMany(\sistema\Models\Bitacora::class, 'idmodulo');
	}

	public function permisos()
	{
		return $this->belongsToMany(\sistema\Models\Permiso::class, 'modulo_permiso', 'idmodulo', 'idpermiso')
					->withPivot('id');
	}

	public function perfils()
	{
		return $this->belongsToMany(\sistema\Models\Perfil::class, 'perfil_modulo', 'idmodulo', 'idperfil')
					->withPivot('idperfil_modulo');
	}

	public function users()
	{
		return $this->belongsToMany(\sistema\Models\User::class, 'users_modulo', 'idmodulo', 'iduser')
					->withPivot('iduser_modulo', 'permitido');
	}
}
