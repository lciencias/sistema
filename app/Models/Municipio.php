<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:28 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Municipio
 * 
 * @property int $idmunicipio
 * @property int $idestado
 * @property string $nombre
 * 
 * @property \sistema\Models\Estado $estado
 * @property \Illuminate\Database\Eloquent\Collection $direccions
 *
 * @package sistema\Models
 */
class Municipio extends Eloquent
{
	protected $table = 'municipio';
	protected $primaryKey = 'idmunicipio';
	public $timestamps = false;

	protected $casts = [
		'idestado' => 'int'
	];

	protected $fillable = [
		'idestado',
		'nombre'
	];

	public function estado()
	{
		return $this->belongsTo(\sistema\Models\Estado::class, 'idestado');
	}

	public function direccions()
	{
		return $this->hasMany(\sistema\Models\Direccion::class, 'idmunicipio');
	}
}
