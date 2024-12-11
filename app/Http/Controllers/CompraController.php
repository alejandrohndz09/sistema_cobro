<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Producto;
use App\Models\Proveedor;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $compras = Compra::selectRaw('
        compra.idCompra, 
        compra.fecha,
        compra.estado, 
        proveedor.nombre AS nombreProveedor, 
        SUM(detalle_compra.precio * detalle_compra.cantidad) AS montoTotal
    ')
            ->join('detalle_compra', 'compra.idCompra', '=', 'detalle_compra.idCompra')
            ->join('proveedor', 'compra.idProveedor', '=', 'proveedor.IdProveedor')
            ->groupBy('compra.idCompra', 'compra.fecha', 'proveedor.nombre', 'compra.estado')
            ->orderBy('compra.fecha', 'DESC')
            ->get();


        $sucursales = DB::table('sucursal as s')
            ->join('departamento as d', 's.idSucursal', '=', 'd.idSucursal')
            ->join('empleado as emp', 'd.idDepartamento', '=', 'emp.idDepartamento')
            ->join('compra as c', function ($join) {
                $join->on('emp.idEmpleado', '=', 'c.idEmpleado')
                    ->whereNotNull('c.idCompra'); // Solo compras asociadas
            })
            ->join('detalle_compra as dc', 'c.idCompra', '=', 'dc.idCompra')
            ->select(
                's.idSucursal',
                's.ubicacion',
                DB::raw('SUM(dc.precio * dc.cantidad) as monto_total_compras')
            )
            ->groupBy('s.idSucursal', 's.ubicacion')
            ->orderByDesc('monto_total_compras')
            ->get();


        // Retorna una vista con las compras
        return view('gestion-comercial.compras.index', compact('compras', 'sucursales'));
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
        DB::beginTransaction();

        try {
            // Validar los detallesCompra recibidos
            $validatedData = $request->validate([
                'detallesCompra.*.idProducto' => 'required|exists:producto,idProducto',
                'detallesCompra.*.producto' => 'required|string',
                'detallesCompra.*.cantidad' => 'required|integer|min:1',
                'detallesCompra.*.precioUnitario' => 'required|numeric|min:0',
                'idProveedorr' => 'required|exists:proveedor,idProveedor', // Validación para que el proveedor exista
            ]);

            // Recalcular totales desde el arreglo de detallesCompra
            $subtotal = 0;
            $detallesCompra = $request->detallesCompra;

            foreach ($detallesCompra as $detallesC) { // Eliminado el '&'
                $producto = DB::table('producto')->where('idProducto', $detallesC['idProducto'])->first();

                // Verificar si el producto existe
                if (!$producto) {
                    throw new \Exception('Producto no encontrado: ' . $detallesC['producto']);
                }

                // Actualizar stock del producto
                DB::table('producto')
                    ->where('idProducto', $detallesC['idProducto'])
                    ->update(['StockTotal' => $producto->StockTotal + $detallesC['cantidad']]);

                // Generar un ID único para el detalle
                $detallesC['idDetalleCompra'] = uniqid();
                $subtotal += $detallesC['cantidad'] * $detallesC['precioUnitario']; // Calcular subtotal

            }

            // Insertar en la tabla `compra`
            $idCompra = $this->generarId();
            DB::table('compra')->insert([
                'idCompra' => $idCompra,
                'fecha' => now(),
                'stockDisponible' => 0,
                'idEmpleado' => Auth::user()->idEmpleado,
                'idProveedor' => $request->idProveedor, // Proveedor fijo o dinámico
                'estado' => 1,
            ]);

            // Crear un array para almacenar los detallesCompra
            $detallesCompraData = [];
            foreach ($detallesCompra as $i => $detallesC) { // Este foreach ahora procesa correctamente cada detalle
                $detallesCompraData[] = [
                    'idDetalleCompra' => $this->generarDetalleId($i),
                    'precio' => $detallesC['precioUnitario'],
                    'cantidad' => $detallesC['cantidad'],
                    'idCompra' => $idCompra,
                    'idProducto' => $detallesC['idProducto'], // Ahora toma el valor correcto
                ];
            }

            // Insertar todos los detallesCompra de la compra en la tabla `detalle_compra`
            DB::table('detalle_compra')->insert($detallesCompraData);

            // Confirmar la transacción
            DB::commit();

            // Respuesta exitosa
            return response()->json([
                'success' => true,
                'message' => 'Compra procesada con éxito.',
                'idCompra' => $idCompra
            ], 200);
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();

            // Responder con el error
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al procesar la compra.',
                'details' => $e->getMessage()
            ], 500);
        }
    }



    /**
     * Display the specified resource.
     */

    public function show($id)
    {
        // Obtener los detalles de la compra
        $productos = DB::table('detalle_compra as dc')
            ->selectRaw('
                p.nombre AS nombreProducto, 
                dc.precio, 
                dc.cantidad, 
                (dc.precio * dc.cantidad) AS monto
            ')
            ->join('producto as p', 'dc.idProducto', '=', 'p.idProducto')
            ->where('dc.idCompra', $id)
            ->get();

        // Calcular el total
        $total = $productos->sum(function ($producto) {
            return $producto->precio * $producto->cantidad;
        });

        // Retornar los productos y el total
        return response()->json([
            'productos' => $productos,
            'total' => $total
        ]);
    }




    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
   
    public function destroy(string $id)
    {
        try {
            // Eliminar primero los detalles de la compra
            DB::table('detalle_compra')->where('idCompra', $id)->delete();
    
            // Luego eliminar la compra
            DB::table('compra')->where('idCompra', $id)->delete();
    
            // Definir el mensaje de éxito
            $alert = [
                'type' => 'success',
                'message' => 'Compra eliminada correctamente.',
            ];
        } catch (\Exception $e) {
            // Si ocurre un error, definir el mensaje de error
            $alert = [
                'type' => 'error',
                'message' => 'No se pudo eliminar la compra.',
            ];
        }
    
        // Retornar el mensaje en formato JSON
        return response()->json($alert);
    }
    



    public function generarDetalleId($i)
    {
        // Obtener el último registro de la tabla "detalle_compra"
        $ultimoDetalle = DB::table('detalle_compra')->latest('idDetalleCompra')->first();

        if (!$ultimoDetalle) {
            // Si no hay registros previos, comenzar desde DC0001
            $nuevoId = 'DC0001';
        } else {
            // Obtener el número del último idDetalleCompra
            $ultimoNumero = intval(substr($ultimoDetalle->idDetalleCompra, 2));

            // Incrementar el número para el nuevo registro
            $nuevoNumero = $ultimoNumero + 1 + $i;

            // Formatear el nuevo idDetalleCompra con ceros a la izquierda
            $nuevoId = 'DC' . str_pad($nuevoNumero, 4, '0', STR_PAD_LEFT);
        }

        return $nuevoId;
    }



    public function getProveedores($query = null)
    {

        $proveedores = Proveedor::select('idProveedor', 'nombre')
            ->where('estado', 1);

        // Si hay un término de búsqueda, aplicamos los filtros en ambas tablas
        if ($query) {
            // Extraer la primera palabra del query
            $firstWord = strtok($query, ' '); // Toma la primera palabra separada por espacio

            $proveedores->where(function ($queryBuilder) use ($query, $firstWord) {
                $queryBuilder->where('nombre', 'like', '%' . $query . '%')
                    ->orWhere('idProveedor', 'like', '%' . $query . '%')
                    ->orWhere('idProveedor', 'like', '%' . $firstWord . '%'); // Filtra por la primera palabra
            });
        }
        $proveedores = $proveedores->get();
        // Devuelve el resultado como JSON
        return response()->json($proveedores);
    }

    public function obtenerCompras()
    {
        $compras = Compra::selectRaw('
        compra.idCompra, 
        compra.fecha,
        compra.estado, 
        proveedor.nombre AS nombreProveedor, 
        SUM(detalle_compra.precio * detalle_compra.cantidad) AS montoTotal
    ')
            ->join('detalle_compra', 'compra.idCompra', '=', 'detalle_compra.idCompra')
            ->join('proveedor', 'compra.idProveedor', '=', 'proveedor.IdProveedor')
            ->groupBy('compra.idCompra', 'compra.fecha', 'proveedor.nombre', 'compra.estado')
            ->orderBy('compra.fecha', 'DESC')
            ->get();
        return response()->json(['success' => true, 'data' => $compras]);
    }



    public function getProductos($query = null)
    {

        $productos = Producto::select([
            'idProducto',
            'nombre',
            'estado',
            DB::raw('(
                    SELECT IFNULL(SUM(dc.cantidad), 0) 
                    FROM detalle_compra dc 
                    WHERE dc.idProducto = producto.idProducto
                ) AS stockTotal')
        ])
            ->where('estado', 1); // Solo productos activos
        //->get(); // Obtener los productos con estado 1


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


    public function getIdCompra()
    {
        return response()->json($this->generarId());
    }


    public function generarId()
    {
        // Obtener el último registro de la tabla "compra"
        $ultimoCompra = Compra::latest('idCompra')->first();

        if (!$ultimoCompra) {
            // Si no hay registros previos, comenzar desde VT0001
            $nuevoId = 'CP0001';
        } else {
            // Obtener el número del último idCompra
            $ultimoNumero = intval(substr($ultimoCompra->idCompra, 2));

            // Incrementar el número para el nuevo registro
            $nuevoNumero = $ultimoNumero + 1;

            // Formatear el nuevo idCompra con ceros a la izquierda
            $nuevoId = 'CP' . str_pad($nuevoNumero, 4, '0', STR_PAD_LEFT);
        }

        return $nuevoId;
    }


    public function bajaCompra($id)
    {
        $empleado = Compra::find($id);
        $empleado->estado = 0;
        $empleado->save();

        $alert = array(
            'type' => 'success',
            'message' => 'El registro se ha deshabilitado exitosamente'
        );
        return response()->json($alert);
    }

    public function altaCompra($id)
    {
        $venta = Compra::find($id);
        $venta->estado = 1;
        $venta->save();

        $alert = array(
            'type' => 'success',
            'message' => 'El registro se ha restaurado exitosamente'
        );
        return response()->json($alert);
    }
 
    public function getSucursalC()
{
    $sucursales = DB::table('sucursal as s')
        ->join('departamento as d', 's.idSucursal', '=', 'd.idSucursal')
        ->join('empleado as emp', 'd.idDepartamento', '=', 'emp.idDepartamento')
        ->join('compra as c', function ($join) {
            $join->on('emp.idEmpleado', '=', 'c.idEmpleado')
                 ->whereNotNull('c.idCompra'); // Solo compras asociadas
        })
        ->join('detalle_compra as dc', 'c.idCompra', '=', 'dc.idCompra')
        ->select(
            's.idSucursal',
            's.ubicacion',
            DB::raw('SUM(dc.precio * dc.cantidad) as monto_total_compras')
        )
        ->groupBy('s.idSucursal', 's.ubicacion')
        ->orderByDesc('monto_total_compras')
        ->get();

    // Devolvemos la respuesta correctamente
    return response()->json([
        'success' => true, 
        'data' => $sucursales // Aseguramos que los datos están dentro de "data"
    ]);
}

    public function generarPDF()
    {
        $fechaInicio = Carbon::now()->startOfYear(); // 2024-01-01 00:00:00
        $fechaFin = Carbon::now(); // Fecha y hora actual completa (2024-12-10 15:23:45)

        // Obtener los resultados solo dentro del rango de fechas
        $results = DB::table('compra as co')
            ->join('detalle_compra as dc', 'co.idCompra', '=', 'dc.idCompra')
            ->join('producto as pr', 'dc.idProducto', '=', 'pr.idProducto')
            ->join('proveedor as p', 'co.idProveedor', '=', 'p.IdProveedor')
            ->select(
                'p.nombre as Proveedor',
                'pr.nombre as Producto',
                DB::raw('SUM(dc.cantidad * dc.precio) as CostoTotal'),
                DB::raw('SUM(dc.cantidad) as CantidadTotal'), // Nueva columna de cantidad total
                DB::raw('COUNT(dc.idCompra) as TotalCompras'),
                DB::raw('MIN(co.fecha) as FechaInicio'),
                DB::raw('MAX(co.fecha) as FechaFin')
            )
            ->whereBetween('co.fecha', [$fechaInicio, $fechaFin]) // Filtrar solo en el rango
            ->groupBy('p.nombre', 'pr.nombre')
            ->orderBy('p.nombre') // Ordenar por proveedor
            ->orderByDesc('CostoTotal') // Ordenar por costo total dentro de cada proveedor
            ->get();

        // Generar el PDF con los resultados y las fechas
        $pdf = Pdf::loadView('gestion-comercial.compras.reporte', [
            'results' => $results,
            'fechaInicio' => $fechaInicio->format('Y-m-d H:i:s'), // Mostrar la fecha con hora
            'fechaFin' => $fechaFin->format('Y-m-d H:i:s') // Mostrar la fecha con hora
        ]);

        return $pdf->stream('reporte_compras.pdf');
    }
}
