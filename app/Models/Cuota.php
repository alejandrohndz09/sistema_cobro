<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Cuotum
 * 
 * @property string $idCuota
 * @property Carbon $fechaLimite
 * @property Carbon $fechaPago
 * @property float $monto
 * @property float $mora
 * @property int $estado
 * @property string|null $idVenta
 * 
 * @property Venta|null $venta
 *
 * @package App\Models
 */
class Cuotum extends Model
{
	protected $table = 'cuota';
	protected $primaryKey = 'idCuota';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'fechaLimite' => 'datetime',
		'fechaPago' => 'datetime',
		'monto' => 'float',
		'mora' => 'float',
		'estado' => 'int'
	];

	protected $fillable = [
		'fechaLimite',
		'fechaPago',
		'monto',
		'mora',
		'estado',
		'idVenta'
	];

	public function venta()
	{
		return $this->belongsTo(Venta::class, 'idVenta');
	}
}
