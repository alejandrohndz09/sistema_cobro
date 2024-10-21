<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Usuario
 * 
 * @property string $idusuario
 * @property string $usuario
 * @property string $token
 * @property string $clave
 * @property int $estado
 * @property string $idEmpleado
 * 
 * @property Empleado $empleado
 *
 * @package App\Models
 */
class Usuario extends Model
{
	protected $table = 'usuario';
	protected $primaryKey = 'idusuario';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'estado' => 'int'
	];

	protected $hidden = [
		'token'
	];

	protected $fillable = [
		'usuario',
		'token',
		'clave',
		'estado',
		'idEmpleado'
	];

	public function empleado()
	{
		return $this->belongsTo(Empleado::class, 'idEmpleado');
	}
}
