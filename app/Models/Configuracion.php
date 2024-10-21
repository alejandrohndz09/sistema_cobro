<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Configuracion
 * 
 * @property int $tiempo_incobrabilidad
 * @property float $tasa_interes
 *
 * @package App\Models
 */
class Configuracion extends Model
{
	protected $table = 'configuracion';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'tiempo_incobrabilidad' => 'int',
		'tasa_interes' => 'float'
	];

	protected $fillable = [
		'tiempo_incobrabilidad',
		'tasa_interes'
	];
}
