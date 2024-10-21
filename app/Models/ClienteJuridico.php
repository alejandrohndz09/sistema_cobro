<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ClienteJuridico
 * 
 * @property string $idClienteJuridico
 * @property string $nit
 * @property string $nombre_empresa
 * @property string $direccion
 * @property string $telefono
 * @property float $ventas_netas
 * @property float $activo_corriente
 * @property float $inventario
 * @property float $costos_ventas
 * @property float $pasivos_corriente
 * @property float $cuentas_cobrar
 * @property float $cuentas_pagar
 * @property int $estado
 * @property string $balance_general
 * @property string $estado_resultado
 * 
 * @property Collection|Venta[] $venta
 *
 * @package App\Models
 */
class ClienteJuridico extends Model
{
	protected $table = 'cliente_juridico';
	protected $primaryKey = 'idClienteJuridico';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'ventas_netas' => 'float',
		'activo_corriente' => 'float',
		'inventario' => 'float',
		'costos_ventas' => 'float',
		'pasivos_corriente' => 'float',
		'cuentas_cobrar' => 'float',
		'cuentas_pagar' => 'float',
		'estado' => 'int'
	];

	protected $fillable = [
		'nit',
		'nombre_empresa',
		'direccion',
		'telefono',
		'ventas_netas',
		'activo_corriente',
		'inventario',
		'costos_ventas',
		'pasivos_corriente',
		'cuentas_cobrar',
		'cuentas_pagar',
		'estado',
		'balance_general',
		'estado_resultado'
	];

	public function ventas()
	{
		return $this->hasMany(Venta::class, 'idCliente_juridico');
	}
}
