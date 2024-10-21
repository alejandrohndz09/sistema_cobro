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
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `ObtenerDepreciacion`(IN `tipo_depreciacion` VARCHAR(10), IN `id_sucursal` VARCHAR(10), IN `id_departamento` VARCHAR(10), IN `id_bien` VARCHAR(10))
BEGIN
    SELECT 
        CONCAT(s.idSucursal, '-', d.idDepartamento, '-', a.idActivo, '-', b.idBien) AS Codigo,
        a.nombre,
        b.fechaAdquisicion,
        b.precio,
        c.depreciacion_anual,
        CASE 
            WHEN tipo_depreciacion = 'anual' THEN ROUND(b.precio * c.depreciacion_anual, 2)
            WHEN tipo_depreciacion = 'mensual' THEN ROUND((b.precio * c.depreciacion_anual) / 12, 2)
            WHEN tipo_depreciacion = 'diaria' THEN ROUND((b.precio * c.depreciacion_anual) / 365, 2)
            ELSE 0
        END AS depreciacion,
        CASE 
            WHEN tipo_depreciacion = 'anual' THEN ROUND(b.precio * c.depreciacion_anual, 2) * (YEAR(CURDATE()) - YEAR(b.fechaAdquisicion))
            WHEN tipo_depreciacion = 'mensual' THEN ROUND((b.precio * c.depreciacion_anual) / 12, 2) * ((YEAR(CURDATE()) - YEAR(b.fechaAdquisicion)) * 12 + (MONTH(CURDATE()) - MONTH(b.fechaAdquisicion)))
            WHEN tipo_depreciacion = 'diaria' THEN ROUND((b.precio * c.depreciacion_anual) / 365, 2) * DATEDIFF(CURDATE(), b.fechaAdquisicion)
            ELSE 0
        END AS depreciacion_acumulada,
        (b.precio - 
            CASE 
                WHEN tipo_depreciacion = 'anual' THEN ROUND(b.precio * c.depreciacion_anual, 2) * (YEAR(CURDATE()) - YEAR(b.fechaAdquisicion))
                WHEN tipo_depreciacion = 'mensual' THEN ROUND((b.precio * c.depreciacion_anual) / 12, 2) * ((YEAR(CURDATE()) - YEAR(b.fechaAdquisicion)) * 12 + (MONTH(CURDATE()) - MONTH(b.fechaAdquisicion)))
                WHEN tipo_depreciacion = 'diaria' THEN ROUND((b.precio * c.depreciacion_anual) / 365, 2) * DATEDIFF(CURDATE(), b.fechaAdquisicion)
                ELSE 0
            END
        ) AS valor_en_libros
    FROM bien b
    INNER JOIN depto d ON b.idDepartamento = d.idDepartamento
    INNER JOIN sucursal s ON d.idSucursal = s.idSucursal
    INNER JOIN activo a ON b.idActivo = a.idActivo
    INNER JOIN categoria c ON a.idCategoria = c.idCategoria
    WHERE 
        (s.idSucursal = id_sucursal OR id_sucursal IS NULL)
        AND (d.idDepartamento = id_departamento OR id_departamento IS NULL)
        AND (b.idBien = id_bien OR id_bien IS NULL)
    GROUP BY b.idBien, a.nombre, b.fechaAdquisicion, b.precio, c.depreciacion_anual;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS ObtenerDepreciacion");
    }
};
