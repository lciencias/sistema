<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:29 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class TipoEjercicio
 * 
 * @property int $idtipo_ejercicio
 * @property string $nombre
 * @property bool $activo
 * @property string $descripcion
 * 
 * @property \Illuminate\Database\Eloquent\Collection $ejercicios
 * @property \Illuminate\Database\Eloquent\Collection $clientes
 *
 * @package sistema\Models
 */
class TipoEjercicio extends Eloquent
{
	protected $table = 'tipo_ejercicio';
	protected $primaryKey = 'idtipo_ejercicio';
	public $timestamps = false;

	protected $casts = [
		'activo' => 'bool'
	];

	protected $fillable = [
		'nombre',
		'activo',
		'descripcion'
	];

	public function ejercicios()
	{
		return $this->hasMany(\sistema\Models\Ejercicio::class, 'idtipo_ejercicio');
	}

	public function clientes()
	{
		return $this->belongsToMany(\sistema\Models\Cliente::class, 'tipo_ejercicio_cliente', 'idtipo_ejercicio', 'idcliente')
					->withPivot('idtipo_ejercicio_cliente', 'activo');
	}
}
