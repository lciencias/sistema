<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:29 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Proyecto
 * 
 * @property int $idproyecto
 * @property int $idcliente
 * @property string $nombre
 * @property string $descripcion
 * @property \Carbon\Carbon $fecha_alta
 * @property \Carbon\Carbon $fecha_ini_evalua
 * @property \Carbon\Carbon $fecha_fin_evalua
 * @property \Carbon\Carbon $fecha_ini
 * @property \Carbon\Carbon $fecha_fin
 * 
 * @property \sistema\Models\Cliente $cliente
 * @property \Illuminate\Database\Eloquent\Collection $candidatos
 *
 * @package sistema\Models
 */
class Proyecto extends Eloquent
{
	protected $table = 'proyecto';
	protected $primaryKey = 'idproyecto';
	public $timestamps = false;

	protected $casts = [
		'idcliente' => 'int'
	];

	protected $dates = [
		'fecha_alta',
		'fecha_ini_evalua',
		'fecha_fin_evalua',
		'fecha_ini',
		'fecha_fin'
	];

	protected $fillable = [
		'idcliente',
		'nombre',
		'descripcion',
		'fecha_alta',
		'fecha_ini_evalua',
		'fecha_fin_evalua',
		'fecha_ini',
		'fecha_fin'
	];

	public function cliente()
	{
		return $this->belongsTo(\sistema\Models\Cliente::class, 'idcliente');
	}

	public function candidatos()
	{
		return $this->belongsToMany(\sistema\Models\Candidato::class, 'candidato_proyecto', 'idproyecto', 'idcandidato')
					->withPivot('idcandidato_proyecto', 'idperfil_puesto', 'fecha_registro');
	}
}
