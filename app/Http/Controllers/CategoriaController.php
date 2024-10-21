<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias = Categoria::all();
        return view('activos.categorias.index')->with('categorias', $categorias);
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
            'depreciacion' => 'required',
            'nombre' => 'required|min:3|unique:categoria'

        ], [
            'nombre.unique' => 'Esta categoría ya ha sido ingresada.',
        ]);

        $categoria = new Categoria();
        $categoria->idCategoria = $this->generarId();
        $categoria->nombre = $request->post('nombre');
        $categoria->depreciacion_anual = $request->post('depreciacion') / 100;
        $categoria->estado = 1;
        $categoria->save();


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
        $categoria = Categoria::find($id);
        return response()->json($categoria);
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
            'depreciacion' => 'required',
            'nombre' => 'required|min:3|unique:categoria,nombre,' . $id . ',idCategoria'

        ], [
            'nombre.unique' => 'Esta categoría ya ha sido ingresada.',
        ]);

        $categoria = Categoria::find($id);
        $categoria->nombre = $request->post('nombre');
        $categoria->depreciacion_anual = $request->post('depreciacion') / 100;
        $categoria->estado = 1;
        $categoria->save();


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
        $categoria = Categoria::find($id);
        if ($categoria->activos->isEmpty()) {
            $categoria->delete();
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
        $categoria = Categoria::find($id);
        $categoria->estado = 0;
        $categoria->save();

        $alert = array(
            'type' => 'success',
            'message' => 'El registro se ha deshabilitado exitosamente'
        );
        return response()->json($alert);
    }

    public function alta($id)
    {
        $categoria = Categoria::find($id);
        $categoria->estado = 1;
        $categoria->save();

        $alert = array(
            'type' => 'success',
            'message' => 'El registro se ha restaurado exitosamente'
        );
        return response()->json($alert);
    }

    public function generarId()
    {
        // Obtener el último registro de la tabla "categoria"
        $ultimoCategoria = Categoria::latest('idCategoria')->first();

        if (!$ultimoCategoria) {
            // Si no hay registros previos, comenzar desde CA0001
            $nuevoId = 'CA0001';
        } else {
            // Obtener el número del último idCategoria
            $ultimoNumero = intval(substr($ultimoCategoria->idCategoria, 2));

            // Incrementar el número para el nuevo registro
            $nuevoNumero = $ultimoNumero + 1;

            // Formatear el nuevo idCategoria con ceros a la izquierda
            $nuevoId = 'CA' . str_pad($nuevoNumero, 4, '0', STR_PAD_LEFT);
        }

        return $nuevoId;
    }

    public function getCategorias()
    {
        $categorias = Categoria::all(); // Ajusta esto según tus necesidades
        return response()->json($categorias);
    }
}
