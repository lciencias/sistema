<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:29 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Prueba
 * 
 * @property int $idprueba
 * @property string $nombre
 * @property string $indicaciones
 * @property string $descripcion
 * @property bool $activo
 * @property string $tipo_interpretacion
 * @property string $etapa
 * 
 * @property \Illuminate\Database\Eloquent\Collection $detalle_servicios
 * @property \Illuminate\Database\Eloquent\Collection $perfil_puestos
 * @property \Illuminate\Database\Eloquent\Collection $pregunta_pruebas
 * @property \Illuminate\Database\Eloquent\Collection $prueba_interpretacions
 * @property \Illuminate\Database\Eloquent\Collection $prueba_resultados
 *
 * @package sistema\Models
 */
class Prueba extends Eloquent
{
	protected $table = 'prueba';
	protected $primaryKey = 'idprueba';
	public $timestamps = false;

	protected $casts = [
		'activo' => 'bool'
	];

	protected $fillable = [
		'nombre',
		'indicaciones',
		'descripcion',
		'activo',
		'tipo_interpretacion',
		'etapa'
	];

	public function detalle_servicios()
	{
		return $this->hasMany(\sistema\Models\DetalleServicio::class, 'idprueba');
	}

	public function perfil_puestos()
	{
		return $this->belongsToMany(\sistema\Models\PerfilPuesto::class, 'perfil_puesto_pruebas', 'idprueba', 'idperfil_puesto')
					->withPivot('idperfil_puesto_pruebas');
	}

	public function pregunta_pruebas()
	{
		return $this->hasMany(\sistema\Models\PreguntaPrueba::class, 'idprueba');
	}

	public function prueba_interpretacions()
	{
		return $this->hasMany(\sistema\Models\PruebaInterpretacion::class, 'idprueba');
	}

	public function prueba_resultados()
	{
		return $this->hasMany(\sistema\Models\PruebaResultado::class, 'idprueba');
	}
}
