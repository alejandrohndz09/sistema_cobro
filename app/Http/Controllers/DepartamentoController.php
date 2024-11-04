<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Sucursal;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departamentos = Departamento::all();
        $sucursales = Sucursal::all();

        return view('opciones.empresa.departamento.index', compact('departamentos', 'sucursales'));
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
            'idSucursal' => 'required',
            'nombre' => 'required|min:3|unique:departamento'

        ], [
            'nombre.unique' => 'Esta categoría ya ha sido ingresada.',
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
    public function show($id) {}

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
        if ($departamento->activos === 0) {
            $departamento->delete();
            $alert = array(
                'type' => 'success',
                'message' => 'El registro se ha eliminado exitosamente'
            );
        } else {
            $alert = array(
                'type' => 'error',
                'message' => 'No se puede eliminar el registro porque tiene datos asociados'
            );
        }

        return response()->json($alert);
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

    public function getDepartamentos()
    {
        $departamentos = Departamento::all(); // Ajusta esto según tus necesidades
        return response()->json($departamentos);
    }
}
