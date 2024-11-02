<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
	public function obtenerValorEnLibros()
    {
        $resultados = DB::select("CALL ObtenerDepreciacion(?, NULL, NULL, NULL,NULL, ?)", [
            'diaria',
			$this->idBien
        ]);

        // Filtrar solo la columna `valor_en_libros`
        $valoresEnLibros = array_map(function ($row) {
            return $row->valor_en_libros;
        }, $resultados);

        return $valoresEnLibros;
    }
}
