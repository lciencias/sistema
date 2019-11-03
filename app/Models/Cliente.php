<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:28 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Cliente
 * 
 * @property int $idcliente
 * @property int $iddireccion_fiscal
 * @property int $iddireccion_comercial
 * @property string $nombre_comercial
 * @property string $razon_social
 * @property string $rfc
 * @property bool $activo
 * @property string $puesto_responsable
 * @property string $area_responsable
 * @property string $tel_cel_responsable
 * @property string $tel_oficina_responsable
 * @property string $ext_tel_responsable
 * @property string $nombre_admon
 * @property string $email_admon
 * @property string $tel_cel_admon
 * @property string $tel_oficina_admon
 * @property string $ext_tel_admon
 * @property string $paterno_admon
 * @property string $materno_admon
 * 
 * @property \sistema\Models\Direccion $direccion
 * @property \Illuminate\Database\Eloquent\Collection $candidatos
 * @property \Illuminate\Database\Eloquent\Collection $perfil_puestos
 * @property \Illuminate\Database\Eloquent\Collection $proyectos
 * @property \Illuminate\Database\Eloquent\Collection $servicios
 * @property \Illuminate\Database\Eloquent\Collection $tipo_ejercicios
 * @property \Illuminate\Database\Eloquent\Collection $users
 *
 * @package sistema\Models
 */
class Cliente extends Eloquent
{
	protected $table = 'cliente';
	protected $primaryKey = 'idcliente';
	public $timestamps = false;

	protected $casts = [
		'iddireccion_fiscal' => 'int',
		'iddireccion_comercial' => 'int',
		'activo' => 'bool'
	];

	protected $fillable = [
		'iddireccion_fiscal',
		'iddireccion_comercial',
		'nombre_comercial',
		'razon_social',
		'rfc',
		'activo',
		'puesto_responsable',
		'area_responsable',
		'tel_cel_responsable',
		'tel_oficina_responsable',
		'ext_tel_responsable',
		'nombre_admon',
		'email_admon',
		'tel_cel_admon',
		'tel_oficina_admon',
		'ext_tel_admon',
		'paterno_admon',
		'materno_admon'
	];

	public function direccionComercial()
	{
		return $this->belongsTo(\sistema\Models\Direccion::class, 'iddireccion_comercial');
	}
	
	public function direccionFiscal()
	{
	    return $this->belongsTo(\sistema\Models\Direccion::class, 'iddireccion_fiscal');
	}

	public function candidatos()
	{
		return $this->hasMany(\sistema\Models\Candidato::class, 'idcliente');
	}

	public function perfil_puestos()
	{
		return $this->hasMany(\sistema\Models\PerfilPuesto::class, 'idcliente');
	}

	public function proyectos()
	{
		return $this->hasMany(\sistema\Models\Proyecto::class, 'idcliente');
	}

	public function servicios()
	{
		return $this->hasMany(\sistema\Models\Servicio::class, 'idcliente');
	}

	public function tipo_ejercicios()
	{
		return $this->belongsToMany(\sistema\Models\TipoEjercicio::class, 'tipo_ejercicio_cliente', 'idcliente', 'idtipo_ejercicio')
					->withPivot('idtipo_ejercicio_cliente', 'activo');
	}

	public function users()
	{
		return $this->hasMany(\sistema\Models\User::class, 'idcliente');
	}
}
