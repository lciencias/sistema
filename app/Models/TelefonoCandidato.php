<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:29 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class TelefonoCandidato
 * 
 * @property int $idtelefono_candidato
 * @property int $idcandidato
 * @property string $tipo
 * @property string $telefono
 * @property string $notas
 * 
 * @property \sistema\Models\Candidato $candidato
 *
 * @package sistema\Models
 */
class TelefonoCandidato extends Eloquent
{
	protected $table = 'telefono_candidato';
	protected $primaryKey = 'idtelefono_candidato';
	public $timestamps = false;

	protected $casts = [
		'idcandidato' => 'int'
	];

	protected $fillable = [
		'idcandidato',
		'tipo',
		'telefono',
		'notas'
	];

	public function candidato()
	{
		return $this->belongsTo(\sistema\Models\Candidato::class, 'idcandidato');
	}
}
