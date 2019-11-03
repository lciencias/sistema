<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:28 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Direccion
 * 
 * @property int $iddireccion
 * @property int $idmunicipio
 * @property string $calle
 * @property string $no_exterior
 * @property string $no_interior
 * @property string $colonia
 * @property string $cp
 * @property string $edificio
 * @property string $tipo_calle
 * 
 * @property \sistema\Models\Municipio $municipio
 * @property \Illuminate\Database\Eloquent\Collection $candidatos
 * @property \Illuminate\Database\Eloquent\Collection $clientes
 *
 * @package sistema\Models
 */
class Direccion extends Eloquent
{
	protected $table = 'direccion';
	protected $primaryKey = 'iddireccion';
	public $timestamps = false;

	protected $casts = [
		'idmunicipio' => 'int'
	];

	protected $fillable = [
		'idmunicipio',
		'calle',
		'no_exterior',
		'no_interior',
		'colonia',
		'cp',
		'edificio',
		'tipo_calle'
	];

	public function municipio()
	{
		return $this->belongsTo(\sistema\Models\Municipio::class, 'idmunicipio');
	}

	public function candidatos()
	{
		return $this->hasMany(\sistema\Models\Candidato::class, 'iddireccion');
	}

	public function clientes()
	{
		return $this->hasMany(\sistema\Models\Cliente::class, 'iddireccion_comercial');
	}
}
