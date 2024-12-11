<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Activo
 * 
 * @property string $idActivo
 * @property string $nombre
 * @property int $estado
 * @property string $imagen
 * @property string $descripcion
 * @property string|null $idCategoria
 * 
 * @property Categoria|null $categoria
 * @property Collection|Bien[] $biens
 *
 * @package App\Models
 */
class Activo extends Model
{
	protected $table = 'activo';
	protected $primaryKey = 'idActivo';
	public $incrementing = false;
	public $timestamps = false;
	protected $valorAcumulado=0;

	protected $casts = [
		'estado' => 'int'
	];

	protected $fillable = [
		'nombre',
		'estado',
		'imagen',
		'descripcion',
		'idCategoria'
	];

	public function categoria()
	{
		return $this->belongsTo(Categoria::class, 'idCategoria');
	}

	public function bienes()
	{
		return $this->hasMany(Bien::class, 'idActivo');
	}

	public function obtenerValorAcumulado()
    {
        // Iterar sobre los bienes y calcular la suma de valoresEnLibros
        return $this->bienes->reduce(function ($carry, $bien) {
			if ($bien->estado === 1) { // Filtrar bienes con estado 1
				$valoresEnLibros = $bien->obtenerValorEnLibros();
				$valorActual = array_sum($valoresEnLibros); // Sumamos los valores si hay más de uno
				return $carry + $valorActual;
			}
			return $carry; // No sumamos si el estado no es 1
        }, 0);
    }
}
