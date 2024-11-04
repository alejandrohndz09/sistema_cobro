<?php

namespace App\Http\Controllers;

use App\Models\Activo;
use App\Models\Bien;
use App\Models\Categoria;
use App\Models\Departamento;
use App\Models\Empresa;
use App\Models\Sucursal;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sucursales = Sucursal::where('estado', 1)->get();
        $departamentos = Departamento::where('estado', 1)->get();
        $empresas = Empresa::where('estado', 1)->get();
        $activos = Activo::where('estado', 1)->get();
        $categorias = Categoria::where('estado', 1)->get();

        return view('activos.index', compact('departamentos', 'sucursales', 'empresas', 'activos', 'categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function pdf(Request $request)
    {
        // Configurar los parámetros iniciales
        $idSucursal = $request->input('sucursal');
        $idDepartamento = $request->input('departamento');
        $idActivo  = $request->input('activo');
        $tipoDepreciacion = $request->input('tipo');
        $idEmpresa = $request->input('empresa');

        // Obtener el nombre de la empresa según el id proporcionado
        $empresa = Empresa::find($idEmpresa);
        $nombreEmpresa = $empresa ? $empresa->nombre : 'Nombre no encontrado';

        // Preparar la consulta SQL para llamar al procedimiento almacenado
        $results = DB::select(
            'CALL ObtenerDepreciacion(?, ?, ?, ?, ?)',
            [$tipoDepreciacion, $idSucursal, $idDepartamento, $idEmpresa, $idActivo]
        );

        // Calcular el total de activos
        $totalActivos = count($results); // Contar el número de filas en los resultados

        // Calcular los totales para cada columna
        $totalPrecio = 0;
        $totalDepreciacion = 0;
        $totalDepreciacionAcumulada = 0;
        $totalValorEnLibros = 0;

        foreach ($results as $resultado) {
            $totalPrecio += $resultado->precio;
            $totalDepreciacion += $resultado->depreciacion;
            $totalDepreciacionAcumulada += $resultado->depreciacion_acumulada;
            $totalValorEnLibros += $resultado->valor_en_libros;
        }

        // Verificar si no se encontraron datos
        if (empty($results)) {
            return response()->json([
                'type' => 'info',
                'message' => 'No existen registros para generar el informe.',
            ]);
        } else {
            // Pasar los resultados a la vista y generar el PDF
            $pdf = Pdf::loadView(
                'activos.pdf',
                [
                    'resultados' => $results,
                    'tipoDepreciacion' => $tipoDepreciacion,
                    'nombreEmpresa' => $nombreEmpresa, // Pasar el nombre de la empresa a la vista
                    'totalActivos' => $totalActivos,
                    'totalDepreciacion' => $totalDepreciacion,
                    'totalDepreciacionAcumulada' => $totalDepreciacionAcumulada,
                    'totalValorEnLibros' => $totalValorEnLibros,
                ]
            );

            // Convertir el PDF a base64 para enviarlo mediante JSON
            $pdfBase64 = base64_encode($pdf->output());

            return response()->json([
                'type' => 'success',
                'pdf' => $pdfBase64,
            ]);
        }
    }
}
