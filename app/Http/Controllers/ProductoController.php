<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productos = Producto::all();

        return view('producto.index', compact('productos', 'productos'));
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
            'nombre' => 'required|min:3|unique:producto',
            'descripcion' => 'required',
            'stockMinimo' => 'required'

        ], [
            'nombre.unique' => 'Este nombre ya ha sido ingresado.',
        ]);

        $producto = new Producto();
        $producto->idProducto = $this->generarId();
        $producto->nombre = $request->post('nombre');
        $producto->descripcion = $request->post('descripcion');
        $producto->stockMinimo = $request->post('stockMinimo');
        $producto->stockTotal = 0;
        $producto->estado = 1;
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreActivoFormateado = str_replace(' ', '_', $producto->nombre);
            $nombreImagen = $producto->idProducto . '_' . $nombreActivoFormateado . '.' . $imagen->getClientOriginalExtension();
            $rutaImagen = public_path('/assets/img/productos'); // Ruta donde deseas guardar la imagen
            $imagen->move($rutaImagen, $nombreImagen);
            // Aquí puedes guardar $nombreImagen en tu base de datos o realizar otras acciones necesarias.
            $producto->imagen = $nombreImagen;
        }
        $producto->save();


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

        $producto = Producto::find($id);

        // Obtener los datos del Kardex ejecutando el procedimiento almacenado
        $kardex = DB::select('CALL obtener_kardex(?)', [$id]);

        // Pasar ambos datos a la vista
        return view('producto.detalleProducto', compact('producto', 'kardex'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $producto = Producto::find($id);
        return response()->json($producto);
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
            'nombre' => 'required|min:3|unique:producto,nombre,' . $id . ',idProducto',
            'descripcion' => 'required',
            'stockMinimo' => 'required'


        ], [
            'nombre.unique' => 'Este nombre ya ha sido ingresado.',
        ]);

        $producto = Producto::find($id);
        $producto->nombre = $request->post('nombre');
        $producto->descripcion = $request->post('descripcion');
        $producto->stockMinimo = $request->post('stockMinimo');
        if ($request->hasFile('imagen')) {
            //primero eliminamos la anterior imagen
            $filePath = public_path('/assets/img/activos/' . $producto->imagen);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            //se procede con el nuevo guardado
            $imagen = $request->file('imagen');
            $nombreProductoFormateado = str_replace(' ', '_', $producto->nombre);
            $nombreImagen = $producto->idProducto . '_' . $nombreProductoFormateado . '.' . $imagen->getClientOriginalExtension();
            $rutaImagen = public_path('/assets/img/productos'); // Ruta donde deseas guardar la imagen
            $imagen->move($rutaImagen, $nombreImagen);
            $producto->imagen = $nombreImagen;
        }
        $producto->save();


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
        $producto = Producto::find($id);
        if ($producto->detalle_ventas || $producto->detalle_compras === 0) {
            $producto->delete();
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
        $producto = Producto::find($id);
        $producto->estado = 0;
        $producto->save();

        $alert = array(
            'type' => 'success',
            'message' => 'El registro se ha deshabilitado exitosamente'
        );
        return response()->json($alert);
    }

    public function alta($id)
    {
        $producto = Producto::find($id);
        $producto->estado = 1;
        $producto->save();

        $alert = array(
            'type' => 'success',
            'message' => 'El registro se ha restaurado exitosamente'
        );
        return response()->json($alert);
    }

    public function generarId()
    {
        // Obtener el último registro de la tabla "departamento"
        $ultimoProducto = Producto::latest('idProducto')->first();

        if (!$ultimoProducto) {
            // Si no hay registros previos, comenzar desde CA0001
            $nuevoId = 'PD0001';
        } else {
            // Obtener el número del último idDepartamento
            $ultimoNumero = intval(substr($ultimoProducto->idProducto, 2));

            // Incrementar el número para el nuevo registro
            $nuevoNumero = $ultimoNumero + 1;

            // Formatear el nuevo idDepartamento con ceros a la izquierda
            $nuevoId = 'PD' . str_pad($nuevoNumero, 4, '0', STR_PAD_LEFT);
        }

        return $nuevoId;
    }

    public function getProductos()
    {
        $productos = Producto::all(); // Ajusta esto según tus necesidades
        return response()->json($productos);
    }

    public function getDetallesProducto($tipo, $id)
    {
        if ($tipo === 'entrada') {
            // Consultar detalles de la compra
            $detalleCompra = DetalleCompra::find($id);

            if (!$detalleCompra) {
                return response()->json(['error' => 'Compra no encontrada'], 404);
            }

            return response()->json([
                'tipo' => 'entrada',
                'idCompra' => $detalleCompra->compra->idCompra,
                'fecha' => $detalleCompra->compra->fecha,
                'cantidad' => $detalleCompra->cantidad,
                'idEmpleado' => $detalleCompra->compra->empleado->idEmpleado,
                'nombreEmpleado' => $detalleCompra->compra->empleado->nombres . ' ' . $detalleCompra->compra->empleado->apellidos,
            ]);
        } elseif ($tipo === 'salida') {
            // Consultar detalles de la venta
            $detalleVenta = DetalleVenta::find($id);

            if (!$detalleVenta) {
                return response()->json(['error' => 'Venta no encontrada'], 404);
            }

            return response()->json([
                'tipo' => 'salida',
                'idVenta' => $detalleVenta->venta->idVenta,
                'fecha' => $detalleVenta->venta->fecha,
                'tipoVenta' => $detalleVenta->venta->tipo, // 0 contado, 1 crédito
                'meses' => $detalleVenta->venta->meses,
                'idEmpleado' => $detalleVenta->venta->empleado->idEmpleado,
                'nombreEmpleado' => $detalleVenta->venta->empleado->nombres . ' ' . $detalleVenta->venta->empleado->apellidos,
            ]);
        } else {
            // Si el tipo no es válido
            return response()->json(['error' => 'Tipo de movimiento inválido'], 400);
        }
    }
}
