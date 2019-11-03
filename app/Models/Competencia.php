<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 30 Sep 2019 22:11:25 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Competencia
 * 
 * @property int $idcompetencia
 * @property string $nombre
 * @property bool $activo
 * @property string $definicion
 * @property int $idtipo_competencia
  * @property string $recomendaciones
 * 
 * @property \sistema\Models\TipoCompetencium $tipo_competencia
 * @property \Illuminate\Database\Eloquent\Collection $comportamientos
 * @property \Illuminate\Database\Eloquent\Collection $perfil_puesto_competencia
 *
 * @package sistema\Models
 */
class Competencia extends Eloquent
{
	protected $primaryKey = 'idcompetencia';
	public $timestamps = false;
	protected $table = 'competencia';

	protected $casts = [
		'activo' => 'bool',
		'idtipo_competencia' => 'int'
	];

	protected $fillable = [
		'nombre',
		'activo',
		'definicion',
		'idtipo_competencia',
	    'recomendaciones'
	];

	public function tipo_competencia()
	{
		return $this->belongsTo(\sistema\Models\TipoCompetencia::class, 'idtipo_competencia');
	}

	public function comportamientos()
	{
		return $this->hasMany(\sistema\Models\Comportamiento::class, 'idcompetencia');
	}

	public function perfil_puesto_competencia()
	{
		return $this->hasMany(\sistema\Models\PerfilPuestoCompetencia::class, 'idcompetencia');
	}
}
