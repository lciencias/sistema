<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:28 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Estado
 * 
 * @property int $idestado
 * @property string $clave
 * @property string $nombre
 * 
 * @property \Illuminate\Database\Eloquent\Collection $candidatos
 * @property \Illuminate\Database\Eloquent\Collection $municipios
 *
 * @package sistema\Models
 */
class Estado extends Eloquent
{
	protected $table = 'estado';
	protected $primaryKey = 'idestado';
	public $timestamps = false;

	protected $fillable = [
		'clave',
		'nombre'
	];

	public function candidatos()
	{
		return $this->hasMany(\sistema\Models\Candidato::class, 'idestado_nacimiento');
	}

	public function municipios()
	{
		return $this->hasMany(\sistema\Models\Municipio::class, 'idestado');
	}
}
