<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ClienteNatural
 * 
 * @property string $idCliente_natural
 * @property string $dui
 * @property string $nombres
 * @property string $apellidos
 * @property string $telefono
 * @property string $direccion
 * @property float $ingresos
 * @property float $egresos
 * @property string $lugarTrabajo
 * @property int $estado
 * 
 * @property Collection|Venta[] $venta
 *
 * @package App\Models
 */
class ClienteNatural extends Model
{
	protected $table = 'cliente_natural';
	protected $primaryKey = 'idCliente_natural';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'ingresos' => 'float',
		'egresos' => 'float',
		'estado' => 'int'
	];

	protected $fillable = [
		'dui',
		'nombres',
		'apellidos',
		'telefono',
		'direccion',
		'ingresos',
		'egresos',
		'lugarTrabajo',
		'estado'
	];

	public function ventas()
	{
		return $this->hasMany(Venta::class, 'idCliente_natural');
	}
}
