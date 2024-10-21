<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Producto
 * 
 * @property string $idProducto
 * @property string $nombre
 * @property string $descripcion
 * @property string $imagen
 * @property int $stockMinimo
 * @property int $StockTotal
 * @property int $estado
 * 
 * @property Collection|DetalleCompra[] $detalle_compras
 * @property Collection|DetalleVenta[] $detalle_venta
 *
 * @package App\Models
 */
class Producto extends Model
{
	protected $table = 'producto';
	protected $primaryKey = 'idProducto';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'stockMinimo' => 'int',
		'StockTotal' => 'int',
		'estado' => 'int'
	];

	protected $fillable = [
		'nombre',
		'descripcion',
		'imagen',
		'stockMinimo',
		'StockTotal',
		'estado'
	];

	public function detalle_compras()
	{
		return $this->hasMany(DetalleCompra::class, 'idProducto');
	}

	public function detalle_ventas()
	{
		return $this->hasMany(DetalleVenta::class, 'idProducto');
	}
}
