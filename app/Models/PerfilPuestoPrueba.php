<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:29 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PerfilPuestoPrueba
 * 
 * @property int $idperfil_puesto_pruebas
 * @property int $idprueba
 * @property int $idperfil_puesto
 * 
 * @property \sistema\Models\Prueba $prueba
 * @property \sistema\Models\PerfilPuesto $perfil_puesto
 *
 * @package sistema\Models
 */
class PerfilPuestoPrueba extends Eloquent
{
    protected $table = 'perfil_puesto_pruebas';
	protected $primaryKey = 'idperfil_puesto_pruebas';
	public $timestamps = false;

	protected $casts = [
		'idprueba' => 'int',
		'idperfil_puesto' => 'int'
	];

	protected $fillable = [
		'idprueba',
		'idperfil_puesto'
	];

	public function prueba()
	{
		return $this->belongsTo(\sistema\Models\Prueba::class, 'idprueba');
	}

	public function perfil_puesto()
	{
		return $this->belongsTo(\sistema\Models\PerfilPuesto::class, 'idperfil_puesto');
	}
}
