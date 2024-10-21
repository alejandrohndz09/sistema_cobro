<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Departamento
 * 
 * @property string $idDepartamento
 * @property string $nombre
 * @property int $estado
 * @property string|null $idSucursal
 * 
 * @property Sucursal|null $sucursal
 * @property Collection|Bien[] $bienes
 * @property Collection|Empleado[] $empleados
 *
 * @package App\Models
 */
class Departamento extends Model
{
	protected $table = 'departamento';
	protected $primaryKey = 'idDepartamento';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'estado' => 'int'
	];

	protected $fillable = [
		'nombre',
		'estado',
		'idSucursal'
	];

	public function sucursal()
	{
		return $this->belongsTo(Sucursal::class, 'idSucursal');
	}

	public function bienes()
	{
		return $this->hasMany(Bien::class, 'idDepartamento');
	}

	public function empleados()
	{
		return $this->hasMany(Empleado::class, 'idDepartamento');
	}
}
