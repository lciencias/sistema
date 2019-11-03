<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 30 Sep 2019 22:11:26 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class TipoCompetencia
 * 
 * @property int $idtipo_competencia
 * @property string $nombre
 * @property bool $activo
 * 
 * @property \Illuminate\Database\Eloquent\Collection $competencia
 *
 * @package sistema\Models
 */
class TipoCompetencia extends Eloquent
{
	protected $primaryKey = 'idtipo_competencia';
	public $timestamps = false;
	protected $table = 'tipo_competencia';

	protected $casts = [
		'activo' => 'bool'
	];

	protected $fillable = [
		'nombre',
		'activo'
	];

	public function competencia()
	{
		return $this->hasMany(\sistema\Models\Competencia::class, 'idtipo_competencia');
	}
}
