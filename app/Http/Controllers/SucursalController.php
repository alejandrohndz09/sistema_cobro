<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Sucursal;
use Illuminate\Http\Request;

class SucursalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $sucursales = Sucursal::all();

        return view('opciones.empresa.index', compact('sucursales'));
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
        // Validar los datos del formulario
        $request->validate([
            'telefono' => 'required|regex:/^[0-9]{4}-[0-9]{4}$/',
            'direccion' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
        ]);

        $sucursal = new Sucursal();
        $sucursal->idSucursal = $this->generarId();
        $sucursal->telefono = $request->post('telefono');
        $sucursal->direccion = $request->post('direccion');
        $sucursal->ubicacion = $request->post('ubicacion');
        $sucursal->estado = 1;
        $sucursal->idEmpresa = 'EP0007';

        $sucursal->save();

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
    public function edit(string $id)
    {
        //
        $sucursal = Sucursal::find($id);
        return response()->json($sucursal);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        // Validar la solicitud
        $request->validate([
            'telefono' => 'required|regex:/^[0-9]{4}-[0-9]{4}$/', // Ejemplo para validar que sean 8 dígitos numéricos
            'direccion' => 'required|min:5', // Mínimo 5 caracteres en la dirección
            'ubicacion' => 'required|min:3', // Mínimo 3 caracteres en la ubicación
        ], [
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.regex' => 'El teléfono debe tener 8 dígitos numéricos.',
            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.min' => 'La dirección debe tener al menos 5 caracteres.',
            'ubicacion.required' => 'La ubicación es obligatoria.',
            'ubicacion.min' => 'La ubicación debe tener al menos 3 caracteres.',
        ]);

        $sucursal = Sucursal::find($id);
        $sucursal->telefono = $request->post('telefono');
        $sucursal->direccion = $request->post('direccion');
        $sucursal->ubicacion = $request->post('ubicacion');
        $sucursal->estado = 1;
        $sucursal->idEmpresa = 'EP0007';
        $sucursal->save();

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
        //ELIMINAR SUCURSAL
        $sucursal = Sucursal::find($id);
        if ($sucursal->activos === null) {
            $sucursal->delete();
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

    public function getSucursales()
    {
        $empresas = Empresa::all(); // Obtiene todas las empresas
        $sucursales = Sucursal::all(); // Obtiene todas las sucursales

        return response()->json($sucursales);
    }


    public function bajaSucursal($id)
    {
        $sucursal = Sucursal::find($id);
        $sucursal->estado = 0;
        $sucursal->save();

        $alert = array(
            'type' => 'success',
            'message' => 'El registro se ha deshabilitado exitosamente'
        );
        return response()->json($alert);
    }

    public function altaSucursal($id)
    {
        $sucursal = Sucursal::find($id);
        $sucursal->estado = 1;
        $sucursal->save();

        $alert = array(
            'type' => 'success',
            'message' => 'El registro se ha restaurado exitosamente'
        );
        return response()->json($alert);
    }

    public function generarId()
    {
        // Obtener el último registro de la tabla "sucursal"
        $ultimoSucursal = Sucursal::latest('idSucursal')->first();

        if (!$ultimoSucursal) {
            // Si no hay registros previos, comenzar desde CA0001
            $nuevoId = 'SC0001';
        } else {
            // Obtener el número del último idSucursal
            $ultimoNumero = intval(substr($ultimoSucursal->idSucursal, 2));

            // Incrementar el número para el nuevo registro
            $nuevoNumero = $ultimoNumero + 1;

            // Formatear el nuevo idSucursal con ceros a la izquierda
            $nuevoId = 'SC' . str_pad($nuevoNumero, 4, '0', STR_PAD_LEFT);
        }

        return $nuevoId;
    }
}
