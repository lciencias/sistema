<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:28 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class CalificacionComportamiento
 * 
 * @property int $idcomportamiento
 * @property int $idcalificacion_comportamiento
 * @property string $calificacion_texto
 * @property int $idescala_calificacion_competencia
 * 
 * @property \sistema\Models\Comportamiento $comportamiento
 * @property \sistema\Models\EscalaCalificacionCompetencium $escala_calificacion_competencium
 *
 * @package sistema\Models
 */
class CalificacionEscala extends Eloquent
{
	protected $table = 'calificacion_escala';
	protected $primaryKey = 'idcalificacion_escala';
	public $timestamps = false;

	protected $casts = [
		'idescala_calificacion_competencia' => 'int',
		'calificacion' => 'double'
	];

	protected $fillable = [
		'idescala_calificacion_competencia',
	    'calificacion'
	];

	
}
