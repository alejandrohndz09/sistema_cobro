<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Compra
 * 
 * @property string $idCompra
 * @property Carbon $fecha
 * @property int $stockDisponible
 * @property string|null $idEmpleado
 * @property string|null $idProveedor
 * 
 * @property Empleado|null $empleado
 * @property Proveedor|null $proveedor
 * @property Collection|DetalleCompra[] $detalle_compras
 *
 * @package App\Models
 */
class Compra extends Model
{
	protected $table = 'compra';
	protected $primaryKey = 'idCompra';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'fecha' => 'datetime',
		'stockDisponible' => 'int'
	];

	protected $fillable = [
		'fecha',
		'stockDisponible',
		'idEmpleado',
		'idProveedor'
	];

	public function empleado()
	{
		return $this->belongsTo(Empleado::class, 'idEmpleado');
	}

	public function proveedor()
	{
		return $this->belongsTo(Proveedor::class, 'idProveedor');
	}

	public function detalle_compras()
	{
		return $this->hasMany(DetalleCompra::class, 'idCompra');
	}
}
