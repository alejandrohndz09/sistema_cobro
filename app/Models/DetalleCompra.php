<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DetalleCompra
 * 
 * @property string $idDetalleCompra
 * @property float $precio
 * @property int $cantidad
 * @property string|null $idCompra
 * @property string|null $idProducto
 * 
 * @property Compra|null $compra
 * @property Producto|null $producto
 *
 * @package App\Models
 */
class DetalleCompra extends Model
{
	protected $table = 'detalle_compra';
	protected $primaryKey = 'idDetalleCompra';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'precio' => 'float',
		'cantidad' => 'int'
	];

	protected $fillable = [
		'precio',
		'cantidad',
		'idCompra',
		'idProducto'
	];

	public function compra()
	{
		return $this->belongsTo(Compra::class, 'idCompra');
	}

	public function producto()
	{
		return $this->belongsTo(Producto::class, 'idProducto');
	}
}
