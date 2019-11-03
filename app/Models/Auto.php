<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 10 Sep 2019 00:28:27 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Auto
 * 
 * @property int $idauto
 * @property string $no_placa
 * @property string $color
 * @property int $anio
 *
 * @package sistema\Models
 */
class Auto extends Eloquent
{
	protected $table = 'auto';
	protected $primaryKey = 'idauto';
	public $timestamps = false;

	protected $casts = [
		'anio' => 'int'
	];

	protected $fillable = [
		'no_placa',
		'color',
		'anio'
	];
}
