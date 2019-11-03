<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:29 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Permiso
 * 
 * @property int $idpermiso
 * @property string $nombre
 * @property string $tipo
 * 
 * @property \Illuminate\Database\Eloquent\Collection $modulos
 * @property \Illuminate\Database\Eloquent\Collection $perfil_modulos
 * @property \Illuminate\Database\Eloquent\Collection $users_modulos
 *
 * @package sistema\Models
 */
class Permiso extends Eloquent
{
	protected $table = 'permiso';
	protected $primaryKey = 'idpermiso';
	public $timestamps = false;

	protected $fillable = [
		'nombre',
		'tipo'
	];

	public function modulos()
	{
		return $this->belongsToMany(\sistema\Models\Modulo::class, 'modulo_permiso', 'idpermiso', 'idmodulo')
					->withPivot('id');
	}

	public function perfil_modulos()
	{
		return $this->belongsToMany(\sistema\Models\PerfilModulo::class, 'perfil_modulo_permiso', 'idpermiso', 'idperfil_modulo')
					->withPivot('idperfil_modulo_permiso');
	}

	public function users_modulos()
	{
		return $this->belongsToMany(\sistema\Models\UsersModulo::class, 'users_modulo_permiso', 'idpermiso', 'iduser_modulo')
					->withPivot('iduser_modulo_permiso', 'permitido');
	}
}
