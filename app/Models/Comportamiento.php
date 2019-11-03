<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:28 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Comportamiento
 * 
 * @property int $idcomportamiento
 * @property int $idcompetencia
 * @property string $nombre
 * @property bool $activo
 * @property string $descripcion
 * 
 * @property \sistema\Models\Competencium $competencium
 * @property \Illuminate\Database\Eloquent\Collection $calificacion_comportamientos
 * @property \Illuminate\Database\Eloquent\Collection $detalle_resultado_candidato_ejercicios
 * @property \Illuminate\Database\Eloquent\Collection $tipo_ejercicio_clientes
 *
 * @package sistema\Models
 */
class Comportamiento extends Eloquent
{
	protected $table = 'comportamiento';
	protected $primaryKey = 'idcomportamiento';
	public $timestamps = false;

	protected $casts = [
		'idcompetencia' => 'int',
		'activo' => 'bool'
	];

	protected $fillable = [
		'idcompetencia',
		'nombre',
		'activo',
		'descripcion'
	];

	public function competencium()
	{
		return $this->belongsTo(\sistema\Models\Competencia::class, 'idcompetencia');
	}

	public function calificacion_comportamientos()
	{
		return $this->hasMany(\sistema\Models\CalificacionComportamiento::class, 'idcomportamiento');
	}

	public function detalle_resultado_candidato_ejercicios()
	{
		return $this->hasMany(\sistema\Models\DetalleResultadoCandidatoEjercicio::class, 'idcomportamiento');
	}

	public function tipo_ejercicio_clientes()
	{
		return $this->belongsToMany(\sistema\Models\TipoEjercicioCliente::class, 'tipo_ejercicio_cliente_comportamiento', 'idcomportamiento', 'idtipo_ejercicio_cliente')
					->withPivot('idtipo_ejercicio_cliente_comportamiento');
	}
}
