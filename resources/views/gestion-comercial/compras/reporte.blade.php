<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Compras</title>
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
    <h3>Reporte de compras desde {{ date('d-m-Y', strtotime($fechaInicio)) }} hasta
        {{ date('d-m-Y', strtotime($fechaFin)) }}</h3>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th> 
                <th>Costo Total</th>
                <th>Numero de compras</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $currentProveedor = null;
                foreach ($results as $row): 
                    if ($currentProveedor !== $row->Proveedor) {
                        $currentProveedor = $row->Proveedor;
                        echo "<tr><td colspan='6' style='background-color: #e0e0e0; font-weight: bold;'>Proveedor: " . htmlspecialchars($currentProveedor, ENT_QUOTES, 'UTF-8') . "</td></tr>";
                    }
            ?>
            <tr>
                <td><?= htmlspecialchars($row->Producto, ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= number_format($row->CantidadTotal) ?></td>
                <td><?= number_format($row->CostoTotal, 2) ?></td>
                <td><?= $row->TotalCompras ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>

</html>
