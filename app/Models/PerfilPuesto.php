<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:29 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PerfilPuesto
 * 
 * @property int $idperfil_puesto
 * @property int $idcliente
 * @property string $nombre
 * @property bool $activo
 * @property string $clave
 * @property string $nivel_puesto
 * @property string $funcion_generica
 * @property string $funciones_generales
 * @property string $actividades
 * @property string $etapa
 * 
 * @property \sistema\Models\Cliente $cliente
 * @property \Illuminate\Database\Eloquent\Collection $candidato_proyectos
 * @property \Illuminate\Database\Eloquent\Collection $grupo_importancia_perfil_puestos
 * @property \Illuminate\Database\Eloquent\Collection $nombramientos
 * @property \Illuminate\Database\Eloquent\Collection $pruebas
 * @property \Illuminate\Database\Eloquent\Collection $talentos
 *
 * @package sistema\Models
 */
class PerfilPuesto extends Eloquent
{
	protected $table = 'perfil_puesto';
	protected $primaryKey = 'idperfil_puesto';
	public $timestamps = false;

	protected $casts = [
		'idcliente' => 'int',
		'activo' => 'bool'
	];

	protected $fillable = [
		'idcliente',
		'nombre',
		'activo',
		'clave',
		'nivel_puesto',
		'funcion_generica',
		'funciones_generales',
		'actividades',
		'etapa'
	];

	public function cliente()
	{
		return $this->belongsTo(\sistema\Models\Cliente::class, 'idcliente');
	}

	public function candidato_proyectos()
	{
		return $this->hasMany(\sistema\Models\CandidatoProyecto::class, 'idperfil_puesto');
	}

	public function grupo_importancia_perfil_puestos()
	{
		return $this->hasMany(\sistema\Models\GrupoImportanciaPerfilPuesto::class, 'idperfil_puesto');
	}

	public function nombramientos()
	{
		return $this->hasMany(\sistema\Models\Nombramiento::class, 'idperfil_puesto');
	}

	public function pruebas()
	{
		return $this->belongsToMany(\sistema\Models\Prueba::class, 'perfil_puesto_pruebas', 'idperfil_puesto', 'idprueba')
					->withPivot('idperfil_puesto_pruebas');
	}

	public function talentos()
	{
		return $this->belongsToMany(\sistema\Models\Talento::class, 'perfil_puesto_talento', 'idperfil_puesto', 'idtalento')
					->withPivot('idperfil_puesto_talento');
	}
}
