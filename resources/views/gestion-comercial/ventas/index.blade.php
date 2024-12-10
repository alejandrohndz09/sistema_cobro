@extends('layouts.user_type.auth')
@section('styles')
    <link rel="stylesheet" href="<?php echo asset('css/extras.css'); ?>" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .drop-relative {
            position: relative !important;
            /* Esto hace que el dropdown se posicione dentro de este contenedor */
        }

        /* Estilos del dropdown */
        .dropdown-results {
            display: block;
            position: absolute;
            /* Se posiciona de manera relativa al input */
            top: 100%;
            /* Lo coloca justo debajo del input */
            left: 0;
            width: 100%;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            /* Asegúrate de que se muestre encima de otros elementos */
            max-height: 200px;
            overflow-y: auto;
            margin-top: 5px;
        }

        .dropdown-results li {
            padding: 8px;
            cursor: pointer;
            list-style-type: none;
        }

        .dropdown-results li:hover {
            background-color: #f0f0f0;
            /* Sombreado al pasar el cursor */
        }

        /* Estilo para el loader */
        .spinner {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
        }

        /* Animación del spinner */
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endsection
@section('scripts')
    <script src="{{ asset('js/tablas.js') }}"></script>
    <script src="{{ asset('js/validaciones/jsVenta.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection
