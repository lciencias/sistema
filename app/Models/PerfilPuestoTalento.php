<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:29 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PerfilPuestoTalento
 * 
 * @property int $idperfil_puesto_talento
 * @property int $idtalento
 * @property int $idperfil_puesto
 * 
 * @property \sistema\Models\Talento $talento
 * @property \sistema\Models\PerfilPuesto $perfil_puesto
 *
 * @package sistema\Models
 */
class PerfilPuestoTalento extends Eloquent
{
	protected $table = 'perfil_puesto_talento';
	protected $primaryKey = 'idperfil_puesto_talento';
	public $timestamps = false;

	protected $casts = [
		'idtalento' => 'int',
		'idperfil_puesto' => 'int'
	];

	protected $fillable = [
		'idtalento',
		'idperfil_puesto'
	];

	public function talento()
	{
		return $this->belongsTo(\sistema\Models\Talento::class, 'idtalento');
	}

	public function perfil_puesto()
	{
		return $this->belongsTo(\sistema\Models\PerfilPuesto::class, 'idperfil_puesto');
	}
}
