<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:29 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PruebaResultado
 * 
 * @property int $idprueba_resultado
 * @property int $idprueba
 * @property string $resultado
 * @property string $descripcion
 * 
 * @property \sistema\Models\Prueba $prueba
 * @property \Illuminate\Database\Eloquent\Collection $opcion_pregunta
 *
 * @package sistema\Models
 */
class PruebaResultado extends Eloquent
{
	protected $table = 'prueba_resultado';
	protected $primaryKey = 'idprueba_resultado';
	public $timestamps = false;

	protected $casts = [
		'idprueba' => 'int'
	];

	protected $fillable = [
		'idprueba',
		'resultado',
		'descripcion'
	];

	public function prueba()
	{
		return $this->belongsTo(\sistema\Models\Prueba::class, 'idprueba');
	}

	public function opcion_pregunta()
	{
		return $this->hasMany(\sistema\Models\OpcionPregunta::class, 'idprueba_resultado');
	}
}
