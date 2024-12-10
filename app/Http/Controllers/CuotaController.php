<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cuota;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CuotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $cuotas = Cuota::all();
        $ventas = Venta::where('tipo', 1)->get(); // Filtra solo ventas a crédito

        return view('gestion-comercial.cuota.index', compact('cuotas', 'ventas'));
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
        // Validar la entrada
        $request->validate([
            'idCuota' => 'required|exists:cuotas,idCuota', // ID de la cuota existente
            'fechaPago' => 'required|date', // Fecha de pago es obligatoria
        ]);

        // Obtener la cuota
        $cuota = Cuota::findOrFail($request->idCuota);
        $fechaPago = Carbon::parse($request->fechaPago);
        $monto = $cuota->monto;

        // Calcular mora si la fecha de pago es posterior a la fecha límite
        $mora = 0;
        if ($fechaPago->greaterThan(Carbon::parse($cuota->fechaLimite))) {
            $mora = $monto * 0.05; // 5% del monto
        }

        // Actualizar la cuota
        $cuota->fechaPago = $fechaPago;
        $cuota->mora = $mora;
        $cuota->estado = 1; // Estado 1: Pagado
        $cuota->save();

        return response()->json([
            'message' => 'Cuota pagada correctamente.',
            'mora' => $mora,
        ]);
    }


    /**
     * Display the specified resource.
     */

    public function show($idVenta)
    {
        // Obtén la información de la venta
        $venta = Venta::with(['cliente_natural', 'cliente_juridico'])->findOrFail($idVenta);

        // Determina el tipo de cliente
        $clienteTipo = $venta->cliente_natural ? 'natural' : ($venta->cliente_juridico ? 'juridico' : 'desconocido');

        // Formatea los datos de la venta
        $ventaData = (object) [
            'idVenta' => $venta->idVenta,
            'fecha' => $venta->fecha,
            'total' => number_format($venta->total, 2),
            'cliente' => $venta->cliente_natural
                ? $venta->cliente_natural->nombres . ' ' . $venta->cliente_natural->apellidos
                : ($venta->cliente_juridico->nombre_empresa ?? 'Sin asignar'),
            'telefono' => $venta->cliente_natural
                ? $venta->cliente_natural->telefono
                : ($venta->cliente_juridico->telefono ?? 'Sin teléfono'),
            'direccion' => $venta->cliente_natural
                ? $venta->cliente_natural->direccion
                : ($venta->cliente_juridico->direccion ?? 'Sin dirección'),
        ];

        // No cargues cuotas aquí
        return view('gestion-comercial.cuota.detalles', compact('ventaData', 'clienteTipo'));
    }



    public function obtenerCuotas(string $idVenta)
    {
        return Cuota::where('idVenta', $idVenta)
            ->get()
            ->map(function ($cuota) {
                $cuota->estadoTexto = $cuota->estado === 1 ? 'Pagado' : 'Pendiente';
                return $cuota;
            });
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
        //
    }

    public function generarId()
    {
        // Obtener el último registro de la tabla "departamento"
        $ultimoCuota = Cuota::latest('idCuota')->first();

        if (!$ultimoCuota) {
            // Si no hay registros previos, comenzar desde CA0001
            $nuevoId = 'CT0001';
        } else {
            // Obtener el número del último idDepartamento
            $ultimoNumero = intval(substr($ultimoCuota->idCuota, 2));

            // Incrementar el número para el nuevo registro
            $nuevoNumero = $ultimoNumero + 1;

            // Formatear el nuevo idDepartamento con ceros a la izquierda
            $nuevoId = 'CT' . str_pad($nuevoNumero, 4, '0', STR_PAD_LEFT);
        }

        return $nuevoId;
    }


    public function getCuotas($id)
    {
        $cuotas = Cuota::where('idVenta', $id)->get();

        // Log para verificar los datos
        Log::info('Cuotas encontradas:', $cuotas->toArray());

        return response()->json($cuotas);
    }

    public function generarCuotasAutomaticas($idVenta)
    {
        // Obtén la venta asociada
        $venta = Venta::findOrFail($idVenta);

        // Configuración de las cuotas
        $numeroCuotas = $venta->meses; // Total de cuotas (meses)
        $montoTotal = $venta->SaldoCapital; // Monto total de la venta
        $montoCuota = round($montoTotal / $numeroCuotas, 2); // Monto por cuota
        $fechaInicial = Carbon::parse($venta->fecha); // Fecha de la venta

        // Generar cuotas
        for ($i = 1; $i <= $numeroCuotas; $i++) {
            // Calcula la fecha límite de pago: se incrementa un mes por cada cuota
            $fechaLimite = $fechaInicial->copy()->addMonths($i); // Incrementar meses desde la fecha de la venta

            // Crear la cuota con un ID único
            Cuota::create([
                'idCuota' => $this->generarId(), // Generar ID único para la cuota
                'idVenta' => $idVenta,
                'fechaPago' => null, // Especifica NULL explícitamente
                'fechaLimite' => $fechaLimite->format('Y-m-d'), // Formato de fecha
                'monto' => $montoCuota,
                'mora' => 0, // Mora inicialmente es 0
                'estado' => 0, // Estado 0: Pendiente
            ]);
        }

        // Respuesta de éxito
        return response()->json([
            'message' => 'Cuotas generadas exitosamente.',
        ]);
    }

    public function actualizarFecha(Request $request, $idCuota)
    {
        $request->validate([
            'fechaPago' => 'required|date',
        ]);

        $cuota = Cuota::findOrFail($idCuota);

        // Convertir fechas para comparaciones
        $fechaPago = Carbon::parse($request->fechaPago);
        $fechaLimite = Carbon::parse($cuota->fechaLimite);

        if ($fechaPago->greaterThan($fechaLimite)) {
            // Si la fecha de pago es posterior a la fecha límite
            $mora = $cuota->monto * 0.05; // 5% del monto
            $cuota->mora = $mora; // Asignar la mora
            $cuota->estado = 1; // Cambiar el estado a "Cancelado"
        } elseif ($fechaPago->lessThanOrEqualTo($fechaLimite)) {
            // Si la fecha de pago es igual o anterior a la fecha límite
            $cuota->mora = 0; // No hay mora
            $cuota->estado = 1; // Cambiar el estado a "Cancelado"
        }

        // Cambiar estado a "En mora" si ya pasó la fecha límite y no se ha pagado
        if (Carbon::now()->greaterThan($fechaLimite) && !$cuota->fechaPago) {
            $cuota->estado = 2; // En mora
        }

        // Actualizar la fecha de pago
        $cuota->fechaPago = $fechaPago;

        $cuota->save();

        return response()->json(['message' => 'Fecha de pago actualizada correctamente.']);
    }


    public function actualizarEstadoCuotas()
    {
        // Obtener todas las cuotas pendientes (estado = 0)
        $cuotasPendientes = Cuota::where('estado', 0)->get();

        foreach ($cuotasPendientes as $cuota) {
            $fechaLimite = Carbon::parse($cuota->fechaLimite);
            $fechaActual = Carbon::now();

            // Si la fecha actual es mayor a la fecha límite, cambia el estado a "En mora"
            if ($fechaActual->greaterThan($fechaLimite)) {
                $cuota->estado = 2; // En mora
                $cuota->mora = $cuota->monto * 0.05; // Calcula la mora (5% del monto)
                $cuota->save(); // Guarda los cambios
            }
        }

        return response()->json(['message' => 'Estados de cuotas actualizados correctamente.']);
    }
}
