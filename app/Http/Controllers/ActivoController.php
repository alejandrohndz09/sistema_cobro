<?php

namespace App\Http\Controllers;

use App\Models\Activo;
use App\Models\Bien;
use App\Models\Categoria;
use App\Models\Sucursal;
use App\Models\Empresa;
use App\Models\Departamento;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sucursales = Sucursal::where('estado', 1)->get();
        $departamentos = Departamento::where('estado', 1)->get();
        $empresas = Empresa::where('estado', 1)->get();
        $activos = Activo::all();
        $categorias = Categoria::where('estado', 1)->get();

        //ENVIO DE CONSULTA DE CATEGORIAS
        $categorias = Categoria::with('activos.bienes')->get();

        // Dividir las primeras tres categorías y las demás
        $resultados = $categorias->take(3)->map(function ($categoria) {
            return [
                'nombre' => $categoria->nombre, // Suponiendo que tienes una columna `nombre`
                'valorAcumulado' => $categoria->obtenerValorAcumulado(),
            ];
        });

        // Calcular el total de las categorías restantes
        $otrosValorAcumulado = $categorias->skip(3)->reduce(function ($carry, $categoria) {
            return $carry + $categoria->obtenerValorAcumulado();
        }, 0);

        // Agregar el registro de "Otros bienes" si hay valores acumulados
        if ($otrosValorAcumulado > 0) {
            $resultados->push([
                'nombre' => 'Otros bienes',
                'valorAcumulado' => $otrosValorAcumulado,
            ]);
        }

        // Consulta para obtener la suma de valores agrupados por sucursal
        $datosSucursales = DB::table('sucursal')
            ->join('departamento', 'sucursal.idSucursal', '=', 'departamento.idSucursal')
            ->join('bien', 'departamento.idDepartamento', '=', 'bien.idDepartamento')
            ->select('sucursal.ubicacion as nombre', DB::raw('SUM(bien.precio) as total'))
            ->where('bien.estado', 1) // Opcional: Filtrar bienes activos
            ->groupBy('sucursal.ubicacion')
            ->get();


        return view('activos.index', compact('departamentos', 'sucursales', 'empresas', 'activos', 'categorias', 'resultados', 'datosSucursales'));
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
            'imagen' => 'required|image|max:3000',
            'nombre' => 'required|min:3|unique:activo',
            'categoria' => 'required',
            'descripcion' => 'required',
            'fechaAdquisicion' => 'required|date|before_or_equal:today',
            'precioAdquisicion' => 'required|numeric|min:0.01',
            'sucursal.*' => 'required',
            'departamento.*' => 'required',
            'cantidad.*' => 'required|numeric|min:1',
        ], [
            'imagen.required' => 'La fotografía es necesaria.',
            'nombre.unique' => 'Este activo ya ha sido ingresado.',
            'fechaAdquisicion.before_or_equal' => 'La fecha ingresada no debe ser mayor a la de ahora.',
            'sucursal.*.required' => 'Seleccione una sucursal.',
            'departamento.*.required' => 'Seleccione un departamento.',
            'cantidad.*.required' => 'Ingrese una cantidad.',
        ]);

        DB::beginTransaction(); // Iniciar transacción
        $nombreImagen = null;
        try {
            $activo = new Activo();
            $activo->idActivo = $this->generarId();
            $activo->nombre = $request->post('nombre');
            $activo->idCategoria = $request->post('categoria');
            $activo->descripcion = $request->post('descripcion');
            $activo->estado = 1;
            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                $nombreActivoFormateado = str_replace(' ', '_', $activo->nombre);
                $nombreImagen = $activo->idActivo . '_' . $nombreActivoFormateado . '.' . $imagen->getClientOriginalExtension();
                $rutaImagen = public_path('/assets/img/activos'); // Ruta donde deseas guardar la imagen
                $imagen->move($rutaImagen, $nombreImagen);
                // Aquí puedes guardar $nombreImagen en tu base de datos o realizar otras acciones necesarias.
                $activo->imagen = $nombreImagen;
            }
            $activo->save();

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
                        'idActivo' => $activo->idActivo,
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
            // Eliminar la imagen del directorio si existe
            if ($nombreImagen && file_exists(public_path('/assets/img/activos/' . $nombreImagen))) {
                unlink(public_path('/assets/img/activos/' . $nombreImagen)); // Eliminar imagen
            }
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $activo = Activo::find($id);
        return view('activos.bienes.index')->with('activo', $activo);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $activo = Activo::find($id);
        return response()->json($activo);
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
            'imagen' => 'image|max:3000',
            'nombre' => 'required|min:3|unique:activo,nombre,' . $id . ',idActivo',
            'categoria' => 'required',
            'descripcion' => 'required',
        ], [
            'nombre.unique' => 'Este activo ya ha sido ingresado.',
        ]);



        $activo = Activo::find($id);
        $activo->nombre = $request->post('nombre');
        $activo->idCategoria = $request->post('categoria');
        $activo->descripcion = $request->post('descripcion');
        if ($request->hasFile('imagen')) {
            //primero eliminamos la anterior imagen
            $filePath = public_path('/assets/img/activos/' . $activo->imagen);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            //se procede con el nuevo guardado
            $imagen = $request->file('imagen');
            $nombreActivoFormateado = str_replace(' ', '_', $activo->nombre);
            $nombreImagen = $activo->idActivo . '_' . $nombreActivoFormateado . '.' . $imagen->getClientOriginalExtension();
            $rutaImagen = public_path('/assets/img/activos'); // Ruta donde deseas guardar la imagen
            $imagen->move($rutaImagen, $nombreImagen);
            $activo->imagen = $nombreImagen;
        }
        $activo->save();


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
        $activo = Activo::find($id);
        if ($activo->bienes == null) {
            $activo->delete();
            if (file_exists(public_path('/assets/img/activos/' . $activo->imagen))) {
                unlink(public_path('/assets/img/activos/' . $activo->imagen)); // Eliminar imagen
            }
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
        $activo = Activo::find($id);
        $activo->estado = 0;
        $activo->save();

        $alert = array(
            'type' => 'success',
            'message' => 'El registro se ha deshabilitado exitosamente'
        );
        return response()->json($alert);
    }

    public function alta($id)
    {
        $activo = Activo::find($id);
        $activo->estado = 1;
        $activo->save();

        $alert = array(
            'type' => 'success',
            'message' => 'El registro se ha restaurado exitosamente'
        );
        return response()->json($alert);
    }

    public function generarId()
    {
        // Obtener el último registro de la tabla "activo"
        $ultimoActivo = Activo::latest('idActivo')->first();

        if (!$ultimoActivo) {
            // Si no hay registros previos, comenzar desde CA0001
            $nuevoId = 'AC0001';
        } else {
            // Obtener el número del último idActivo
            $ultimoNumero = intval(substr($ultimoActivo->idActivo, 2));

            // Incrementar el número para el nuevo registro
            $nuevoNumero = $ultimoNumero + 1;

            // Formatear el nuevo idActivo con ceros a la izquierda
            $nuevoId = 'AC' . str_pad($nuevoNumero, 4, '0', STR_PAD_LEFT);
        }

        return $nuevoId;
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

    public function getActivos()
    {

        //ENVIO DE ACTIVOS
        $activos = Activo::with(['categoria', 'bienes']) // Asegúrate de tener la relación 'categoria' en tu modelo Activo
            ->get(); // Ajusta esto según tus necesidades

        foreach ($activos as $activo) {
            $valorActual = $activo->obtenerValorAcumulado();
            $activo->valorAcumulado = $valorActual; // Si el valor es negativo, asignar 0
        }

        //ENVIO DE CONSULTA DE CATEGORIAS
        
        $categorias = Categoria::with('activos.bienes')->get();

        // Dividir las primeras tres categorías y las demás
        $datosCategorias = $categorias->take(3)->map(function ($categoria) {
            return [
                'nombre' => $categoria->nombre, // Suponiendo que tienes una columna `nombre`
                'valorAcumulado' => $categoria->obtenerValorAcumulado(),
            ];
        });

        // Calcular el total de las categorías restantes
        $otrosValorAcumulado = $categorias->skip(3)->reduce(function ($carry, $categoria) {
            return $carry + $categoria->obtenerValorAcumulado();
        }, 0);

        // Agregar el registro de "Otros bienes" si hay valores acumulados
        if ($otrosValorAcumulado > 0) {
            $datosCategorias->push([
                'nombre' => 'Otros bienes',
                'valorAcumulado' => $otrosValorAcumulado,
            ]);
        }
        


        //ENVIO DE CONSULTA DE SUCURSALES

        $datosSucursales = DB::table('sucursal')
            ->join('departamento', 'sucursal.idSucursal', '=', 'departamento.idSucursal')
            ->join('bien', 'departamento.idDepartamento', '=', 'bien.idDepartamento')
            ->select('sucursal.ubicacion as nombre', DB::raw('SUM(bien.precio) as total'))
            ->where('bien.estado', 1) // Opcional: Filtrar bienes activos
            ->groupBy('sucursal.ubicacion')
            ->get();


        return response()->json([
            'activos' => $activos,
            'datosCategorias' => $datosCategorias,
            'datosSucursales' =>  $datosSucursales
        ]);

        return response()->json($activos);
    }

    public function getCategorias()
    {
        $categorias = Categoria::where('estado', 1)->get(); // Ajusta esto según tus necesidades
        return response()->json($categorias);
    }

    public function getSucursales()
    {
        $sucursales = Sucursal::where('estado', 1)->get(); // Ajusta esto según tus necesidades
        return response()->json($sucursales);
    }

    public function getDepartamentos($idSucursal)
    {
        $departamentos = Departamento::where('idSucursal', $idSucursal)->where('estado', 1)->get(); // Ajusta esto según tus necesidades
        return response()->json($departamentos);
    }

    public function pdf(Request $request)
    {
        // Configurar los parámetros iniciales
        $idSucursal = $request->input('sucursal');
        $idDepartamento = $request->input('departamento');
        $idActivo  = $request->input('activo');
        $tipoDepreciacion = $request->input('tipo');
        $idEmpresa = $request->input('empresa');

        // Obtener el nombre de la empresa según el empleado o admin logueado
        $empleado = Auth::user()->empleado;
        $nombreEmpresa = $empleado && $empleado->departamento->sucursal->empresa->nombre ? $empleado->departamento->sucursal->empresa->nombre : 'Nombre no encontrado';
        $logo = $empleado && $empleado->departamento->sucursal->empresa->logo ? $empleado->departamento->sucursal->empresa->logo : NULL;


        // Preparar la consulta SQL para llamar al procedimiento almacenado
        $results = DB::select(
            'CALL ObtenerDepreciacion(?, ?, ?, ?, ?,NULL)',
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
                    'logo' => $logo, // Pasar el logo de la empresa a la vista
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

    // Generar PDF del activo en especifico con todos sus bienes
    public function pdfActivo(Request $request, $idActivo)
    {
        $empleado = Auth::user()->empleado;
        $activo = Activo::find($idActivo);

        // Configurar los parámetros iniciales para el activo en especifico que se selecciono
        $idSucursal = $empleado->departamento->sucursal->idSucursal;
        $idDepartamento = $empleado->departamento->idDepartamento;
        $tipoDepreciacion = $request->input('tipo');
        $idEmpresa = $empleado->departamento->sucursal->empresa->idEmpresa;
        $logo = $empleado && $empleado->departamento->sucursal->empresa->logo ? $empleado->departamento->sucursal->empresa->logo : NULL;


        // Obtener el nombre de la empresa según el empleado o admin logueado
        $nombreEmpresa = $empleado && $empleado->departamento && $empleado->departamento->sucursal && $empleado->departamento->sucursal->empresa
            ? $empleado->departamento->sucursal->empresa->nombre
            : 'Nombre no encontrado';


        // Preparar la consulta SQL para llamar al procedimiento almacenado
        $results = DB::select(
            'CALL ObtenerDepreciacion(?, ?, ?, ?, ?, NULL)',
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
                'activos.bienes.pdf',
                [
                    'resultados' => $results,
                    'tipoDepreciacion' => $tipoDepreciacion,
                    'nombreEmpresa' => $nombreEmpresa, // Pasar el nombre de la empresa a la vista
                    'logo' => $logo,
                    'activo' => $activo,
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
