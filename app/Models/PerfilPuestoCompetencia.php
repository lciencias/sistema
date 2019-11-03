<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 15 Oct 2019 11:40:09 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PerfilPuestoCompetencium
 * 
 * @property int $idperfil_puesto
 * @property int $idperfil_puesto_competencia
 * @property int $idcompetencia
 * @property string $importancia
 * 
 * @property \sistema\Models\PerfilPuesto $perfil_puesto
 * @property \sistema\Models\Competencium $competencium
 *
 * @package sistema\Models
 */
class PerfilPuestoCompetencia extends Eloquent
{
	protected $primaryKey = 'idperfil_puesto_competencia';
	public $timestamps = false;

	protected $casts = [
		'idperfil_puesto' => 'int',
		'idcompetencia' => 'int'
	];

	protected $fillable = [
		'idperfil_puesto',
		'idcompetencia',
		'importancia'
	];

	public function perfil_puesto()
	{
		return $this->belongsTo(\sistema\Models\PerfilPuesto::class, 'idperfil_puesto');
	}

	public function competencium()
	{
		return $this->belongsTo(\sistema\Models\Competencia::class, 'idcompetencia');
	}
}
