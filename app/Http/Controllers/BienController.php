<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Bien;

class BienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store($idActivo, Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'fechaAdquisicion' => 'required|date|before_or_equal:today',
            'precioAdquisicion' => 'required|numeric|min:0.01',
            'sucursal.*' => 'required',
            'departamento.*' => 'required',
            'cantidad.*' => 'required|numeric|min:1',
        ], [
            'fechaAdquisicion.before_or_equal' => 'La fecha ingresada no debe ser mayor a la de ahora.',
            'sucursal.*.required' => 'Seleccione una sucursal.',
            'departamento.*.required' => 'Seleccione un departamento.',
            'cantidad.*.required' => 'Ingrese una cantidad.',
        ]);

        DB::beginTransaction(); // Iniciar transacción
        try {
            // Obtener los datos de bienes en lotes
            $cantidadesArray = $request->input('cantidad');
            $departamentosArray = $request->input('departamento');

            // Insertar bienes en lotes
            foreach ($cantidadesArray as $index => $cantidad) {
                $idDepartamento = $departamentosArray[$index];
                $bienes = [];

                for ($i = 0; $i < $cantidad; $i++) {
                    $bienes[] = [
                        'idBien' => $this->generarBienId($i),
                        'descripcion' => '',
                        'fechaAdquisicion' => $request->fechaAdquisicion,
                        'precio' => $request->precioAdquisicion,
                        'estado' => 1,
                        'idDepartamento' => $idDepartamento,
                        'idActivo' => $idActivo,
                    ];
                }
                Bien::insert($bienes);
            }
            DB::commit(); // Confirmar transacción
            $alert = [
                'type' => 'success',
                'message' => 'Operación exitosa.',
            ];
            return response()->json($alert);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], 500);
        }
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
    public function edit($idActivo, string $id)
    {
        $bien = Bien::find($id);
        return response()->json($bien);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($idActivo, Request $request, string $id) {
        $request->validate([
            'fechaAdquisicion' => 'required|date|before_or_equal:today',
            'precioAdquisicion' => 'required|numeric|min:0.01',
            'sucursal' => 'required',
            'departamento' => 'required',
        ], [
            'fechaAdquisicion.before_or_equal' => 'La fecha ingresada no debe ser mayor a la de ahora.',
            'sucursal.required' => 'Seleccione una sucursal.',
            'departamento.required' => 'Seleccione un departamento.',
        ]);
        
        $bien = Bien::find($id);
        $bien->fechaAdquisicion = $request->post('fechaAdquisicion');
        $bien->precio = $request->post('precioAdquisicion');
        $bien->idDepartamento = $request->post('departamento');
     
        $bien->save();


        $alert = array(
            'type' => 'success',
            'message' => 'Operación exitosa.',
        );
        return response()->json($alert);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getBienes($idActivo)
    {
        $bienes = Bien::with(['activo', 'departamento'])->where('idActivo', $idActivo) // Asegúrate de tener la relación 'categoria' en tu modelo Activo
            ->get(); // Ajusta esto según tus necesidades

        // Iterar sobre cada bien para agregar el valor actual
        foreach ($bienes as $bien) {
            $valorActual = $bien->obtenerValorEnLibros();
            $bien->valorActual = $valorActual[0] >= 0 ? $valorActual[0] : 0; // Si el valor es negativo, asignar 0
        }
        return response()->json($bienes);
    }

    public function generarBienId($i)
    {
        // Obtener el último registro de la tabla "activo"
        $ultimoBien = Bien::latest('idBien')->first();

        if (!$ultimoBien) {
            // Si no hay registros previos, comenzar desde BN0001
            $nuevoId = 'BN0001';
        } else {
            // Obtener el número del último idBien
            $ultimoNumero = intval(substr($ultimoBien->idBien, 2));

            // Incrementar el número para el nuevo registro
            $nuevoNumero = $ultimoNumero + 1 + $i;

            // Formatear el nuevo idActivo con ceros a la izquierda
            $nuevoId = 'BN' . str_pad($nuevoNumero, 4, '0', STR_PAD_LEFT);
        }

        return $nuevoId;
    }
}
