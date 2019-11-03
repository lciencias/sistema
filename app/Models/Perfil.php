<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:29 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Perfil
 * 
 * @property int $idempresa
 * @property int $idperfil
 * @property string $nombre
 * @property bool $activo
 * @property string $descripcion
 * 
 * @property \sistema\Models\Empresa $empresa
 * @property \Illuminate\Database\Eloquent\Collection $modulos
 * @property \Illuminate\Database\Eloquent\Collection $users
 *
 * @package sistema\Models
 */
class Perfil extends Eloquent
{
	protected $table = 'perfil';
	protected $primaryKey = 'idperfil';
	public $timestamps = false;

	protected $casts = [
		'idempresa' => 'int',
		'activo' => 'bool'
	];

	protected $fillable = [
		'idempresa',
		'nombre',
		'activo',
		'descripcion'
	];

	public function empresa()
	{
		return $this->belongsTo(\sistema\Models\Empresa::class, 'idempresa');
	}

	public function modulos()
	{
		return $this->belongsToMany(\sistema\Models\Modulo::class, 'perfil_modulo', 'idperfil', 'idmodulo')
					->withPivot('idperfil_modulo');
	}

	public function users()
	{
		return $this->hasMany(\sistema\Models\User::class, 'idperfil');
	}
}
