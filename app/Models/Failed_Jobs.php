<?php

namespace sistema\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Failed_Jobs
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
class Failed_Jobs extends Model
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
