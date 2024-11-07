<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Departamento;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empleados = Empleado::with('departamento')->get();
        $departamentos = Departamento::all();

        return view('opciones.empleados.index')->with([
            'empleados' => $empleados,
            'departamentos' => $departamentos,
        ]);
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
        // Validar la solicitud
        $request->validate([
            'dui' => 'required|string|max:10|unique:empleado',
            'nombres' => 'required|string|max:50',
            'apellidos' => 'required|string|max:50',
            'cargo' => 'required|string|max:50',
            'idDepartamento' => 'required|string|max:6',
        ], [
            'dui.unique' => 'El DUI ya ha sido ingresado.',
            'nombres.*.required' => 'Ingrese un nombre.',
            'apellidos.*.required' => 'Ingrese un apellido.',
            'cargo.*.required' => 'Ingrese un cargo.',
            'departamento.*.required' => 'Seleccione un departamento.',

        ]);


        $empleado = new Empleado();
        $empleado->idEmpleado = $this->generarId();
        $empleado->dui = $request->post('dui');
        $empleado->nombres = $request->post('nombres');
        $empleado->apellidos = $request->post('apellidos');
        $empleado->cargo = $request->post('cargo');
        $empleado->estado = 1;
        $empleado->idDepartamento = $request->post('idDepartamento');
        $empleado->save();


        $alert = array(
            'type' => 'success',
            'message' => 'Operación exitosa.',
        );

        return response()->json($alert);
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
    public function edit($id)
    {
        $empleado = Empleado::find($id);
        return response()->json($empleado);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id)
    {
        // Validar la solicitud
        $request->validate([
            'dui' => 'required|string|max:10|unique:empleado,dui,' . $id . ',idEmpleado',
            'nombres' => 'required|string|max:50',
            'apellidos' => 'required|string|max:50',
            'cargo' => 'required|string|max:50',
            //  'idDepartamento' => 'required|integer' // Asegúrate de validar este campo también
        ], [
            'dui.unique' => 'El DUI ya ha sido ingresado.',
            'nombres.required' => 'Ingrese un nombre.',
            'apellidos.required' => 'Ingrese un apellido.',
            'cargo.required' => 'Ingrese un cargo.',
        ]);

        // Buscar al empleado por ID
        $empleado = Empleado::findOrFail($id);
        // Actualizar los datos del empleado
        $empleado->dui = $request->input('dui');
        $empleado->nombres = $request->input('nombres');
        $empleado->apellidos = $request->input('apellidos');
        $empleado->cargo = $request->input('cargo');
        $empleado->idDepartamento = $request->input('idDepartamento');

        // Guardar los cambios
        $empleado->save();

        // Preparar respuesta de éxito
        $alert = [
            'type' => 'success',
            'message' => 'Operación exitosa.',
        ];

        // Retornar respuesta JSON
        return response()->json($alert);
    }


    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id)
    {
        // Busca el empleado por ID
        $empleado = Empleado::find($id);

        // Verifica si el empleado existe
        if (!$empleado) {
            return response()->json([
                'type' => 'error',
                'message' => 'Registro no encontrado'
            ], 404);
        }

        // Verifica si registros asociados
        if ($empleado->ventas()->exists() || $empleado->compras()->exists() || $empleado->empresas()->exists()) {
            return response()->json([
                'type' => 'error',
                'message' => 'No se puede eliminar el registro porque tiene datos asociados.'
            ]);
        }

        // Elimina el empleado si no tiene un registros asociados
        $empleado->delete();
        return response()->json([
            'type' => 'success',
            'message' => 'El registro se ha eliminado exitosamente'
        ]);
    }

    public function baja($id)
    {
        $empleado = Empleado::find($id);
        $empleado->estado = 0;
        $empleado->save();

        $alert = array(
            'type' => 'success',
            'message' => 'El registro se ha deshabilitado exitosamente'
        );
        return response()->json($alert);
    }

    public function alta($id)
    {
        $empleado = Empleado::find($id);
        $empleado->estado = 1;
        $empleado->save();

        $alert = array(
            'type' => 'success',
            'message' => 'El registro se ha restaurado exitosamente'
        );
        return response()->json($alert);
    }

    public function generarId()
    {
        // Obtener el último registro de la tabla "empleado"
        $ultimoEmpleado = Empleado::latest('idEmpleado')->first();

        if (!$ultimoEmpleado) {
            $nuevoId = 'EM0001';
        } else {
            // Obtener el número del último idempleado
            $ultimoNumero = intval(substr($ultimoEmpleado->idEmpleado, 2));

            // Incrementar el número para el nuevo registro
            $nuevoNumero = $ultimoNumero + 1;

            // Formatear el nuevo idempleado con ceros a la izquierda
            $nuevoId = 'EM' . str_pad($nuevoNumero, 4, '0', STR_PAD_LEFT);
        }

        return $nuevoId;
    }


    public function getEmpleados()
    {
        try {
            $empleados = Empleado::with('departamento')->get();
            return response()->json($empleados);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener empleados.'], 500);
        }
    }


    public function getDepartamentos()
    {
        $departamentos = Departamento::where('estado', 1)->get(); // Ajusta esto según tus necesidades
        return response()->json($departamentos);
    }
}
