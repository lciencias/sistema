<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:28 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Candidato
 * 
 * @property int $idcandidato
 * @property int $iduser
 * @property int $iddireccion
 * @property int $idestado_nacimiento
 * @property int $idarchivo_cv
 * @property string $nombre
 * @property string $paterno
 * @property string $materno
 * @property bool $activo
 * @property \Carbon\Carbon $fecha_nacimiento
 * @property string $genero
 * @property string $estado_civil
 * @property string $nacionalidad
 * @property string $curp
 * @property string $rfc
 * @property string $nss
 * @property string $referencia
 * @property bool $viaje
 * @property bool $cambio_residencia
 * @property string $uso_manos
 * @property string $alergias
 * @property string $tipo_sangre
 * @property \Carbon\Carbon $fecha_registro
 * @property \Carbon\Carbon $fecha_actualizacion
 * @property int $idcliente
 * @property string $no_empleado
 * @property int $parentesco_avisa
 * 
 * @property \sistema\Models\Cliente $cliente
 * @property \sistema\Models\Archivo $archivo
 * @property \sistema\Models\User $user
 * @property \sistema\Models\Direccion $direccion
 * @property \sistema\Models\Estado $estado
 * @property \Illuminate\Database\Eloquent\Collection $archivos
 * @property \Illuminate\Database\Eloquent\Collection $proyectos
 * @property \Illuminate\Database\Eloquent\Collection $familiar_candidatos
 * @property \Illuminate\Database\Eloquent\Collection $nombramientos
 * @property \Illuminate\Database\Eloquent\Collection $telefono_candidatos
 *
 * @package sistema\Models
 */
class Candidato extends Eloquent
{
	protected $table = 'candidato';
	protected $primaryKey = 'idcandidato';
	public $timestamps = false;

	protected $casts = [
		'iduser' => 'int',
		'iddireccion' => 'int',
		'idestado_nacimiento' => 'int',
		'idarchivo_cv' => 'int',
		'activo' => 'bool',
		'viaje' => 'bool',
		'cambio_residencia' => 'bool',
		'idcliente' => 'int',
		'parentesco_avisa' => 'int'
	];

	protected $dates = [
		'fecha_nacimiento',
		'fecha_registro',
		'fecha_actualizacion'
	];

	protected $fillable = [
		'iduser',
		'iddireccion',
		'idestado_nacimiento',
		'idarchivo_cv',
		'nombre',
		'paterno',
		'materno',
		'activo',
		'fecha_nacimiento',
		'genero',
		'estado_civil',
		'nacionalidad',
		'curp',
		'rfc',
		'nss',
		'referencia',
		'viaje',
		'cambio_residencia',
		'uso_manos',
		'alergias',
		'tipo_sangre',
		'fecha_registro',
		'fecha_actualizacion',
		'idcliente',
		'no_empleado',
		'parentesco_avisa'
	];

	public function cliente()
	{
		return $this->belongsTo(\sistema\Models\Cliente::class, 'idcliente');
	}

	public function archivo()
	{
		return $this->belongsTo(\sistema\Models\Archivo::class, 'idarchivo_cv');
	}

	public function user()
	{
		return $this->belongsTo(\sistema\Models\User::class, 'iduser');
	}

	public function direccion()
	{
		return $this->belongsTo(\sistema\Models\Direccion::class, 'iddireccion');
	}

	public function estado()
	{
		return $this->belongsTo(\sistema\Models\Estado::class, 'idestado_nacimiento');
	}

	public function archivos()
	{
		return $this->belongsToMany(\sistema\Models\Archivo::class, 'archivo_candidato', 'idcandidato', 'idarchivo')
					->withPivot('idarchivo_candidato', 'nombre');
	}

	public function proyectos()
	{
		return $this->belongsToMany(\sistema\Models\Proyecto::class, 'candidato_proyecto', 'idcandidato', 'idproyecto')
					->withPivot('idcandidato_proyecto', 'idperfil_puesto', 'fecha_registro');
	}

	public function familiar_candidatos()
	{
		return $this->hasMany(\sistema\Models\FamiliarCandidato::class, 'idcandidato');
	}

	public function nombramientos()
	{
		return $this->hasMany(\sistema\Models\Nombramiento::class, 'idcandidato');
	}

	public function telefono_candidatos()
	{
		return $this->hasMany(\sistema\Models\TelefonoCandidato::class, 'idcandidato');
	}
}
