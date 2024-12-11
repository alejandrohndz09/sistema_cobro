<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\ClienteJuridico;
use App\Models\ClienteNatural;
use App\Models\Cuota;
use App\Models\Sucursal;
use App\Models\Empresa;
use App\Models\Departamento;
use App\Models\DetalleVenta;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ventas = null;
        if (Auth::user()->usuario == 'admin') {
            $ventas = Venta::orderBy('fecha', 'desc')
                ->get();
        } else {
            $ventas = Venta::where('idEmpleado', Auth::user()->idEmpleado)
                ->where('idSucursal', Auth::user()->empleado->departamento->idSucursal)
                ->orderBy('fecha', 'desc')
                ->get();
        }

        // Consulta para obtener la suma de ventas agrupados por sucursal
        $ventasPorSucursal  = DB::table('sucursal')
            ->join('empresa', 'sucursal.idEmpresa', '=', 'empresa.idEmpresa')
            ->join('empleado', 'empresa.idEmpleado', '=', 'empleado.idEmpleado')
            ->join('venta', 'empleado.idEmpleado', '=', 'venta.idEmpleado')
            ->select('sucursal.ubicacion as ubicacion', DB::raw('SUM(venta.total) AS total'))
            ->groupBy('sucursal.ubicacion')
            ->get();

        return view('gestion-comercial.ventas.index', compact('ventas', 'ventasPorSucursal'));
    }

    public function store(Request $request)
    {
        // Validaciones
        $request->validate([
            'idCliente' => [
                'required',
                function ($attribute, $value, $fail) {
                    $existsJuridico = DB::table('cliente_juridico')->where('idClienteJuridico', $value)->exists();
                    $existsNatural = DB::table('cliente_natural')->where('idCliente_natural', $value)->exists();
                    if (!$existsJuridico && !$existsNatural) {
                        $fail('El cliente seleccionado no existe.');
                    }
                },
            ],
            'tipo' => 'required|in:Crédito,Contado',
            'plazo' => 'required_if:tipo,Crédito|nullable|integer|min:1',
            'detalles' => 'required|array|min:1',
            'detalles.*.idProducto' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!DB::table('producto')->where('idProducto', $value)->exists()) {
                        $fail("El producto seleccionado no existe.");
                    }
                },
            ],
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precioVenta' => 'required|numeric|min:0.01',
        ], [
            'detalles.required' => 'No ha seleccionado ningún producto.',
            'idCliente.required' => 'No ha seleccionado ningún cliente.',
        ]);

        DB::beginTransaction();

        try {
            // Recalcular totales desde el arreglo de detalles
            $subtotal = 0;
            $detalles = $request->detalles;

            foreach ($detalles as &$detalle) {
                $producto = DB::table('producto')->where('idProducto', $detalle['idProducto'])->first();

                // Validar stock
                if ($detalle['cantidad'] > $producto->StockTotal) {
                    return response()->json([
                        'errors' => [
                            'detalles' => [
                                "La cantidad solicitada para el producto {$detalle['idProducto']} excede el stock disponible."
                            ]
                        ]
                    ], 422);
                }

                // Actualizar stock del producto
                DB::table('producto')
                    ->where('idProducto', $detalle['idProducto'])
                    ->update(['StockTotal' => $producto->StockTotal - $detalle['cantidad']]);

                // Calcular subtotal del detalle
                $detalle['subtotal'] = $detalle['cantidad'] * $detalle['precioVenta'];
                $subtotal += $detalle['subtotal'];

                // Generar un ID único para el detalle
                $detalle['idDetalleVenta'] = uniqid();
            }

            $iva = $subtotal * 0.13;
            $total = $subtotal + $iva;

            // Insertar en la tabla `venta`
            $idVenta = $this->generarId();
            DB::table('venta')->insert([
                'idVenta' => $idVenta,
                'fecha' => now(),
                'tipo' => $request->tipo === 'Crédito' ? 1 : 0,
                'meses' => $request->tipo === 'Crédito' ? $request->meses : null,
                'SaldoCapital' => $subtotal,
                'iva' => $iva,
                'total' => $total,
                'idEmpleado' => Auth::user()->idEmpleado,
                'idCliente_juridico' => DB::table('cliente_juridico')->where('idClienteJuridico', $request->idCliente)->exists() ? $request->idCliente : null,
                'idCliente_natural' => DB::table('cliente_natural')->where('idCliente_natural', $request->idCliente)->exists() ? $request->idCliente : null,
                'estado' => $request->tipo === 'Crédito' ? 0 : 1,
            ]);

            // Crear un array para almacenar los detalles
            $detallesData = [];

            foreach ($detalles as $i => $detalle) {
                $detallesData[] = [
                    'idDetalleVenta' => $this->generarDetalleId($i),
                    'cantidad' => $detalle['cantidad'],
                    'subtotal' => $detalle['subtotal'],
                    'idProducto' => $detalle['idProducto'],
                    'idventa' => $idVenta,
                ];
            }

            // Insertar todos los detalles de una vez
            DB::table('detalle_venta')->insert($detallesData);

            DB::commit();
            $alert = [
                'type' => 'success',
                'message' => 'Operación exitosa.',
            ];
            return response()->json($alert);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Ocurrió un error al procesar la venta.', 'details' => $e->getMessage()], 500);
        }
    }


    public function show($id)
    {
        // Obtener la venta con las relaciones necesarias (detalles y productos)
        $venta = Venta::with(['cliente_natural', 'cliente_juridico', 'detalle_venta.producto'])->find($id);

        if (!$venta) {
            return redirect()->back()->withErrors(['error' => 'Venta no encontrada']);
        }

        // Determinar si el cliente es natural o jurídico
        $datosCliente = [];
        if ($venta->idCliente_natural !== null) {
            $cliente = $venta->Cliente_natural;
            $datosCliente['nombre'] = $cliente->nombres . ' ' . $cliente->apellidos;
            $datosCliente['telefono'] = $cliente->telefono;
            $datosCliente['direccion'] = $cliente->direccion;
            if ($venta->tipo === 0) {
                $datosCliente['tipo'] = 'Contado';
            } else {
                $datosCliente['tipo'] = 'Crédito';
            }
        } elseif ($venta->idCliente_juridico !== null) {
            $cliente = $venta->cliente_juridico;
            $datosCliente['nombre'] = $cliente->nombre_empresa;
            $datosCliente['telefono'] = $cliente->telefono;
            $datosCliente['direccion'] = $cliente->direccion;
            if ($venta->tipo === 0) {
                $datosCliente['tipo'] = 'Contado';
            } else {
                $datosCliente['tipo'] = 'Crédito';
            }
        } else {
            $datosCliente = [
                'nombre' => 'N/A',
                'telefono' => 'N/A',
                'direccion' => 'N/A',
                'tipo' => 'Desconocido'
            ];
        }
        return view('gestion-comercial.ventas.detalle', compact('venta', 'datosCliente'));
    }

    public function edit($id)
    {
        $venta = Venta::find($id);
        return response()->json($venta);
    }

    public function update(Request $request, $id)
    {

        // Validar la solicitud
        $request->validate([
            'imagen' => 'image|max:3000',
            'nombre' => 'required|min:3|unique:venta,nombre,' . $id . ',idVenta',
            'categoria' => 'required',
            'descripcion' => 'required',
        ], [
            'nombre.unique' => 'Este venta ya ha sido ingresado.',
        ]);



        $venta = Venta::find($id);
        $venta->nombre = $request->post('nombre');
        $venta->idCategoria = $request->post('categoria');
        $venta->descripcion = $request->post('descripcion');
        if ($request->hasFile('imagen')) {
            //primero eliminamos la anterior imagen
            $filePath = public_path('/assets/img/ventas/' . $venta->imagen);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            //se procede con el nuevo guardado
            $imagen = $request->file('imagen');
            $nombreVentaFormateado = str_replace(' ', '_', $venta->nombre);
            $nombreImagen = $venta->idVenta . '_' . $nombreVentaFormateado . '.' . $imagen->getClientOriginalExtension();
            $rutaImagen = public_path('/assets/img/ventas'); // Ruta donde deseas guardar la imagen
            $imagen->move($rutaImagen, $nombreImagen);
            $venta->imagen = $nombreImagen;
        }
        $venta->save();


        $alert = array(
            'type' => 'success',
            'message' => 'Operación exitosa.',
        );
        return response()->json($alert);
    }

    public function destroy($id)
    {
        $venta = Venta::find($id);
        $venta->delete();
        $alert = array(
            'type' => 'success',
            'message' => 'El registro se ha eliminado exitosamente'
        );


        return response()->json($alert);
    }

    public function baja($id)
    {
        $venta = Venta::find($id);
        $venta->estado = 0;
        $venta->save();

        $alert = array(
            'type' => 'success',
            'message' => 'El registro se ha deshabilitado exitosamente'
        );
        return response()->json($alert);
    }

    public function alta($id)
    {
        $venta = Venta::find($id);
        $venta->estado = 1;
        $venta->save();

        $alert = array(
            'type' => 'success',
            'message' => 'El registro se ha restaurado exitosamente'
        );
        return response()->json($alert);
    }

    public function generarId()
    {
        // Obtener el último registro de la tabla "venta"
        $ultimoVenta = Venta::latest('idVenta')->first();

        if (!$ultimoVenta) {
            // Si no hay registros previos, comenzar desde VT0001
            $nuevoId = 'VT0001';
        } else {
            // Obtener el número del último idVenta
            $ultimoNumero = intval(substr($ultimoVenta->idVenta, 2));

            // Incrementar el número para el nuevo registro
            $nuevoNumero = $ultimoNumero + 1;

            // Formatear el nuevo idVenta con ceros a la izquierda
            $nuevoId = 'VT' . str_pad($nuevoNumero, 4, '0', STR_PAD_LEFT);
        }

        return $nuevoId;
    }

    public function generarDetalleId($i)
    {
        // Obtener el último registro de la tabla "DetalleVenta"
        $ultimoDetalle = DetalleVenta::latest('idDetalleVenta')->first();

        if (!$ultimoDetalle) {
            // Si no hay registros previos, comenzar desde DV0001
            $nuevoId = 'DV0001';
        } else {
            // Obtener el número del último idDetalleVenta
            $ultimoNumero = intval(substr($ultimoDetalle->idventa, 2));

            // Incrementar el número para el nuevo registro
            $nuevoNumero = $ultimoNumero + 1 + $i;

            // Formatear el nuevo idVenta con ceros a la izquierda
            $nuevoId = 'DV' . str_pad($nuevoNumero, 4, '0', STR_PAD_LEFT);
        }

        return $nuevoId;
    }

    public function getVentas($tipoVenta, $tipoCliente)
    {
        // Listas de valores válidos
        $validTipoVenta = ['v-todas', 'v-contado', 'v-credito'];
        $validTipoCliente = ['c-todos', 'c-natural', 'c-juridico'];

        // Validar los parámetros
        if (!in_array($tipoVenta, $validTipoVenta) || !in_array($tipoCliente, $validTipoCliente)) {
            return response()->json(['error' => 'Parámetros inválidos. Por favor, verifica los valores enviados.'], 400);
        }

        // Construcción de la consulta base
        $ventasQuery = Venta::with(['cliente_juridico', 'cliente_natural'])
            ->orderBy('fecha', 'desc');

        // Verifica el rol del usuario
        if (Auth::user()->usuario !== 'admin') {
            $ventasQuery->where('idEmpleado', Auth::user()->idEmpleado)
                ->where('idSucursal', Auth::user()->empleado->departamento->idSucursal);
        }

        // Filtro por tipo de venta (Contado o Crédito)
        if ($tipoVenta !== 'v-todas') {
            $ventasQuery->where('tipo', $tipoVenta === 'v-contado' ? 0 : 1); // 0=Contado, 1=Crédito
        }

        // Filtro por tipo de cliente (Natural o Jurídico)
        if ($tipoCliente !== 'c-todos') {
            $ventasQuery->whereHas('cliente_' . ($tipoCliente === 'c-natural' ? 'natural' : 'juridico'));
        }

        // Ejecuta la consulta y retorna el resultado
        return response()->json($ventasQuery->get());
    }

    public function getClientes($query = null)
    {
        // Construir la consulta base para clientes naturales
        $clientesNaturales = ClienteNatural::select([
            'idCliente_natural as idCliente',
            DB::raw("CONCAT(nombres, ' ', apellidos) as cliente"),
            'estado',
            DB::raw('0 as tipo') // Tipo 0 para clientes naturales
        ])
            ->where('estado', 1);

        // Construir la consulta base para clientes jurídicos
        $clientesJuridicos = ClienteJuridico::select([
            'idClienteJuridico as idCliente',
            'nombre_empresa as cliente',
            'estado',
            DB::raw('1 as tipo') // Tipo 1 para clientes jurídicos
        ])
            ->where('estado', 1);

        // Si hay un término de búsqueda, aplicamos los filtros en ambas tablas
        if ($query) {
            $firstWord = strtok($query, ' '); // Toma la primera palabra separada por espacio
            $clientesNaturales->where(function ($queryBuilder) use ($query, $firstWord) {
                $queryBuilder->where('nombres', 'like', '%' . $query . '%')
                    ->orWhere('apellidos', 'like', '%' . $query . '%')
                    ->orWhere('idCliente_natural', 'like', '%' . $query . '%') // Filtra por ID de cliente natural
                    ->orWhere('idCliente_natural', 'like', '%' . $firstWord . '%'); // Filtra por ID de cliente natural
            });

            $clientesJuridicos->where(function ($queryBuilder) use ($query, $firstWord) {
                $queryBuilder->where('nombre_empresa', 'like', '%' . $query . '%')
                    ->orWhere('idClienteJuridico', 'like', '%' . $query . '%') // Filtra por ID de cliente jurídico
                    ->orWhere('idClienteJuridico', 'like', '%' . $firstWord . '%');
            });
        }

        // Combina las dos consultas usando un `union` optimizado
        $clientes = $clientesNaturales->unionAll($clientesJuridicos)->orderBy('cliente', 'asc')->get(); // `unionAll` puede ser más eficiente que `union`

        // Devuelve el resultado como JSON
        return response()->json($clientes);
    }

    public function getProductos($query = null)
    {

        //Construir la consulta base para clientes naturales
        $productos = Producto::select([
            'idProducto',
            'nombre',
            'estado',
            DB::raw('(
                    (SELECT IFNULL(SUM(dc.cantidad), 0) FROM detalle_compra dc WHERE dc.idProducto = producto.idProducto) -
                     (SELECT IFNULL(SUM(dv.cantidad), 0) FROM detalle_venta dv WHERE dv.idProducto = producto.idProducto)
                     ) AS stockTotal'),
            DB::raw('ROUND((
                        (SELECT SUM(dc.precio * dc.cantidad) FROM detalle_compra dc WHERE dc.idProducto = producto.idProducto) /
                        (SELECT IFNULL(SUM(dc.cantidad), 1) FROM detalle_compra dc WHERE dc.idProducto = producto.idProducto)
                    ) * 1.10, 2) AS precioVenta')
        ])
            ->where('estado', 1) // Solo productos activos
            ->having('stockTotal', '>', 0); // Filtra productos con stock > 0

        // Si hay un término de búsqueda, aplicamos los filtros en ambas tablas
        if ($query) {
            // Extraer la primera palabra del query
            $firstWord = strtok($query, ' '); // Toma la primera palabra separada por espacio

            $productos->where(function ($queryBuilder) use ($query, $firstWord) {
                $queryBuilder->where('nombre', 'like', '%' . $query . '%')
                    ->orWhere('idProducto', 'like', '%' . $query . '%')
                    ->orWhere('idProducto', 'like', '%' . $firstWord . '%'); // Filtra por la primera palabra
            });
        }
        $productos = $productos->get();
        // Devuelve el resultado como JSON
        return response()->json($productos);
    }

    public function getIdVenta()
    {
        return response()->json($this->generarId());
    }
    public function pdf(Request $request)
    {
        // Configurar los parámetros iniciales
        $idSucursal = $request->input('sucursal');
        $idDepartamento = $request->input('departamento');
        $idVenta  = $request->input('venta');
        $tipoDepreciacion = $request->input('tipo');
        $idEmpresa = $request->input('empresa');

        // Obtener el nombre de la empresa según el id proporcionado
        $empresa = Empresa::find($idEmpresa);
        $nombreEmpresa = $empresa ? $empresa->nombre : 'Nombre no encontrado';

        // Preparar la consulta SQL para llamar al procedimiento almacenado
        $results = DB::select(
            'CALL ObtenerDepreciacion(?, ?, ?, ?, ?,NULL)',
            [$tipoDepreciacion, $idSucursal, $idDepartamento, $idEmpresa, $idVenta]
        );

        // Calcular el total de ventas
        $totalVentas = count($results); // Contar el número de filas en los resultados

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
                'ventas.pdf',
                [
                    'resultados' => $results,
                    'tipoDepreciacion' => $tipoDepreciacion,
                    'nombreEmpresa' => $nombreEmpresa, // Pasar el nombre de la empresa a la vista
                    'totalVentas' => $totalVentas,
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

    public function obtenerProducto($idDetalleVenta)
    {
        // Buscar el detalle de la venta por el idDetalleVenta
        $detalleVenta = DetalleVenta::with('producto') // Asegúrate de que la relación 'producto' esté definida en el modelo DetalleVenta
            ->find($idDetalleVenta);

        // Verificar si el detalle de venta existe
        if (!$detalleVenta) {
            return response()->json(['error' => 'Detalle de venta no encontrado'], 404);
        }

        // Obtener el producto asociado con el detalle de venta
        $producto = $detalleVenta->producto;

        // Devolver los detalles del producto en formato JSON
        return response()->json([
            'nombre' => $producto->nombre,
            'descripcion' => $producto->descripcion,
            'cantidad' => $detalleVenta->cantidad,  // Usamos la cantidad del detalle de venta
            'subtotal' => $detalleVenta->subtotal, // Usamos el subtotal del detalle de venta
            'imagen' => asset('assets/img/productos/' . $producto->imagen)
        ]);
  }


    // public function pdfFactura($idVenta)
    // {
    //     // Obtener la venta con el cliente relacionado
    //     $venta = Venta::with(['cliente_natural', 'cliente_juridico'])->findOrFail($idVenta);

    //     // Obtener los detalles de la venta
    //     $detalles = DetalleVenta::where('idVenta', $idVenta)
    //         ->with('producto') // Cargar relación con producto
    //         ->get();

    //     // Calcular precio de venta y subtotal
    //     foreach ($detalles as $detalle) {
    //         $totalCompra = DB::table('detalle_compra')
    //             ->where('idProducto', $detalle->idProducto)
    //             ->sum(DB::raw('precio * cantidad'));

    //         $totalCantidad = DB::table('detalle_compra')
    //             ->where('idProducto', $detalle->idProducto)
    //             ->sum('cantidad');

    //         $detalle->precioVenta = round(($totalCompra / ($totalCantidad ?: 1)) * 1.10, 2);
    //         $detalle->subtotal = $detalle->precioVenta * $detalle->cantidad;
    //     }

    //     // Verificar si la venta es a crédito
    //     $numeroCuotas = $venta->tipo == 1 ? $venta->meses : 0;

    //     // Calcular IVA (13%)
    //     $iva = round($venta->total * 0.13, 2);

    //     // Calcular Total con IVA
    //     $totalConIva = round($venta->total + $iva, 2);

    //     // Obtener la empresa
    //     $empresa = Empresa::first();

    //     // Generar el PDF
    //     $pdf = PDF::loadView('gestion-comercial.ventas.factura', compact('venta', 'detalles', 'empresa', 'iva', 'totalConIva', 'numeroCuotas'));

    //     // Descargar el PDF
    //     return $pdf->download('Factura-Venta-' . $venta->idVenta . '.pdf');
    // }

    public function pdfFactura($idVenta)
    {
        // Obtener la venta con el cliente relacionado
        $venta = Venta::with(['cliente_natural', 'cliente_juridico'])->findOrFail($idVenta);

        // Obtener los detalles de la venta
        $detalles = DetalleVenta::where('idVenta', $idVenta)
            ->with('producto') // Cargar relación con producto
            ->get();

        // Calcular precio de venta y subtotal
        foreach ($detalles as $detalle) {
            $totalCompra = DB::table('detalle_compra')
                ->where('idProducto', $detalle->idProducto)
                ->sum(DB::raw('precio * cantidad'));

            $totalCantidad = DB::table('detalle_compra')
                ->where('idProducto', $detalle->idProducto)
                ->sum('cantidad');

            $detalle->precioVenta = round(($totalCompra / ($totalCantidad ?: 1)) * 1.10, 2);
            $detalle->subtotal = round($detalle->precioVenta * $detalle->cantidad, 2);
        }

        // Calcular el total sin IVA
        $totalSinIva = $detalles->sum('subtotal'); // Aquí se define correctamente

        // Calcular IVA (13%)
        $iva = round($totalSinIva * 0.13, 2);

        // Calcular Total con IVA
        $totalConIva = round($totalSinIva + $iva, 2);

        // Calcular el número de cuotas
        $ultimaFechaCuota = Cuota::where('idVenta', $idVenta)->max('fechaLimite');
        $numeroCuotas = 0;
        if ($ultimaFechaCuota) {
            $fechaInicio = \Carbon\Carbon::parse($venta->fecha);
            $fechaFin = \Carbon\Carbon::parse($ultimaFechaCuota);
            $numeroCuotas = $fechaInicio->diffInMonths($fechaFin) + 1;
        }

        // Obtener la empresa
        $empresa = Empresa::first();

        // Generar el PDF
        $pdf = PDF::loadView('gestion-comercial.ventas.factura', compact(
            'venta',
            'detalles',
            'empresa',
            'iva',
            'totalConIva',
            'numeroCuotas',
            'totalSinIva' // Asegurarse de pasar esta variable
        ));

        // Descargar el PDF
        return $pdf->download('Factura-Venta-' . $venta->idVenta . '.pdf');
    }
}
