<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:28 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class GrupoImportanciaPerfilPuesto
 * 
 * @property int $idgrupo_importancia_perfil_puesto
 * @property int $idperfil_puesto
 * @property string $importancia
 * @property float $porcentaje
 * 
 * @property \sistema\Models\PerfilPuesto $perfil_puesto
 * @property \Illuminate\Database\Eloquent\Collection $perfil_puesto_competencia
 *
 * @package sistema\Models
 */
class GrupoImportanciaPerfilPuesto extends Eloquent
{
	protected $table = 'grupo_importancia_perfil_puesto';
	protected $primaryKey = 'idgrupo_importancia_perfil_puesto';
	public $timestamps = false;

	protected $casts = [
		'idperfil_puesto' => 'int',
		'porcentaje' => 'float'
	];

	protected $fillable = [
		'idperfil_puesto',
		'importancia',
		'porcentaje'
	];

	public function perfil_puesto()
	{
		return $this->belongsTo(\sistema\Models\PerfilPuesto::class, 'idperfil_puesto');
	}

	public function perfil_puesto_competencia()
	{
		return $this->hasMany(\sistema\Models\PerfilPuestoCompetencia::class, 'idgrupo_importancia_perfil_puesto');
	}
}
