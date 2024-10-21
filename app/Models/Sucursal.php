<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Sucursal
 * 
 * @property string $idSucursal
 * @property string $telefono
 * @property string $direccion
 * @property string $ubicacion
 * @property int $estado
 * @property string|null $idEmpresa
 * 
 * @property Empresa|null $empresa
 * @property Collection|Departamento[] $departamentos
 *
 * @package App\Models
 */
class Sucursal extends Model
{
	protected $table = 'sucursal';
	protected $primaryKey = 'idSucursal';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'estado' => 'int'
	];

	protected $fillable = [
		'telefono',
		'direccion',
		'ubicacion',
		'estado',
		'idEmpresa'
	];

	public function empresa()
	{
		return $this->belongsTo(Empresa::class, 'idEmpresa');
	}

	public function departamentos()
	{
		return $this->hasMany(Departamento::class, 'idSucursal');
	}
}
