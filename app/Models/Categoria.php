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

	public function obtenerValorAcumulado()
    {
        // Iterar sobre los activos y sumar sus valores acumulados
        return $this->activos->reduce(function ($carry, $activo) {
            if ($activo->estado === 1) { // Filtrar activos con estado 1
                $valorActual = $activo->obtenerValorAcumulado(); // Este valor ya es un número
                return $carry + $valorActual;
            }
            return $carry; // No sumamos si el estado no es 1
        }, 0);
    }
}
