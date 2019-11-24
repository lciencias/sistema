<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:28 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Ejercicio
 * 
 * @property int $idtipo_ejercicio
 * @property int $idejercicio
 * @property string $nombre
 * @property bool $activo
 * @property string $descripcion
 * 
 * @property \sistema\Models\TipoEjercicio $tipo_ejercicio
 * @property \Illuminate\Database\Eloquent\Collection $candidato_proyecto_ejercicios
 *
 * @package sistema\Models
 */
class ViewEjercicio extends Eloquent
{
	protected $table = 'view_ejercicio';
	public $timestamps = false;

	protected $casts = [
		'idcandidato_proyecto_ejercicio' => 'int',
	    'idejercicio' => 'int',
	    'idcandidato_proyecto' => 'int',
	    'iduser' => 'int',
	    'idcliente' => 'int',
	    'estatus' => 'int',
	    'idtipo_ejercicio_cliente' => 'int',
	    'idtipo_ejercicio' => 'int',
	    'dias' => 'int'
	];

	protected $fillable = [
		'idcandidato_proyecto_ejercicio',
	    'idejercicio',
	    'idcandidato_proyecto',
	    'iduser',
	    'estatus',
	    'idtipo_ejercicio_cliente',
	    'ejercicio',	    
		'nombre',
	    'paterno',
	    'materno',
	    'idcliente',
	    'idproyecto',
	    'proyecto',
	    'fecha_fin',
		'dias',
	    'idtipo_ejercicio',
        'tipo_ejercicio',
	    'clase'
	];
}
