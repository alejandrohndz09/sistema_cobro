<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Venta
 * 
 * @property string $idVenta
 * @property Carbon $fecha
 * @property int $tipo
 * @property int|null $meses
 * @property float $SaldoCapital
 * @property float $iva
 * @property float $total
 * @property string|null $idEmpleado
 * @property string|null $idCliente_juridico
 * @property string|null $idCliente_natural
 * 
 * @property ClienteJuridico|null $cliente_juridico
 * @property ClienteNatural|null $cliente_natural
 * @property Empleado|null $empleado
 * @property Collection|Abonocapital[] $abonocapitals
 * @property Collection|Cuotum[] $cuota
 * @property Collection|DetalleVenta[] $detalle_venta
 *
 * @package App\Models
 */
class Venta extends Model
{
	protected $table = 'venta';
	protected $primaryKey = 'idVenta';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'fecha' => 'datetime',
		'tipo' => 'int',
		'meses' => 'int',
		'SaldoCapital' => 'float',
		'iva' => 'float',
		'total' => 'float'
	];

	protected $fillable = [
		'fecha',
		'tipo',
		'meses',
		'SaldoCapital',
		'iva',
		'total',
		'idEmpleado',
		'idCliente_juridico',
		'idCliente_natural'
	];

	public function cliente_juridico()
	{
		return $this->belongsTo(ClienteJuridico::class, 'idCliente_juridico');
	}

	public function cliente_natural()
	{
		return $this->belongsTo(ClienteNatural::class, 'idCliente_natural');
	}

	public function empleado()
	{
		return $this->belongsTo(Empleado::class, 'idEmpleado');
	}

	public function abonocapitals()
	{
		return $this->hasMany(Abonocapital::class, 'idVenta');
	}

	public function cuota()
	{
		return $this->hasMany(Cuotum::class, 'idVenta');
	}

	public function detalle_venta()
	{
		return $this->hasMany(DetalleVenta::class, 'idventa');
	}
}
