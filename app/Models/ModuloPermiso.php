<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:28 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ModuloPermiso
 * 
 * @property int $id
 * @property int $idmodulo
 * @property int $idpermiso
 * 
 * @property \sistema\Models\Modulo $modulo
 * @property \sistema\Models\Permiso $permiso
 *
 * @package sistema\Models
 */
class ModuloPermiso extends Eloquent
{
	protected $table = 'modulo_permiso';
	public $timestamps = false;

	protected $casts = [
		'idmodulo' => 'int',
		'idpermiso' => 'int'
	];

	protected $fillable = [
		'idmodulo',
		'idpermiso'
	];

	public function modulo()
	{
		return $this->belongsTo(\sistema\Models\Modulo::class, 'idmodulo');
	}

	public function permiso()
	{
		return $this->belongsTo(\sistema\Models\Permiso::class, 'idpermiso');
	}
}
