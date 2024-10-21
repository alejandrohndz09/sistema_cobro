<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Abonocapital
 * 
 * @property string $idAbono
 * @property Carbon $fecha
 * @property float $monto
 * @property string|null $idVenta
 * 
 * @property Venta|null $venta
 *
 * @package App\Models
 */
class Abonocapital extends Model
{
	protected $table = 'abonocapital';
	protected $primaryKey = 'idAbono';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'fecha' => 'datetime',
		'monto' => 'float'
	];

	protected $fillable = [
		'fecha',
		'monto',
		'idVenta'
	];

	public function venta()
	{
		return $this->belongsTo(Venta::class, 'idVenta');
	}
}
