<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            margin-bottom: 40px;
        }

        h5 {
            color: #333;
            text-align: center;
            font-weight: bold;
            margin: 0;
        }

        .fecha {
            color: #333;
            font-weight: bold;
            margin: 0;
            margin-left: 86%;
        }

        .header {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 5px;
        }

        .logo {
            height: 50px;
            margin-top: -10%;
        }

        .line {
            width: 116%;
            border-top: 2px solid #000;
            margin: 1px 0;
            margin-left: -8%;
        }

        table {
            width: 118%;
            border-collapse: collapse;
            margin-top: 1px;
            font-size: 10px;
            margin-left: -9%;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .codigo-cell {
            width: 25%;
        }

        /* Alineación a la derecha para los totales */
        .right-align {
            text-align: right;
        }

        /* CSS para el pie de página con el número de página */
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #333;
        }
    </style>
</head>

<body>
    <div class="header">
        <h5>{{ $nombreEmpresa }}</h5>
        <h5>Informe de depreciación {{ $tipoDepreciacion }}</h5>
        <h6 class="fecha">Fecha: {{ now()->format('d/m/Y') }}</h6>
        <div class="line"></div>
        <img src="{{ $logo }}" alt="Logo" class="logo">
    </div>

    <!-- Tabla de datos -->
    <table>
        <thead>
            <tr>
                <th class="codigo-cell">Código</th>
                <th>Nombre</th>
                <th>Fecha de adquisición</th>
                <th>Precio de adquisición</th>
                <th>Depreciación anual</th>
                <th>Valor a Depreciar</th>
                <th>Depreciación acumulada</th>
                <th>Valor en libros</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($resultados as $resultado)
                <tr>
                    <td style="font-size: 9px">{{ $resultado->Codigo }}</td>
                    <td>{{ $resultado->nombre }}</td>
                    <td>{{ \Carbon\Carbon::parse($resultado->fechaAdquisicion)->format('d/m/Y') }}</td>
                    <td>${{ number_format($resultado->precio, 2) }}</td>
                    <td>{{ $resultado->depreciacion_anual }}%</td>
                    <td>${{ number_format($resultado->depreciacion, 2) }}</td>
                    <td>${{ number_format($resultado->depreciacion_acumulada, 2) }}</td>
                    <td>${{ number_format($resultado->valor_en_libros, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4"><strong>TOTAL DE ACTIVOS: {{ $totalActivos }}</strong></td>
                <td><strong>TOTALES</strong></td>
                <td class="right-align"><strong>${{ number_format($totalDepreciacion, 2) }}</strong></td>
                <td class="right-align"><strong>${{ number_format($totalDepreciacionAcumulada, 2) }}</strong></td>
                <td class="right-align"><strong>${{ number_format($totalValorEnLibros, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>
    <!-- Pie de página con número de página -->
    <script type="text/php">
    if ( isset($pdf) ) {
        $pdf->page_script('
            $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
            $pdf->text(270, 820, "$PAGE_NUM de $PAGE_COUNT", $font, 10);
        ');
    }
    </script>
</body>



</html>
