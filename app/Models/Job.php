<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 25 Oct 2019 20:46:28 -0500.
 */

namespace sistema\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Job
 * 
 * @property int $id
 * @property string $queue
 * @property string $payload
 * @property int $attempts
 * @property int $reserved
 * @property int $reserved_at
 * @property int $available_at
 * @property int $created_at
 *
 * @package sistema\Models
 */
class Job extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'attempts' => 'int',
		'reserved' => 'int',
		'reserved_at' => 'int',
		'available_at' => 'int',
		'created_at' => 'int'
	];

	protected $fillable = [
		'queue',
		'payload',
		'attempts',
		'reserved',
		'reserved_at',
		'available_at'
	];
}
