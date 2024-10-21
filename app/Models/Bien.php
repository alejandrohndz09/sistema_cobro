<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Bien
 * 
 * @property string $idBien
 * @property string $descripcion
 * @property Carbon $fechaAdquisicion
 * @property float $precio
 * @property int $estado
 * @property string|null $idDepartamento
 * @property string|null $idActivo
 * 
 * @property Departamento|null $departamento
 * @property Activo|null $activo
 *
 * @package App\Models
 */
class Bien extends Model
{
	protected $table = 'bien';
	protected $primaryKey = 'idBien';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'fechaAdquisicion' => 'datetime',
		'precio' => 'float',
		'estado' => 'int'
	];

	protected $fillable = [
		'descripcion',
		'fechaAdquisicion',
		'precio',
		'estado',
		'idDepartamento',
		'idActivo'
	];

	public function departamento()
	{
		return $this->belongsTo(Departamento::class, 'idDepartamento');
	}

	public function activo()
	{
		return $this->belongsTo(Activo::class, 'idActivo');
	}
}
