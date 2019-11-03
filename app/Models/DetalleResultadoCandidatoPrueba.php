<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:28 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class DetalleResultadoCandidatoPrueba
 * 
 * @property int $iddetalle_resultado_candidato_prueba
 * @property int $idopcion_pregunta
 * @property int $idresultado_candidato_prueba
 * 
 * @property \sistema\Models\ResultadoCandidatoPrueba $resultado_candidato_prueba
 * @property \sistema\Models\OpcionPreguntum $opcion_preguntum
 *
 * @package sistema\Models
 */
class DetalleResultadoCandidatoPrueba extends Eloquent
{
	protected $table = 'detalle_resultado_candidato_prueba';
	protected $primaryKey = 'iddetalle_resultado_candidato_prueba';
	public $timestamps = false;

	protected $casts = [
		'idopcion_pregunta' => 'int',
		'idresultado_candidato_prueba' => 'int'
	];

	protected $fillable = [
		'idopcion_pregunta',
		'idresultado_candidato_prueba'
	];

	public function resultado_candidato_prueba()
	{
		return $this->belongsTo(\sistema\Models\ResultadoCandidatoPrueba::class, 'idresultado_candidato_prueba');
	}

	public function opcion_preguntum()
	{
		return $this->belongsTo(\sistema\Models\OpcionPreguntum::class, 'idopcion_pregunta');
	}
}
