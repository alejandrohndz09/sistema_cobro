<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Proveedor
 * 
 * @property string $IdProveedor
 * @property string $nombre
 * @property string $direccion
 * @property string $telefono
 * @property string $correo
 * @property int $estado
 * 
 * @property Collection|Compra[] $compras
 *
 * @package App\Models
 */
class Proveedor extends Model
{
	protected $table = 'proveedor';
	protected $primaryKey = 'IdProveedor';
	public $incrementing = false;
	public $timestamps = false;
	

	protected $casts = [
		'estado' => 'int'
	];

	protected $fillable = [
		'nombre',
		'direccion',
		'telefono',
		'correo',
		'estado'
	];

	public function compras()
	{
		return $this->hasMany(Compra::class, 'IdProveedor');
	}
}
