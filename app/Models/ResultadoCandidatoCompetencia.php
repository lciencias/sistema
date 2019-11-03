<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:29 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ResultadoCandidatoCompetencium
 * 
 * @property int $idresultado_candidato_competencia
 * @property int $idcandidato_proyecto
 * 
 * @property \sistema\Models\CandidatoProyecto $candidato_proyecto
 *
 * @package sistema\Models
 */
class ResultadoCandidatoCompetencia extends Eloquent
{
	protected $primaryKey = 'idresultado_candidato_competencia';
	public $timestamps = false;

	protected $casts = [
		'idcandidato_proyecto' => 'int'
	];

	protected $fillable = [
		'idcandidato_proyecto'
	];

	public function candidato_proyecto()
	{
		return $this->belongsTo(\sistema\Models\CandidatoProyecto::class, 'idcandidato_proyecto');
	}
}
