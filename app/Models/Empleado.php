<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Empleado
 * 
 * @property string $idEmpleado
 * @property string $dui
 * @property string $nombres
 * @property string $apellidos
 * @property string $cargo
 * @property int $estado
 * @property string|null $idDepartamento
 * 
 * @property Departamento|null $departamento
 * @property Collection|Compra[] $compras
 * @property Collection|Empresa[] $empresas
 * @property Collection|Usuario[] $usuarios
 * @property Collection|Venta[] $venta
 *
 * @package App\Models
 */
class Empleado extends Model
{
	protected $table = 'empleado';
	protected $primaryKey = 'idEmpleado';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'estado' => 'int'
	];

	protected $fillable = [
		'dui',
		'nombres',
		'apellidos',
		'cargo',
		'estado',
		'idDepartamento'
	];

	public function departamento()
	{
		return $this->belongsTo(Departamento::class, 'idDepartamento');
	}
	
	
	public function compras()
	{
		return $this->hasMany(Compra::class, 'idEmpleado');
	}

	public function empresas()
	{
		return $this->hasMany(Empresa::class, 'idEmpleado');
	}

	public function usuarios()
	{
		return $this->hasMany(Usuario::class, 'idEmpleado');
	}

	public function ventas()
	{
		return $this->hasMany(Venta::class, 'idEmpleado');
	}
}
