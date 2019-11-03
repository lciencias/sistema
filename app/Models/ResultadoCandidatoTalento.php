<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:29 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ResultadoCandidatoTalento
 * 
 * @property int $idresultado_candidato_talento
 * @property int $idcandidato_proyecto
 * @property int $idtalento
 * @property int $orden
 * 
 * @property \sistema\Models\Talento $talento
 * @property \sistema\Models\CandidatoProyecto $candidato_proyecto
 *
 * @package sistema\Models
 */
class ResultadoCandidatoTalento extends Eloquent
{
	protected $table = 'resultado_candidato_talento';
	protected $primaryKey = 'idresultado_candidato_talento';
	public $timestamps = false;

	protected $casts = [
		'idcandidato_proyecto' => 'int',
		'idtalento' => 'int',
		'orden' => 'int'
	];

	protected $fillable = [
		'idcandidato_proyecto',
		'idtalento',
		'orden'
	];

	public function talento()
	{
		return $this->belongsTo(\sistema\Models\Talento::class, 'idtalento');
	}

	public function candidato_proyecto()
	{
		return $this->belongsTo(\sistema\Models\CandidatoProyecto::class, 'idcandidato_proyecto');
	}
}
