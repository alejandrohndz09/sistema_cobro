<?php

namespace App\Http\Controllers;

use App\Models\ClienteJuridico;
use App\Models\ClienteNatural;
use App\Models\Departamento;
use App\Models\Sucursal;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Consultar los clientes naturales
        $clientesNaturales = DB::table('cliente_natural')
            ->select(
                'idCliente_natural as id',
                DB::raw("CONCAT(nombres, ' ', apellidos) as nombre"),
                'telefono',
                'direccion',
                'estado'
            )
            ->addSelect(DB::raw("'natural' as tipo_cliente"))
            ->get();

        // Consultar los clientes jurídicos
        $clientesJuridicos = DB::table('cliente_juridico')
            ->select(
                'idClienteJuridico as id',
                'nombre_empresa as nombre',
                'telefono',
                'direccion',
                'estado'
            )
            ->addSelect(DB::raw("'juridico' as tipo_cliente"))
            ->get();

        // Combinar y mezclar los resultados
        $clientes = $clientesNaturales->merge($clientesJuridicos)->shuffle();


        return view('clientes.index', compact('clientes', 'clientes'));
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
        // Determinar el tipo de cliente
        $tipoNatural = $request->post('tipoNatural');
        $tipoJuridico = $request->post('tipoJuridico');

        // Validaciones comunes
        $validaciones = [];
        $mensajes = [];

        if ($tipoNatural == 0 && $tipoJuridico == null) {
            // Cliente Natural
            $validaciones = [
                'dui' => 'required|min:9|unique:cliente_natural',
                'nombres' => 'required',
                'apellidos' => 'required',
                'telefono' => 'required',
                'ingresos' => 'required',
                'egresos' => 'required',
                'lugarTrabajo' => 'required',
                'direccion' => 'required',
            ];
            $mensajes = [
                'dui.unique' => 'Este DUI ya ha sido registrado.',
            ];
        } else if ($tipoJuridico == 1 && $tipoNatural == null) {
            // Cliente Jurídico
            $validaciones = [
                'nit' => 'required|regex:/^\d{4}-\d{6}-\d{3}-\d{1}$/|unique:cliente_juridico|min:14',
                'nombreEmpresa' => 'required',
                'telefonoJuridico' => 'required',
                'direccionJuridico' => 'required',
                'ventasNetas' => 'required',
                'activoCorriente' => 'required',
                'inventario' => 'required',
                'costoVentas' => 'required',
                'pasivoCorriente' => 'required',
                'cuentasCobrar' => 'required',
                'cuentasPagar' => 'required',
                'balanceGeneral' => 'required|mimetypes:application/pdf',
                'estadoResultados' => 'required|mimetypes:application/pdf',

            ];
            $mensajes = [
                'nit.unique' => 'Este NIT ya ha sido registrado.',
            ];
        } else {
            return response()->json(['type' => 'error', 'message' => 'Tipo de cliente no válido.'], 400);
        }

        // Validar la solicitud
        $request->validate($validaciones, $mensajes);

        // Crear cliente según el tipo
        if ($tipoNatural == 0 && $tipoJuridico == null) {
            $cliente = new ClienteNatural();
            $cliente->idCliente_natural = $this->generarId(0);
            $cliente->dui = $request->post('dui');
            $cliente->nombres = $request->post('nombres');
            $cliente->apellidos = $request->post('apellidos');
            $cliente->telefono = $request->post('telefono');
            $cliente->direccion = $request->post('direccion');
            $cliente->ingresos = $request->post('ingresos');
            $cliente->egresos = $request->post('egresos');
            $cliente->lugarTrabajo = $request->post('lugarTrabajo');
            $cliente->estado = 1;
        } else if ($tipoJuridico == 1 && $tipoNatural == null) {

            $cliente = new ClienteJuridico();
            $cliente->idClienteJuridico = $this->generarId(1);
            $cliente->nit = $request->post('nit');
            $cliente->nombre_empresa = $request->post('nombreEmpresa');
            $cliente->telefono = $request->post('telefonoJuridico');
            $cliente->direccion = $request->post('direccionJuridico');
            $cliente->ventas_netas = $request->post('ventasNetas');
            $cliente->activo_corriente = $request->post('activoCorriente');
            $cliente->inventario = $request->post('inventario');
            $cliente->costos_ventas = $request->post('costoVentas');
            $cliente->pasivos_corriente = $request->post('pasivoCorriente');
            $cliente->cuentas_cobrar = $request->post('cuentasCobrar');
            $cliente->cuentas_pagar = $request->post('cuentasPagar');
            $cliente->estado = 1;

            if ($request->hasFile('balanceGeneral')) {
                $archivo = $request->file('balanceGeneral');
                $nombreArchivoBalanceGeneral = $cliente->idClienteJuridico . '_BG.' . $archivo->getClientOriginalExtension();
                $rutaArchivo = public_path('/assets/pdf/documentosFinancieros'); // Ruta donde deseas guardar el archivo PDF
                $archivo->move($rutaArchivo, $nombreArchivoBalanceGeneral);
                // Guarda el nombre del archivo en la base de datos
                $cliente->balance_general = $nombreArchivoBalanceGeneral;
            }

            if ($request->hasFile('estadoResultados')) {
                $archivo = $request->file('estadoResultados');
                $nombreArchivoEstadoResultados = $cliente->idClienteJuridico . '_ER.' . $archivo->getClientOriginalExtension();
                $rutaArchivo = public_path('/assets/pdf/documentosFinancieros'); // Ruta donde deseas guardar el archivo PDF
                $archivo->move($rutaArchivo, $nombreArchivoEstadoResultados);
                // Guarda el nombre del archivo en la base de datos
                $cliente->estado_resultado = $nombreArchivoEstadoResultados;
            }
        }

        // Guardar en la base de datos
        $cliente->save();

        // Respuesta de éxito
        return response()->json([
            'type' => 'success',
            'message' => 'Operación exitosa.',
        ]);
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
        // Verificar si el ID pertenece a CN o CJ
        if (str_starts_with($id, 'CN')) {
            $cliente = ClienteNatural::find($id);
        } elseif (str_starts_with($id, 'CJ')) {
            $cliente = ClienteJuridico::find($id);
        }

        return response()->json($cliente);
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
        // Determinar el tipo de cliente según el prefijo del ID
        if (str_starts_with($id, 'CN')) {
            $cliente = ClienteNatural::find($id);

            // Validar datos para Cliente Natural
            $validaciones = [
                'dui' => 'required|min:9|unique:cliente_natural,dui,' . $cliente->idCliente_natural . ',idCliente_natural',
                'nombres' => 'required',
                'apellidos' => 'required',
                'telefono' => 'required',
                'ingresos' => 'required',
                'egresos' => 'required',
                'lugarTrabajo' => 'required',
                'direccion' => 'required',
            ];
            $mensajes = [
                'dui.unique' => 'Este DUI ya ha sido registrado.',
            ];
        } elseif (str_starts_with($id, 'CJ')) {
            $cliente = ClienteJuridico::find($id);

            // Validar datos para Cliente Jurídico
            $validaciones = [
                'nit' => 'required|min:14|unique:cliente_juridico,nit,' . $cliente->idClienteJuridico . ',idClienteJuridico',
                'nombreEmpresa' => 'required',
                'telefonoJuridico' => 'required',
                'direccionJuridico' => 'required',
                'ventasNetas' => 'required',
                'activoCorriente' => 'required',
                'inventario' => 'required',
                'costoVentas' => 'required',
                'pasivoCorriente' => 'required',
                'cuentasCobrar' => 'required',
                'cuentasPagar' => 'required',
            ];
            $mensajes = [
                'nit.unique' => 'Este NIT ya ha sido registrado.',
            ];
        } else {
            return response()->json(['type' => 'error', 'message' => 'ID no válido.'], 400);
        }

        // Validar la solicitud
        $request->validate($validaciones, $mensajes);

        // Actualizar datos del cliente según el tipo
        if (str_starts_with($id, 'CN')) {
            $cliente->dui = $request->post('dui');
            $cliente->nombres = $request->post('nombres');
            $cliente->apellidos = $request->post('apellidos');
            $cliente->telefono = $request->post('telefono');
            $cliente->direccion = $request->post('direccion');
            $cliente->ingresos = $request->post('ingresos');
            $cliente->egresos = $request->post('egresos');
            $cliente->lugarTrabajo = $request->post('lugarTrabajo');
        } elseif (str_starts_with($id, 'CJ')) {

            $cliente->nit = $request->post('nit');
            $cliente->nombre_empresa = $request->post('nombreEmpresa');
            $cliente->telefono = $request->post('telefonoJuridico');
            $cliente->direccion = $request->post('direccionJuridico');
            $cliente->ventas_netas = $request->post('ventasNetas');
            $cliente->activo_corriente = $request->post('activoCorriente');
            $cliente->inventario = $request->post('inventario');
            $cliente->costos_ventas = $request->post('costoVentas');
            $cliente->pasivos_corriente = $request->post('pasivoCorriente');
            $cliente->cuentas_cobrar = $request->post('cuentasCobrar');
            $cliente->cuentas_pagar = $request->post('cuentasPagar');

            // Manejo de imágenes si existen archivos cargados
            if ($request->hasFile('balanceGeneral')) {
                $imagen = $request->file('balanceGeneral');
                $nombreClienteFormateado = str_replace(' ', '_', $cliente->nombre_empresa);
                $nombreImagenBalanceGeneral = $cliente->idClienteJuridico . '_' . $nombreClienteFormateado . '.' . $imagen->getClientOriginalExtension();
                $rutaImagen = public_path('/assets/img/documentoFinancieros');
                $imagen->move($rutaImagen, $nombreImagenBalanceGeneral);
                $cliente->balance_general = $nombreImagenBalanceGeneral;
            }

            if ($request->hasFile('estadoResultados')) {
                $imagen = $request->file('estadoResultados');
                $nombreClienteFormateado = str_replace(' ', '_', $cliente->nombre_empresa);
                $nombreImagenEstadoResultados = $cliente->idClienteJuridico . '_' . $nombreClienteFormateado . '.' . $imagen->getClientOriginalExtension();
                $rutaImagen = public_path('/assets/img/documentoFinancieros');
                $imagen->move($rutaImagen, $nombreImagenEstadoResultados);
                $cliente->estado_resultado = $nombreImagenEstadoResultados;
            }
        }

        // Guardar los cambios en la base de datos
        $cliente->save();

        // Respuesta de éxito
        return response()->json([
            'type' => 'success',
            'message' => 'Cliente actualizado exitosamente.',
        ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (str_starts_with($id, 'CN')) {
            // Buscar cliente natural
            $cliente = ClienteNatural::find($id);
            // Comprobar si tiene ventas asociadas
            $ventas = DB::table('venta')->where('IdCliente_natural', $cliente->idCliente_natural)->count();

            if ($ventas === 0) {
                $cliente->delete();
                $alert = [
                    'type' => 'success',
                    'message' => 'El cliente natural se ha eliminado exitosamente.',
                ];
            } else {
                $alert = [
                    'type' => 'error',
                    'message' => 'No se puede eliminar el cliente natural porque tiene datos asociados.',
                ];
            }
        } elseif (str_starts_with($id, 'CJ')) {
            // Buscar cliente jurídico
            $cliente = ClienteJuridico::find($id);

            // Comprobar si tiene ventas asociadas
            $ventas = DB::table('venta')->where('IdCliente_juridico', $cliente->idClienteJuridico)->count();

            if ($ventas === 0) {
                $cliente->delete();
                $alert = [
                    'type' => 'success',
                    'message' => 'El cliente jurídico se ha eliminado exitosamente.',
                ];
            } else {
                $alert = [
                    'type' => 'error',
                    'message' => 'No se puede eliminar el cliente jurídico porque tiene datos asociados.',
                ];
            }
        } else {
            // ID no válido
            $alert = [
                'type' => 'error',
                'message' => 'El ID proporcionado no corresponde a un cliente válido.',
            ];
        }

        return response()->json($alert);
    }


    public function baja($id)
    {
        if (str_starts_with($id, 'CN')) {
            $registro = ClienteNatural::find($id);
        } elseif (str_starts_with($id, 'CJ')) {
            $registro = ClienteJuridico::find($id);
        } else {
            $registro = Departamento::find($id);
        }

        if ($registro) {
            $registro->estado = 0;
            $registro->save();

            $alert = [
                'type' => 'success',
                'message' => 'El registro se ha deshabilitado exitosamente.',
            ];

            return response()->json($alert);
        } else {
            $alert = [
                'type' => 'error',
                'message' => 'El registro no se encontró.',
            ];
            return response()->json($alert);
        }
    }

    public function alta($id)
    {
        if (str_starts_with($id, 'CN')) {
            $registro = ClienteNatural::find($id);
        } elseif (str_starts_with($id, 'CJ')) {
            $registro = ClienteJuridico::find($id);
        } else {
            $registro = Departamento::find($id);
        }

        if ($registro) {
            $registro->estado = 1;
            $registro->save();

            $alert = [
                'type' => 'success',
                'message' => 'El registro se ha restaurado exitosamente.',
            ];
        } else {
            $alert = [
                'type' => 'error',
                'message' => 'El registro no se encontró.',
            ];
        }

        return response()->json($alert);
    }


    public function generarId($tipo)
    {
        if ($tipo == 0) {
            // Obtener el último registro de la tabla "departamento"
            $ultimocliente = ClienteNatural::latest('idCliente_natural')->first();

            if (!$ultimocliente) {
                // Si no hay registros previos, comenzar desde CA0001
                $nuevoId = 'CN0001';
            } else {
                // Obtener el número del último idDepartamento
                $ultimoNumero = intval(substr($ultimocliente->idCliente_natural, 2));

                // Incrementar el número para el nuevo registro
                $nuevoNumero = $ultimoNumero + 1;

                // Formatear el nuevo idDepartamento con ceros a la izquierda
                $nuevoId = 'CN' . str_pad($nuevoNumero, 4, '0', STR_PAD_LEFT);
            }
        } else {
            // Obtener el último registro de la tabla "departamento"
            $ultimocliente = ClienteJuridico::latest('idClienteJuridico')->first();

            if (!$ultimocliente) {
                // Si no hay registros previos, comenzar desde CA0001
                $nuevoId = 'CJ0001';
            } else {
                // Obtener el número del último idDepartamento
                $ultimoNumero = intval(substr($ultimocliente->idClienteJuridico, 2));

                // Incrementar el número para el nuevo registro
                $nuevoNumero = $ultimoNumero + 1;

                // Formatear el nuevo idDepartamento con ceros a la izquierda
                $nuevoId = 'CJ' . str_pad($nuevoNumero, 4, '0', STR_PAD_LEFT);
            }
        }


        return $nuevoId;
    }

    public function obtenerClientes($tipoCliente, $tipoClasificacion)
    {
        // Listas de valores válidos
        $validTipoCliente = ['c-todos', 'c-natural', 'c-juridico'];
        $validClasificacion = ['v-todos', 'v-A', 'v-B', 'v-C', 'v-D'];
    
        // Validar los parámetros
        if (!in_array($tipoCliente, $validTipoCliente) || !in_array($tipoClasificacion, $validClasificacion)) {
            return response()->json(['error' => 'Parámetro inválido. Por favor, verifica los valores enviados.'], 400);
        }
    
        // Convertir los parámetros para el procedimiento almacenado
        $tipoClasificacionDB = $tipoClasificacion === 'v-todos' ? 'Todos' : substr($tipoClasificacion, 2, 1);
        $tipoClienteDB = match ($tipoCliente) {
            'c-natural' => 'Natural',
            'c-juridico' => 'Jurídico',
            default => 'Todos',
        };

        // Llamar al procedimiento almacenado con ambos parámetros
        $clientes = DB::select('CALL ClasificarClientes(?, ?)', [$tipoClasificacionDB, $tipoClienteDB]);

        // Retornar los datos en formato JSON
        return response()->json($clientes);
    }
    
}
