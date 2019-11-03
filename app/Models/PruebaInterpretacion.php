<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:29 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PruebaInterpretacion
 * 
 * @property int $idprueba_interpretacion
 * @property int $idprueba
 * @property string $resultado
 * @property string $interpretacion
 * 
 * @property \sistema\Models\Prueba $prueba
 * @property \Illuminate\Database\Eloquent\Collection $resultado_candidato_pruebas
 *
 * @package sistema\Models
 */
class PruebaInterpretacion extends Eloquent
{
	protected $table = 'prueba_interpretacion';
	protected $primaryKey = 'idprueba_interpretacion';
	public $timestamps = false;

	protected $casts = [
		'idprueba' => 'int'
	];

	protected $fillable = [
		'idprueba',
		'resultado',
		'interpretacion'
	];

	public function prueba()
	{
		return $this->belongsTo(\sistema\Models\Prueba::class, 'idprueba');
	}

	public function resultado_candidato_pruebas()
	{
		return $this->hasMany(\sistema\Models\ResultadoCandidatoPrueba::class, 'idprueba_interpretacion');
	}
}
