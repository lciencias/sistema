<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 03 Oct 2019 22:09:36 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class OpcionPregunta
 * 
 * @property int $idopcion_pregunta
 * @property int $idpregunta_prueba
 * @property int $idprueba_resultado
 * @property string $opcion
 * @property int $orden
 * @property bool $activo
 * @property int $valor
 * 
 * @property \sistema\Models\PreguntaPrueba $pregunta_prueba
 * @property \Illuminate\Database\Eloquent\Collection $opcion_resultados
 *
 * @package sistema\Models
 */
class OpcionPregunta extends Eloquent
{
	protected $primaryKey = 'idopcion_pregunta';
	public $timestamps = false;
	protected $table = 'opcion_pregunta';

	protected $casts = [
		'idpregunta_prueba' => 'int',
		'orden' => 'int',
		'activo' => 'bool',
		'valor' => 'int'
	];

	protected $fillable = [
		'idpregunta_prueba',
		'opcion',
		'orden',
		'activo',
		'valor'
	];

	public function pregunta_prueba()
	{
		return $this->belongsTo(\sistema\Models\PreguntaPrueba::class, 'idpregunta_prueba');
	}

	
	public function prueba_resultado()
	{
	    return $this->belongsTo(\sistema\Models\PruebaResultado::class, 'idprueba_resultado');
	}
}
