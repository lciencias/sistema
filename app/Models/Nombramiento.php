<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:29 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Nombramiento
 * 
 * @property int $idnombramiento
 * @property int $idperfil_puesto
 * @property int $idcandidato
 * @property \Carbon\Carbon $fecha
 * @property float $salario
 * @property float $espectativas_economicas
 * @property bool $vigente
 * 
 * @property \sistema\Models\PerfilPuesto $perfil_puesto
 * @property \sistema\Models\Candidato $candidato
 *
 * @package sistema\Models
 */
class Nombramiento extends Eloquent
{
	protected $table = 'nombramiento';
	protected $primaryKey = 'idnombramiento';
	public $timestamps = false;

	protected $casts = [
		'idperfil_puesto' => 'int',
		'idcandidato' => 'int',
		'salario' => 'float',
		'espectativas_economicas' => 'float',
		'vigente' => 'bool'
	];

	protected $dates = [
		'fecha'
	];

	protected $fillable = [
		'idperfil_puesto',
		'idcandidato',
		'fecha',
		'salario',
		'espectativas_economicas',
		'vigente'
	];

	public function perfil_puesto()
	{
		return $this->belongsTo(\sistema\Models\PerfilPuesto::class, 'idperfil_puesto');
	}

	public function candidato()
	{
		return $this->belongsTo(\sistema\Models\Candidato::class, 'idcandidato');
	}
}
