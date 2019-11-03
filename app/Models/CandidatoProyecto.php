<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:28 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class CandidatoProyecto
 * 
 * @property int $idcandidato_proyecto
 * @property int $idperfil_puesto
 * @property int $idproyecto
 * @property int $idcandidato
 * @property \Carbon\Carbon $fecha_registro
 * 
 * @property \sistema\Models\Proyecto $proyecto
 * @property \sistema\Models\Candidato $candidato
 * @property \sistema\Models\PerfilPuesto $perfil_puesto
 * @property \Illuminate\Database\Eloquent\Collection $resultado_candidato_competencia
 * @property \Illuminate\Database\Eloquent\Collection $resultado_candidato_pruebas
 * @property \Illuminate\Database\Eloquent\Collection $resultado_candidato_talentos
 *
 * @package sistema\Models
 */
class CandidatoProyecto extends Eloquent
{
	protected $table = 'candidato_proyecto';
	protected $primaryKey = 'idcandidato_proyecto';
	public $timestamps = false;

	protected $casts = [
		'idperfil_puesto' => 'int',
		'idproyecto' => 'int',
		'idcandidato' => 'int'
	];

	protected $dates = [
		'fecha_registro'
	];

	protected $fillable = [
		'idperfil_puesto',
		'idproyecto',
		'idcandidato',
		'fecha_registro'
	];

	public function proyecto()
	{
		return $this->belongsTo(\sistema\Models\Proyecto::class, 'idproyecto');
	}

	public function candidato()
	{
		return $this->belongsTo(\sistema\Models\Candidato::class, 'idcandidato');
	}

	public function perfil_puesto()
	{
		return $this->belongsTo(\sistema\Models\PerfilPuesto::class, 'idperfil_puesto');
	}

	public function resultado_candidato_competencia()
	{
		return $this->hasMany(\sistema\Models\ResultadoCandidatoCompetencia::class, 'idcandidato_proyecto');
	}

	public function resultado_candidato_pruebas()
	{
		return $this->hasMany(\sistema\Models\ResultadoCandidatoPrueba::class, 'idcandidato_proyecto');
	}

	public function resultado_candidato_talentos()
	{
		return $this->hasMany(\sistema\Models\ResultadoCandidatoTalento::class, 'idcandidato_proyecto');
	}
}
