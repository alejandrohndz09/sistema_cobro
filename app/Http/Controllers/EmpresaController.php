<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Sucursal; // Importar el modelo de Sucursal
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empresas = Empresa::all();
        $sucursales = Sucursal::all();

        return view('opciones.empresa.index', compact('empresas', 'sucursales'));
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
        $empresa = Empresa::find($id); // Obtén la empresa por su ID, asegúrate de que este valor sea correcto
        return view('nombre_de_la_vista', compact('empresa')); // Pasa la variable $empresa a la vista
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {}

    public function editEmpresa(string $id)
    {
        $empresa = Empresa::find($id);
        return response()->json($empresa);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //

    }

    public function updateEmpresa(Request $request, string $id)
    {
        // Validación para Empresa
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nit' => 'required|regex:/^[0-9]{14}$/',
            'nombre' => 'required|string|min:3|max:100',
        ]);

        $empresa = Empresa::find($id);

        // Actualizar los campos de la empresa
        $empresa->nit = $request->input('nit');
        $empresa->nombre = $request->input('nombre');

        if ($request->hasFile('logo')) {
            // Eliminar la imagen anterior si existe
            if ($empresa->logo) {
                $oldFilePath = public_path($empresa->logo);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            // Procesar y guardar la nueva imagen
            $file = $request->file('logo');
            $filename = time() . '_' . $file->getClientOriginalName(); // Genera un nombre único
            $filePath = 'assets/img/empresas/' . $filename;

            $file->move(public_path('assets/img/empresas'), $filename);
            $empresa->logo = $filePath; // Guarda la nueva ruta en la base de datos
        }

        $empresa->save();

        return response()->json([
            'type' => 'success',
            'message' => 'Empresa actualizada exitosamente.',
            'data' => $empresa
        ]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getEmpresa()
    {
        $empresa = Empresa::all(); // Ajusta esto según tus necesidades
        return response()->json($empresa);
    }
}