@section('content')
    <div class="container-fluid ">
        <div class="row">
            <div class="col-lg-9">
                {{-- <div class="row ">
                    <div class="col-md-3 col-sm-3 mb-4">
                        <div class="card">
                            <div class="card-header mx-4 p-3 text-center">
                                <div class="icon icon-shape icon-lg bg-gradient-dark shadow text-center border-radius-lg">
                                    <i class="fas fa-building opacity-10"></i>
                                </div>
                            </div>
                            <div class="card-body pt-0 p-3 text-center">
                                <h6 class="text-center mb-0">Edificaciones</h6>
                                <span class="text-xs">Valor estimado:</span>
                                <hr class="horizontal dark my-1">
                                <h5 class="mb-0">+$2000</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 mb-4">
                        <div class="card">
                            <div class="card-header mx-4 p-3 text-center">
                                <div class="icon icon-shape icon-lg bg-gradient-info shadow text-center border-radius-lg">
                                    <i class="fas fa-snowplow opacity-10"></i>
                                </div>
                            </div>
                            <div class="card-body pt-0 p-3 text-center">
                                <h6 class="text-center mb-0">Maquinaria</h6>
                                <span class="text-xs">Valor estimado:</span>
                                <hr class="horizontal dark my-1">
                                <h5 class="mb-0">+$2000</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 mb-4">
                        <div class="card">
                            <div class="card-header mx-4 p-3 text-center">
                                <div
                                    class="icon icon-shape icon-lg bg-gradient-warning shadow text-center border-radius-lg">
                                    <i class="fas fa-car opacity-10"></i>
                                </div>
                            </div>
                            <div class="card-body pt-0 p-3 text-center">
                                <h6 class="text-center mb-0">Vehículos</h6>
                                <span class="text-xs">Valor estimado:</span>
                                <hr class="horizontal dark my-1">
                                <h5 class="mb-0">+$2000</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 mb-4">
                        <div class="card">
                            <div class="card-header mx-4 p-3 text-center">
                                <div
                                    class="icon icon-shape icon-lg bg-gradient-success shadow text-center border-radius-lg">
                                    <i class="fas fa-computer opacity-10"></i>
                                </div>
                            </div>
                            <div class="card-body pt-0 p-3 text-center">
                                <h6 class="text-center mb-0">Otros bienes</h6>
                                <span class="text-xs">Valor estimado:</span>
                                <hr class="horizontal dark my-1">
                                <h5 class="mb-0">$455.00</h5>
                            </div>
                        </div>
                    </div>
                </div> --}}

                <div class="col-md-12 mb-lg-0 mb-4">
                    <div class="card">
                        <div class="card-header pb-0 p-3">
                            <div class="row">
                                <div class="col-4 d-flex align-items-center">
                                    <h4 class="mb-0">Registros</h4>
                                </div>
                                <div class="col-8 d-flex align-items-center justify-content-end">
                                    <a role="button" data-bs-toggle="collapse" data-bs-target="#collapseExample"
                                        data-bs-tt="tooltip" data-bs-original-title="Filtrar" aria-hidden="false">
                                        <i class="fas fa-filter text-lg text-dark"></i>
                                    </a>
                                    <div class="ms-2 input-group" style="width: 40%">
                                        <span class="input-group-text text-body"><i class="fas fa-search"
                                                aria-hidden="true"></i></span>
                                        <input type="text" id="searchInput" class="form-control" placeholder="Buscar...">
                                    </div>
                                    <a class="btn bg-gradient-dark mb-0 ms-1" href="javascript:;" data-bs-toggle="modal"
                                        id="btnAgregar" data-bs-target="#modalForm"><i
                                            class="fas fa-plus"></i>&nbsp;&nbsp;Agregar</a>

                                </div>
                            </div>

                            <div class="collapsenull" id="collapseExample">
                                <div class="row mt-3">
                                    <div class="col-4">
                                        <label>Tipo de venta:</label>
                                        <div class="nav-wrapper position-relative end-0">
                                            <ul class="nav nav-pills nav-fill p-1 bg-gray-100" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link mb-0 px-0 py-1 active"data-bs-toggle="tab"
                                                        data-sort="v-todas" role="tab" aria-selected="true">
                                                        <i class="ni ni-bullet-list-67 text-sm me-2"></i> Todos
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab"
                                                        data-sort="v-contado" role="tab" aria-selected="false">
                                                        <i class="ni ni-money-coins text-sm me-2"></i> Contado
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab"
                                                        data-sort="v-credito" role="tab" aria-selected="false">
                                                        <i class="fas fa-chart-line text-sm me-2"></i> Crédito
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="ms-4 col-4">
                                        <label>Tipo de cliente:</label>
                                        <div class="nav-wrapper position-relative end-0">
                                            <ul class="nav nav-pills nav-fill p-1 bg-gray-100" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab"
                                                        data-sort="c-todos" role="tab" aria-selected="true">
                                                        <i class="ni ni-bullet-list-67 text-sm me-2"></i> Todos
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab"
                                                        data-sort="c-natural" role="tab" aria-selected="false">
                                                        <i class="fas fa-person text-sm me-2"></i> Natural
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab"
                                                        data-sort="c-juridico" role="tab" aria-selected="false">
                                                        <i class="fas fa-building text-sm me-2"></i> Jurídico
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive p-0">
                                <table id="myTable" class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 3%">
                                            </th>
                                            <th
                                                class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Código
                                            </th>
                                            <th
                                                class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Fecha
                                            </th>
                                            <th style="text-align: left !important;"
                                                class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 dt-type-">
                                                Monto
                                            </th>
                                            <th
                                                class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Cliente
                                            </th>
                                            <th
                                                class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Tipo
                                            </th>
                                            <th
                                                class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Estado
                                            </th>
                                            <th
                                                class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Acción
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableBody">
                                        @foreach ($ventas as $v)
                                            <tr class="tr-link" data-id="{{ $v->idVenta }}">
                                                <td>
                                                    <div
                                                        class="avatar avatar-sm icon bg-gradient-info shadow text-center border-radius-lg">
                                                        <i class="fas fa-arrow-alt-circle-up opacity-10 text-sm"></i>
                                                    </div>
                                                </td>
                                                <td class="px-1">
                                                    <p class="text-xs font-bold mb-0">{{ $v->idVenta }}</p>
                                                </td>
                                                <td class="px-1">
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ $v->fecha->format('d/m/y') }}</p>
                                                    <p class="text-xxs  mb-0">({{ $v->fecha->format('h:i:s a') }})</p>
                                                </td>
                                                <td class="px-1">
                                                    <p class="text-xs font-weight-bold mb-0"
                                                        style="text-align: left !important;">
                                                        {{ '$' . number_format($v->total, 2, '.', ',') }}
                                                    </p>
                                                </td>
                                                <td class="px-1">
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        <i
                                                            class="fas fa-{{ $v->cliente_natural != null ? 'person' : 'building' }} text-xxs"></i>&nbsp;
                                                        {{ $v->cliente_natural != null
                                                            ? $v->cliente_natural->nombres . ' ' . $v->cliente_natural->apellidos
                                                            : $v->cliente_juridico->nombre_empresa }}
                                                    </p>

                                                    <p class="text-xxs  mb-0">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;{{ $v->cliente_natural != null ? 'Cliente Natural' : 'Cliente Jurídico' }}
                                                    </p>
                                                </td>
                                                <td class="px-1">
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ $v->tipo == 1 ? 'Credito' : 'Contado' }}
                                                    </p>
                                                </td>
                                                <td class="px-1 text-xs">
                                                    <span
                                                        class="badge badge-xs opacity-7 bg-{{ $v->estado == 1 ? 'success' : 'secondary' }} ">
                                                        {{ $v->estado == 1 ? 'activa' : 'inactiva' }}</span>
                                                </td>
                                                <td>
                                                    @if ($v->estado == 1)
                                                        {{-- <a role="button" data-bs-toggle="modal"
                                                            data-bs-target="#modalForm" data-id="{{ $v->idVenta }}"
                                                            data-bs-tt="tooltip" data-bs-original-title="Editar"
                                                            class="btnEditar me-2">
                                                            <i class="fas fa-pen text-secondary"></i>
                                                        </a> --}}

                                                        <a role="button" data-bs-toggle="modal"
                                                            data-bs-target="#modalConfirm" data-id="{{ $v->idVenta }}"
                                                            data-bs-tt="tooltip" data-bs-original-title="Deshabilitar"
                                                            class="btnDeshabilitar">
                                                            <i class="fas fa-minus-circle text-secondary"></i>
                                                        </a>
                                                    @else
                                                        <a role="button" data-id="{{ $v->idVenta }}"
                                                            data-bs-tt="tooltip" data-bs-original-title="Habilitar"
                                                            class="btnHabilitar me-2">
                                                            <i class="fas fa-arrow-up text-secondary"></i>
                                                        </a>

                                                        <a role="button" data-bs-toggle="modal"
                                                            data-bs-target="#modalConfirm" data-id="{{ $v->idVenta }}"
                                                            data-bs-tt="tooltip" data-bs-original-title="Eliminar"
                                                            class="btnEliminar">
                                                            <i class="fas fa-trash text-secondary"></i>
                                                        </a>
                                                    @endif
                                                    <!-- Nuevo botón para generar factura -->
                                                    <a role="button"
                                                        href="{{ route('ventas.pdfFactura', $v->idVenta) }}"
                                                        data-bs-tt="tooltip" data-bs-original-title="Generar Factura"
                                                        class="btnGenerarFactura">
                                                        <i class="fas fa-file-invoice text-secondary"></i>
                                                    </a>

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div id="pagination" class="d-flex justify-content-center mt-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="row mx-1 dropdown">
                    <a href=""
                        class="btn bg-gradient-dark dropdown-toggle text text-transform-none border-radius-lg"
                        data-bs-toggle="dropdown" id="navbarDropdownMenuLink2">
                        <i class="fas fa-file-contract me-2"></i>
                        <span style="text-transform: none">Informes de Ventas</span>
                    </a>
                    <ul class="dropdown-menu w-100" aria-labelledby="navbarDropdownMenuLink2">
                        <li>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#PDFmodal"
                                onclick="setDepreciationType('anual')">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    Anual
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#PDFmodal"
                                onclick="setDepreciationType('mensual')">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    Mensual
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#PDFmodal"
                                onclick="setDepreciationType('diaria')">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    Diaria
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card">
                    <div class="card-header pb-0 p-3">
                        <div class="row d-flex align-items-center">

                            <h6 class="mb-0">Ventas por Sucursal</h6>

                        </div>
                    </div>
                    <div class="card-body p-3 pb-0">
                        <ul class="list-group">
                            <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">

                                <div class="d-flex align-items-center">
                                    <button
                                        class="btn btn-icon-only btn-rounded btn-outline-success mb-0 me-3 btn-sm d-flex align-items-center justify-content-center">
                                        <i class="fas fa-map-marker-alt"></i></button>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-1 text-dark font-weight-bold text-sm">San Vicente</h6>
                                        <span class="text-xs">Valor distribuido:</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center text-sm font-weight-bold">
                                    $ 2,000
                                </div>
                            </li>
                            <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">

                                <div class="d-flex align-items-center">
                                    <button
                                        class="btn btn-icon-only btn-rounded btn-outline-success mb-0 me-3 btn-sm d-flex align-items-center justify-content-center">
                                        <i class="fas fa-map-marker-alt"></i></button>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-1 text-dark font-weight-bold text-sm">San Salvador</h6>
                                        <span class="text-xs">Valor distribuido:</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center text-sm font-weight-bold">
                                    $ 2,000
                                </div>
                            </li>
                            <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                <div class="d-flex align-items-center">
                                    <button
                                        class="btn btn-icon-only btn-rounded btn-outline-success mb-0 me-3 btn-sm d-flex align-items-center justify-content-center">
                                        <i class="fas fa-map-marker-alt"></i></button>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-1 text-dark font-weight-bold text-sm">Santa Ana</h6>
                                        <span class="text-xs">Valor distribuido:</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center text-sm font-weight-bold">
                                    $ 2,000
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('gestion-comercial.ventas.modales')
@endsection
