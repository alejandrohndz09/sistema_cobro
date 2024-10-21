<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DetalleVenta
 * 
 * @property string $idDetalleVenta
 * @property int $cantidad
 * @property float $subtotal
 * @property string|null $idProducto
 * @property string|null $idventa
 * 
 * @property Producto|null $producto
 * @property Venta|null $venta
 *
 * @package App\Models
 */
class DetalleVenta extends Model
{
	protected $table = 'detalle_venta';
	protected $primaryKey = 'idDetalleVenta';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'cantidad' => 'int',
		'subtotal' => 'float'
	];

	protected $fillable = [
		'cantidad',
		'subtotal',
		'idProducto',
		'idventa'
	];

	public function producto()
	{
		return $this->belongsTo(Producto::class, 'idProducto');
	}

	public function venta()
	{
		return $this->belongsTo(Venta::class, 'idventa');
	}
}
