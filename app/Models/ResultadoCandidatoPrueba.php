<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:29 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ResultadoCandidatoPrueba
 * 
 * @property int $idresultado_candidato_prueba
 * @property int $idprueba_interpretacion
 * @property int $idcandidato_proyecto
 * @property int $tiempo
 * 
 * @property \sistema\Models\CandidatoProyecto $candidato_proyecto
 * @property \sistema\Models\PruebaInterpretacion $prueba_interpretacion
 * @property \Illuminate\Database\Eloquent\Collection $detalle_resultado_candidato_pruebas
 *
 * @package sistema\Models
 */
class ResultadoCandidatoPrueba extends Eloquent
{
	protected $table = 'resultado_candidato_prueba';
	protected $primaryKey = 'idresultado_candidato_prueba';
	public $timestamps = false;

	protected $casts = [
		'idprueba_interpretacion' => 'int',
		'idcandidato_proyecto' => 'int'
	];

	protected $fillable = [
		'idprueba_interpretacion',
		'idcandidato_proyecto'
	];

	public function candidato_proyecto()
	{
		return $this->belongsTo(\sistema\Models\CandidatoProyecto::class, 'idcandidato_proyecto');
	}

	public function prueba_interpretacion()
	{
		return $this->belongsTo(\sistema\Models\PruebaInterpretacion::class, 'idprueba_interpretacion');
	}

	public function detalle_resultado_candidato_pruebas()
	{
		return $this->hasMany(\sistema\Models\DetalleResultadoCandidatoPrueba::class, 'idresultado_candidato_prueba');
	}
}
