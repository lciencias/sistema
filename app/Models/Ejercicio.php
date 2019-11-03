<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:28 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Ejercicio
 * 
 * @property int $idtipo_ejercicio
 * @property int $idejercicio
 * @property string $nombre
 * @property bool $activo
 * @property string $descripcion
 * 
 * @property \sistema\Models\TipoEjercicio $tipo_ejercicio
 * @property \Illuminate\Database\Eloquent\Collection $candidato_proyecto_ejercicios
 *
 * @package sistema\Models
 */
class Ejercicio extends Eloquent
{
	protected $table = 'ejercicio';
	protected $primaryKey = 'idejercicio';
	public $timestamps = false;

	protected $casts = [
		'idtipo_ejercicio' => 'int',
		'activo' => 'bool'
	];

	protected $fillable = [
		'idtipo_ejercicio',
		'nombre',
		'activo',
		'descripcion'
	];

	public function tipo_ejercicio()
	{
		return $this->belongsTo(\sistema\Models\TipoEjercicio::class, 'idtipo_ejercicio');
	}

	public function candidato_proyecto_ejercicios()
	{
		return $this->hasMany(\sistema\Models\CandidatoProyectoEjercicio::class, 'idejercicio');
	}
}
