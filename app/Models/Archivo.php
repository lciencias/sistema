<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:28 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Archivo
 * 
 * @property int $idarchivo
 * @property string $nombre
 * @property string $contenido
 * 
 * @property \Illuminate\Database\Eloquent\Collection $candidatos
 *
 * @package sistema\Models
 */
class Archivo extends Eloquent
{
	protected $table = 'archivo';
	protected $primaryKey = 'idarchivo';
	public $timestamps = false;

	protected $fillable = [
		'nombre',
		'contenido'
	];

	public function candidatos()
	{
		return $this->hasMany(\sistema\Models\Candidato::class, 'idarchivo_cv');
	}
}
