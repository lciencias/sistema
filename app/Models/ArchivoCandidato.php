<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:28 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ArchivoCandidato
 * 
 * @property int $idarchivo_candidato
 * @property int $idcandidato
 * @property int $idarchivo
 * @property string $nombre
 * 
 * @property \sistema\Models\Archivo $archivo
 * @property \sistema\Models\Candidato $candidato
 *
 * @package sistema\Models
 */
class ArchivoCandidato extends Eloquent
{
	protected $table = 'archivo_candidato';
	protected $primaryKey = 'idarchivo_candidato';
	public $timestamps = false;

	protected $casts = [
		'idcandidato' => 'int',
		'idarchivo' => 'int'
	];

	protected $fillable = [
		'idcandidato',
		'idarchivo',
		'nombre'
	];

	public function archivo()
	{
		return $this->belongsTo(\sistema\Models\Archivo::class, 'idarchivo');
	}

	public function candidato()
	{
		return $this->belongsTo(\sistema\Models\Candidato::class, 'idcandidato');
	}
}
