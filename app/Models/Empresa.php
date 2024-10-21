<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Empresa
 * 
 * @property string $idEmpresa
 * @property string $nit
 * @property string $nombre
 * @property string $logo
 * @property int $estado
 * @property string|null $idEmpleado
 * 
 * @property Empleado|null $empleado
 * @property Collection|Sucursal[] $sucursales
 *
 * @package App\Models
 */
class Empresa extends Model
{
	protected $table = 'empresa';
	protected $primaryKey = 'idEmpresa';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'estado' => 'int'
	];

	protected $fillable = [
		'nit',
		'nombre',
		'logo',
		'estado',
		'idEmpleado'
	];

	public function empleado()
	{
		return $this->belongsTo(Empleado::class, 'idEmpleado');
	}

	public function sucursales()
	{
		return $this->hasMany(Sucursal::class, 'idEmpresa');
	}
}
