<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:29 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class User
 * 
 * @property int $id
 * @property int $idcliente
 * @property int $idperfil
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property bool $activo
 * @property bool $dummy
 * @property string $paterno
 * @property string $materno
 * @property int $idempresa
 * 
 * @property \sistema\Models\Cliente $cliente
 * @property \sistema\Models\Empresa $empresa
 * @property \sistema\Models\Perfil $perfil
 * @property \Illuminate\Database\Eloquent\Collection $accesos
 * @property \Illuminate\Database\Eloquent\Collection $bitacoras
 * @property \Illuminate\Database\Eloquent\Collection $candidatos
 * @property \Illuminate\Database\Eloquent\Collection $detalle_resultado_candidato_ejercicios
 * @property \Illuminate\Database\Eloquent\Collection $modulos
 *
 * @package sistema\Models
 */
class User extends Eloquent
{
	protected $casts = [
		'idcliente' => 'int',
		'idperfil' => 'int',
		'activo' => 'bool',
		'dummy' => 'bool',
		'idempresa' => 'int'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'idcliente',
		'idperfil',
		'name',
		'email',
		'password',
		'remember_token',
		'activo',
		'dummy',
		'paterno',
		'materno',
		'idempresa'
	];

	public function cliente()
	{
		return $this->belongsTo(\sistema\Models\Cliente::class, 'idcliente');
	}

	public function empresa()
	{
		return $this->belongsTo(\sistema\Models\Empresa::class, 'idempresa');
	}

	public function perfil()
	{
		return $this->belongsTo(\sistema\Models\Perfil::class, 'idperfil');
	}

	public function accesos()
	{
		return $this->hasMany(\sistema\Models\Acceso::class, 'idusuario');
	}

	public function bitacoras()
	{
		return $this->hasMany(\sistema\Models\Bitacora::class, 'iduser');
	}

	public function candidatos()
	{
		return $this->hasMany(\sistema\Models\Candidato::class, 'iduser');
	}

	public function detalle_resultado_candidato_ejercicios()
	{
		return $this->hasMany(\sistema\Models\DetalleResultadoCandidatoEjercicio::class, 'iduser');
	}

	public function modulos()
	{
		return $this->belongsToMany(\sistema\Models\Modulo::class, 'users_modulo', 'iduser', 'idmodulo')
					->withPivot('iduser_modulo', 'permitido');
	}
}
