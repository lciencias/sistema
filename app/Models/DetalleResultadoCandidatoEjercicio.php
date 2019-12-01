<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:28 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class DetalleResultadoCandidatoEjercicio
 * 
 * @property int $iddetalle_resultado_candidato_ejercicio
 * @property int $idresultado_candidato_ejercicio
 * @property int $idcomportamiento
 * @property int $iduser
 * @property float $calificacion
 * 
 * @property \sistema\Models\User $user
 * @property \sistema\Models\ResultadoCandidatoEjercicio $resultado_candidato_ejercicio
 * @property \sistema\Models\Comportamiento $comportamiento
 *
 * @package sistema\Models
 */
class DetalleResultadoCandidatoEjercicio extends Eloquent
{
	protected $table = 'detalle_resultado_candidato_ejercicio';
	protected $primaryKey = 'iddetalle_resultado_candidato_ejercicio';
	public $timestamps = false;

	protected $casts = [
		'idresultado_candidato_ejercicio' => 'int',
		'idcomportamiento' => 'int',
		'idcalificacion_comportamiento' => 'int',
		'calificacion' => 'float'
	];

	protected $fillable = [
		'idresultado_candidato_ejercicio',
		'idcomportamiento',
	    'idcalificacion_comportamiento',
		'calificacion'
	];

	public function user()
	{
		return $this->belongsTo(\sistema\Models\User::class, 'iduser');
	}

	public function resultado_candidato_ejercicio()
	{
		return $this->belongsTo(\sistema\Models\ResultadoCandidatoEjercicio::class, 'idresultado_candidato_ejercicio');
	}

	public function comportamiento()
	{
		return $this->belongsTo(\sistema\Models\Comportamiento::class, 'idcomportamiento');
	}
}
