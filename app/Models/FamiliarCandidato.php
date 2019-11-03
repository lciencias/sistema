<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:28 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class FamiliarCandidato
 * 
 * @property int $idfamiliar_candidato
 * @property int $idcandidato
 * @property string $nombre
 * @property string $genero
 * @property \Carbon\Carbon $fecha_nacimiento
 * @property string $ocupacion
 * @property string $parentesco
 * 
 * @property \sistema\Models\Candidato $candidato
 *
 * @package sistema\Models
 */
class FamiliarCandidato extends Eloquent
{
	protected $table = 'familiar_candidato';
	protected $primaryKey = 'idfamiliar_candidato';
	public $timestamps = false;

	protected $casts = [
		'idcandidato' => 'int'
	];

	protected $dates = [
		'fecha_nacimiento'
	];

	protected $fillable = [
		'idcandidato',
		'nombre',
		'genero',
		'fecha_nacimiento',
		'ocupacion',
		'parentesco'
	];

	public function candidato()
	{
		return $this->belongsTo(\sistema\Models\Candidato::class, 'idcandidato');
	}
}
