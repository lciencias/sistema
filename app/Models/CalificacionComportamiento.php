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
class CalificacionComportamiento extends Eloquent
{
	protected $table = 'calificacion_comportamiento';
	protected $primaryKey = 'idcalificacion_comportamiento';
	public $timestamps = false;

	protected $casts = [
		'idcomportamiento' => 'int',
		'idescala_calificacion_competencia' => 'int'
	];

	protected $fillable = [
		'idcomportamiento',
		'calificacion_texto',
		'idescala_calificacion_competencia'
	];

	public function comportamiento()
	{
		return $this->belongsTo(\sistema\Models\Comportamiento::class, 'idcomportamiento');
	}

	public function escala_calificacion_competencium()
	{
		return $this->belongsTo(\sistema\Models\EscalaCalificacionCompetencia::class, 'idescala_calificacion_competencia');
	}
}
