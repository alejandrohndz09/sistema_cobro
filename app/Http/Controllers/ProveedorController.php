<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index() {
        // Obtener todos los proveedores ordenados por IdProveedor
        $proveedores = Proveedor::orderBy('IdProveedor', 'ASC')->get(); // Cambiar a 'DESC' si quieres los más recientes primero
        return view('opciones.proveedores.index', compact('proveedores'));
    }

    public function store(Request $request) {
        $request->validate([
            'nombre' => 'required|min:3',
            'direccion' => 'required|min:3',
            'telefono' => 'required|min:3',
            'correo' => 'required|min:3'
        ]);
        
         $proveedor = new Proveedor();
         $proveedor->IdProveedor = $this->generarId();
         $proveedor->nombre = $request->post('nombre');
         $proveedor->direccion = $request->post('direccion');
         $proveedor->telefono = $request->post('telefono');
         $proveedor->correo = $request->post('correo');
         $proveedor->estado = 1;
     
         $proveedor->save();

         $alert = array(
            'type' => 'success',
            'message' => 'Operación exitosa.',
        );
        return response()->json($alert);    }

    public function edit($id) {
        $proveedor = Proveedor::find($id);
        return response()->json($proveedor);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'nombre' => 'required|max:255',
            'direccion' => 'required|max:255',
            'telefono' => 'required|max:255',
            'correo' => 'required|email',
        ]);

        $proveedor = Proveedor::find($id);
        $proveedor->nombre = $request->post('nombre');
        $proveedor->direccion = $request->post('direccion');
        $proveedor->telefono = $request->post('telefono');
        $proveedor->correo = $request->post('correo');
        $proveedor->estado = $request->post('estado', $proveedor->estado);
        $proveedor->save();

        return response()->json(['type' => 'success', 'message' => 'Proveedor actualizado correctamente']);
    }

    public function destroy($id) {
        // Eliminar el proveedor por su ID
        $proveedor = Proveedor::find($id);
        $proveedor->delete();

        return response()->json(['type' => 'success', 'message' => 'Proveedor eliminado correctamente']);
    }

    public function baja($id) {
        $proveedor = Proveedor::find($id);
        $proveedor->estado = 0;
        $proveedor->save();

        return response()->json(['type' => 'success', 'message' => 'El registro se ha deshabilitado exitosamente']);
    }

    public function alta($id) {
        $proveedor = Proveedor::find($id);
        $proveedor->estado = 1;
        $proveedor->save();

        return response()->json(['type' => 'success', 'message' => 'El registro se ha restaurado exitosamente']);
    }

    private function generarId() {
        $ultimoProveedor = Proveedor::orderByRaw('CAST(SUBSTRING(IdProveedor, 3) AS UNSIGNED) DESC')->first();

        if (!$ultimoProveedor) {
            $nuevoId = 'PV0001';
        } else {
            $ultimoNumero = intval(substr($ultimoProveedor->IdProveedor, 2));
            $nuevoId = 'PV' . str_pad($ultimoNumero + 1, 4, '0', STR_PAD_LEFT);
        }

        return $nuevoId;
    }

    public function getProveedores() {
        $proveedores = Proveedor::orderBy('IdProveedor', 'ASC')->get();
        return response()->json($proveedores);
    }
}