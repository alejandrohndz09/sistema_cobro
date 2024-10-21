<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `ObtenerValorTotalActivos`(IN `id_categoria` VARCHAR(10), IN `id_sucursal` VARCHAR(10), IN `id_departamento` VARCHAR(10), IN `id_bien` VARCHAR(10), IN `id_empresa` VARCHAR(10))
BEGIN
    SELECT 
        c.idCategoria AS CategoriaID,
        c.nombre AS CategoriaNombre,
        SUM(b.precio) AS ValorTotalActivos
    FROM bien b
    INNER JOIN activo a ON b.idActivo = a.idActivo
    INNER JOIN categoria c ON a.idCategoria = c.idCategoria
    INNER JOIN depto d ON b.idDepartamento = d.idDepartamento
    INNER JOIN sucursal s ON d.idSucursal = s.idSucursal
    INNER JOIN empresa e ON s.idEmpresa = e.idEmpresa  -- Asegúrate de que la tabla empresa esté correctamente unida
    WHERE 
        (c.idCategoria = id_categoria OR id_categoria IS NULL)
        AND (s.idSucursal = id_sucursal OR id_sucursal IS NULL)
        AND (d.idDepartamento = id_departamento OR id_departamento IS NULL)
        AND (b.idBien = id_bien OR id_bien IS NULL)
        AND (e.idEmpresa = id_empresa OR id_empresa IS NULL)  -- Filtrar por empresa
    GROUP BY c.idCategoria, c.nombre;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS ObtenerValorTotalActivos");
    }
};
