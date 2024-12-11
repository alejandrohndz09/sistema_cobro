<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Empresa;
use App\Models\Sucursal;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $empresas = Empresa::all();
        $sucursales = Sucursal::all();

        return view('opciones.empresa.index', compact('empresas', 'sucursales'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'nombre' => 'required|min:3|unique:departamento'

        ], [
            'nombre.unique' => 'Esta departamento ya ha sido ingresado.',
        ]);

        $departamento = new Departamento();
        $departamento->idDepartamento = $this->generarId();
        $departamento->nombre = $request->post('nombre');
        $departamento->idSucursal = $request->post('idSucursal');
        $departamento->estado = 1;
        $departamento->save();


        $alert = array(
            'type' => 'success',
            'message' => 'Operación exitosa.',
        );

        return response()->json($alert);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sucursal = Sucursal::find($id);
        $departamentos = $sucursal->departamentos;

        // Retornar la vista con los datos separados
        return view('opciones.empresa.departamentos.index', compact('sucursal', 'departamentos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $departamento = Departamento::with('sucursal')->find($id);
        return response()->json($departamento);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        // Validar la solicitud
        $request->validate([
            'idSucursal' => 'required',
            'nombre' => 'required|min:3|unique:departamento,nombre,' . $id . ',idDepartamento'

        ], [
            'nombre.unique' => 'Este departamento ya esta registrado.',
        ]);

        $departamento = Departamento::find($id);
        $departamento->nombre = $request->post('nombre');
        $departamento->idSucursal = $request->post('idSucursal');
        $departamento->save();


        $alert = array(
            'type' => 'success',
            'message' => 'Operación exitosa.',
        );
        return response()->json($alert);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $departamento = Departamento::find($id);

        // Verificar si el departamento tiene relaciones con la tabla 'venta'
        $relacionVenta = DB::table('venta')
            ->where('idEmpleado', $departamento->idEmpleado) // Asegúrate de usar el campo correcto para la relación
            ->exists(); // Verifica si hay registros relacionados

        if ($relacionVenta) {
            return response()->json([
                'type' => 'error',
                'message' => 'No se puede eliminar el departamento porque tiene registros relacionados en la tabla Venta.'
            ]);
        }

        // Verificar si el departamento tiene registros en la tabla 'bien' o 'empleado'
        $relacionBien = DB::table('bien')->where('idDepartamento', $departamento->idDepartamento)->exists();
        $relacionEmpleado = DB::table('empleado')->where('idDepartamento', $departamento->idDepartamento)->exists();

        // Si el departamento tiene registros relacionados, no permitir la eliminación
        if ($relacionBien || $relacionEmpleado) {
            return response()->json([
                'type' => 'error',
                'message' => 'El departamento no puede ser eliminado porque tiene registros relacionados en Bien o Empleado.'
            ]);
        }

        // Si no tiene relaciones, proceder con la eliminación
        $departamento->delete();

        return response()->json([
            'type' => 'success',
            'message' => 'El departamento ha sido eliminado exitosamente.'
        ]);
    }



    public function baja($id)
    {
        $departamento = Departamento::find($id);
        $departamento->estado = 0;
        $departamento->save();

        $alert = array(
            'type' => 'success',
            'message' => 'El registro se ha deshabilitado exitosamente'
        );
        return response()->json($alert);
    }

    public function alta($id)
    {
        $departamento = Departamento::find($id);
        $departamento->estado = 1;
        $departamento->save();

        $alert = array(
            'type' => 'success',
            'message' => 'El registro se ha restaurado exitosamente'
        );
        return response()->json($alert);
    }

    public function generarId()
    {
        // Obtener el último registro de la tabla "departamento"
        $ultimoDepartamento = Departamento::latest('idDepartamento')->first();

        if (!$ultimoDepartamento) {
            // Si no hay registros previos, comenzar desde CA0001
            $nuevoId = 'DP0001';
        } else {
            // Obtener el número del último idDepartamento
            $ultimoNumero = intval(substr($ultimoDepartamento->idDepartamento, 2));

            // Incrementar el número para el nuevo registro
            $nuevoNumero = $ultimoNumero + 1;

            // Formatear el nuevo idDepartamento con ceros a la izquierda
            $nuevoId = 'DP' . str_pad($nuevoNumero, 4, '0', STR_PAD_LEFT);
        }

        return $nuevoId;
    }

    public function getDepartamentos($id)
    {
        // Obtener departamentos relacionados con la sucursal especificada
        $departamentos = Departamento::where('idSucursal', $id)->get();

        return response()->json($departamentos);
    }
}
