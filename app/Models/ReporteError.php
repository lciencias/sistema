<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:29 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ReporteError
 * 
 * @property int $idreporte_error
 * @property \Carbon\Carbon $fecha
 * @property string $excepcion
 * @property string $clase
 * @property string $metodo
 * @property int $linea
 * @property string $mensaje
 * @property bool $reportado
 * @property string $archivo
 *
 * @package sistema\Models
 */
class ReporteError extends Eloquent
{
	protected $table = 'reporte_error';
	protected $primaryKey = 'idreporte_error';
	public $timestamps = false;

	protected $casts = [
		'linea' => 'int',
		'reportado' => 'bool'
	];

	protected $dates = [
		'fecha'
	];

	protected $fillable = [
		'fecha',
		'excepcion',
		'clase',
		'metodo',
		'linea',
		'mensaje',
		'reportado',
		'archivo'
	];
}
