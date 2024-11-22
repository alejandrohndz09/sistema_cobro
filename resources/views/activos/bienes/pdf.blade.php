<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Nucleo Icons -->
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <link id="pagestyle" href="{{ public_path('css/soft-ui-dashboard.css?v=1.0.3') }} " rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo asset('css/extras.css'); ?>" type="text/css">

    <title>Registro de Bienes</title>
    {{-- <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            margin: 20px auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            align-items: center;
            padding: 20px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #ddd;
        }

        .header img {
            width: 200px;
            height: auto;
            border-radius: 5px;
            margin-right: 20px;
        }

        .header .info {
            flex: 1;
            mar
        }

        .header h1 {
            margin: 0;
            font-size: 1.5em;
            color: #333;
        }

        .header span {
            font-size: 0.9em;
            color: #555;
        }

        .description {
            padding: 20px;
        }

        .description h2 {
            margin: 0 0 10px;
            font-size: 1.2em;
            color: #333;
        }

        .description p {
            margin: 5px 0;
            font-size: 0.95em;
            color: #555;
        }

        .table-container {
            margin: 20px;
            border-top: 1px solid #ddd;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th,
        table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 0.9em;
        }

        table th {
            background-color: #f8f9fa;
            color: #333;
        }

        table td .status {
            font-weight: bold;
            color: #28a745;
        }

        table td .progress-bar {
            display: block;
            width: 100%;
            height: 6px;
            border-radius: 3px;
            background-color: #e9ecef;
            margin-top: 5px;
            overflow: hidden;
            position: relative;
        }

        table td .progress-bar span {
            display: block;
            height: 100%;
            background-color: #28a745;
        }
    </style> --}}
</head>

<body>
    <div class="container">

        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="bg-white border-radius-lg h-100 position-relative overflow-hidden">
                                <img src="assets/img/activos/{{ $activo->imagen }}"
                                    style="width: 100%; height: 100%; object-fit: cover;"
                                    class="position-absolute top-0 start-0">
                            </div>

                        </div>
                        <div class="col-lg-6 ms-auto mt-5 mt-lg-0">
                            <div class="d-flex flex-column h-100">
                                <h5 class="font-weight-bolder mb-1">{{ $activo->nombre }}</h5>
                                @php
                                    $vida_util =
                                        $activo->categoria->depreciacion_anual > 0
                                            ? 1 / $activo->categoria->depreciacion_anual
                                            : 0;
                                @endphp
                                <p class="mb-0 mt-0 text-bold"><i class="fas fa-tag text-xs"></i>
                                    {{ $activo->categoria->nombre }}
                                    <span
                                        class="text-xs">({{ $vida_util > 1 ? "{$vida_util} años " : "{$vida_util} año " }}de
                                        vida útil).</span>
                                </p>

                                <p class="mb-1 mt-0 "><i class="fas fa-cubes text-xs"></i>
                                    {{ $activo->bienes->count() > 1 ? "{$activo->bienes->count()} Bienes registrados." : "{$activo->bienes->count()} Bien registrado." }}
                                </p>
                                <p class="mb-1 mt-0 text-sm"><i class="fas fa-info-circle text-xs"></i> Descripción:
                                </p>
                                <div class=" text-xs" style="font-size: 12px !important;">
                                    {!! \Parsedown::instance()->text($activo->descripcion) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <!-- Tabla -->
        <div class="table-container">
            <h2>Registro de Bienes</h2>
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Fecha Adquisición</th>
                        <th>V. Adquisición</th>
                        <th>V. Actual</th>
                        <th>Depreciación Acum.</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>SC0001-DP0001-AC0001-BN0001</td>
                        <td>10/01/20</td>
                        <td>$150,000.00</td>
                        <td>$113,585.40</td>
                        <td>
                            24.28%
                            <div class="progress-bar">
                                <span style="width: 24%;"></span>
                            </div>
                        </td>
                        <td><span class="status">ACTIVO</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div> --}}
</body>

</html>
