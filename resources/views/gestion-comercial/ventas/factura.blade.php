<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Factura de Venta</title>
    <style>
        body {
            font-family: "Arial", sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header .company-info {
            text-align: left;
        }

        .header .company-info h1 {
            font-size: 20px;
            margin: 0;
            color: #555;
        }

        .header .company-info p {
            margin: 5px 0;
            font-size: 12px;
        }

        .header .logo img {
            max-width: 120px;
            height: auto;
        }

        .title {
            text-align: center;
            margin: 20px 0;
            font-size: 24px;
            font-weight: bold;
            color: #444;
        }

        .details {
            margin: 20px 0;
            font-size: 14px;
            line-height: 1.6;
        }

        .details strong {
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f7f7f7;
            color: #333;
            font-size: 14px;
        }

        tfoot .total-label {
            text-align: right;
            font-weight: bold;
            font-size: 16px;
            color: #333;
        }

        tfoot .total-value {
            font-weight: bold;
            font-size: 16px;
            color: #333;
            text-align: right;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }

        .footer p {
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <h1>{{ $empresa->nombre ?? 'Nombre de la Empresa' }}</h1>
                </p>
            </div>
            <div class="logo">
                @if ($empresa && $empresa->logo)
                    <img src="{{ public_path($empresa->logo) }}" alt="Logo de {{ $empresa->nombre }}"
                        style="max-width: 150px; height: auto;">
                @else
                    <img src="{{ public_path('assets/img/empresas/default-logo.png') }}" alt="Logo Genérico"
                        style="max-width: 150px; height: auto;">
                @endif
            </div>
        </div>

        <!-- Title -->
        <div class="title">
            Factura de Venta
        </div>

        <!-- Sale Details -->
        <div class="details">
            <p><strong>ID Venta:</strong> {{ $venta->idVenta }}</p>
            <p><strong>Fecha:</strong> {{ $venta->fecha }}</p>
            <p><strong>Cliente:</strong>
                @if ($venta->cliente_natural)
                    {{ $venta->cliente_natural->nombres }} {{ $venta->cliente_natural->apellidos }}
                @elseif ($venta->cliente_juridico)
                    {{ $venta->cliente_juridico->nombre_empresa }}
                @else
                    Cliente no asignado
                @endif
            </p>
            <p><strong>Tipo de Venta:</strong> {{ $venta->tipo == 0 ? 'Contado' : 'Crédito' }}</p>
        </div>

        <!-- Product Table -->
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Venta</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->producto->nombre }}</td>
                        <td>{{ $detalle->cantidad }}</td>
                        <td>${{ number_format($detalle->precioVenta, 2) }}</td>
                        <td>${{ number_format($detalle->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="total-label">Total:</td>
                    <td class="total-value">${{ number_format($venta->total, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p>Gracias por su compra.</p>
            <p>Todos los derechos reservados &copy; {{ date('Y') }}
                {{ $empresa->nombre ?? 'Nombre de la Empresa' }}.</p>
        </div>
    </div>
</body>

</html>
