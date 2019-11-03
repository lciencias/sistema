<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:28 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Empresa
 * 
 * @property int $idempresa
 * @property string $nombre
 * @property string $direccion
 * @property bool $activo
 * @property string $nombre_representante
 * @property string $paterno_representante
 * @property string $materno_representante
 * @property string $email_representante
 * @property string $rfc
 * @property string $razon_social
 * @property string $logotipo
 * 
 * @property \Illuminate\Database\Eloquent\Collection $perfils
 * @property \Illuminate\Database\Eloquent\Collection $users
 *
 * @package sistema\Models
 */
class Empresa extends Eloquent
{
	protected $table = 'empresa';
	protected $primaryKey = 'idempresa';
	public $timestamps = false;

	protected $casts = [
		'activo' => 'bool'
	];

	protected $fillable = [
		'nombre',
		'direccion',
		'activo',
		'nombre_representante',
		'paterno_representante',
		'materno_representante',
		'email_representante',
		'rfc',
		'razon_social',
		'logotipo'
	];

	public function perfils()
	{
		return $this->hasMany(\sistema\Models\Perfil::class, 'idempresa');
	}

	public function users()
	{
		return $this->hasMany(\sistema\Models\User::class, 'idempresa');
	}
}
