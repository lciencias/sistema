<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:29 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Talento
 * 
 * @property int $idtalento
 * @property string $nombre
 * @property bool $activo
 * @property string $definicion
 * 
 * @property \Illuminate\Database\Eloquent\Collection $perfil_puestos
 * @property \Illuminate\Database\Eloquent\Collection $resultado_candidato_talentos
 *
 * @package sistema\Models
 */
class Talento extends Eloquent
{
	protected $table = 'talento';
	protected $primaryKey = 'idtalento';
	public $timestamps = false;

	protected $casts = [
		'activo' => 'bool'
	];

	protected $fillable = [
		'nombre',
		'activo',
		'definicion'
	];

	public function perfil_puestos()
	{
		return $this->belongsToMany(\sistema\Models\PerfilPuesto::class, 'perfil_puesto_talento', 'idtalento', 'idperfil_puesto')
					->withPivot('idperfil_puesto_talento');
	}

	public function resultado_candidato_talentos()
	{
		return $this->hasMany(\sistema\Models\ResultadoCandidatoTalento::class, 'idtalento');
	}
}
