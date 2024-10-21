<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Categoria
 * 
 * @property string $idCategoria
 * @property string $nombre
 * @property float $depreciacion_anual
 * @property int $estado
 * 
 * @property Collection|Activo[] $activos
 *
 * @package App\Models
 */
class Categoria extends Model
{
	protected $table = 'categoria';
	protected $primaryKey = 'idCategoria';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'depreciacion_anual' => 'float',
		'estado' => 'int'
	];

	protected $fillable = [
		'nombre',
		'depreciacion_anual',
		'estado'
	];

	public function activos()
	{
		return $this->hasMany(Activo::class, 'idCategoria');
	}
}
