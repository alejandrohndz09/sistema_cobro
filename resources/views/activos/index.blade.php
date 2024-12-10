@extends('layouts.user_type.auth')
@section('styles')
    <link rel="stylesheet" href="<?php echo asset('css/extras.css'); ?>" type="text/css">
@endsection
@section('scripts')
    <script src="{{ asset('js/tablas.js') }}"></script>
    <script src="{{ asset('js/validaciones/jsActivo.js') }}"></script>
@endsection
@section('content')
    <div class="container-fluid ">
        <div class="row">
            <div class="col-lg-9">
                <div class="row">
                    <!-- Columna de Edificaciones -->
                    <div class="col-md-3 col-sm-3 mb-4">
                        <div class="card card-edificaciones">
                            <div class="card-header mx-4 p-3 text-center">
                                <div class="icon icon-shape icon-lg bg-gradient-dark shadow text-center border-radius-lg">
                                    <i class="fas fa-building opacity-10"></i>
                                </div>
                            </div>
                            <div class="card-body pt-0 p-3 text-center">
                                <h6 class="text-center mb-0">Edificaciones</h6>
                                <span class="text-xs">Valor estimado:</span>
                                <hr class="horizontal dark my-1">
                                <h5 class="mb-0">
                                    ${{ number_format(optional($resultados->firstWhere('categoria_agrupada', 'Edificación'))->total, 2) ?? '0.00' }}
                                </h5>
                            </div>
                        </div>
                    </div>

                    <!-- Columna de Maquinaria -->
                    <div class="col-md-3 col-sm-3 mb-4">
                        <div class="card card-maquinaria">
                            <div class="card-header mx-4 p-3 text-center">
                                <div class="icon icon-shape icon-lg bg-gradient-info shadow text-center border-radius-lg">
                                    <i class="fas fa-snowplow opacity-10"></i>
                                </div>
                            </div>
                            <div class="card-body pt-0 p-3 text-center">
                                <h6 class="text-center mb-0">Maquinaria</h6>
                                <span class="text-xs">Valor estimado:</span>
                                <hr class="horizontal dark my-1">
                                <h5 class="mb-0">
                                    ${{ number_format(optional($resultados->firstWhere('categoria_agrupada', 'Maquinaria'))->total, 2) ?? '0.00' }}
                                </h5>
                            </div>
                        </div>
                    </div>

                    <!-- Columna de Vehículos -->
                    <div class="col-md-3 col-sm-3 mb-4">
                        <div class="card card-vehiculos">
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
                                <h5 class="mb-0">
                                    ${{ number_format(optional($resultados->firstWhere('categoria_agrupada', 'Vehiculo'))->total, 2) ?? '0.00' }}
                                </h5>
                            </div>
                        </div>
                    </div>

                    <!-- Columna de Otros Bienes -->
                    <div class="col-md-3 col-sm-3 mb-4">
                        <div class="card card-otros-bienes">
                            <div class="card-header mx-4 p-3 text-center">
                                <div
                                    class="icon icon-shape icon-lg bg-gradient-success shadow text-center border-radius-lg">
                                    <i class="fas fa-box opacity-10"></i>
                                </div>
                            </div>
                            <div class="card-body pt-0 p-3 text-center">
                                <h6 class="text-center mb-0">Otros Bienes</h6>
                                <span class="text-xs">Valor estimado:</span>
                                <hr class="horizontal dark my-1">
                                <h5 class="mb-0">
                                    ${{ number_format(optional($resultados->firstWhere('categoria_agrupada', 'Otros bienes muebles'))->total, 2) ?? '0.00' }}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-12 mb-lg-0 mb-4">
                    <div class="card">
                        <div class="card-header pb-0 p-3">
                            <div class="row">
                                <div class="col-6 d-flex align-items-center">
                                    <h4 class="mb-0">Registros</h4>
                                </div>
                                <div class="col-6 d-flex align-items-end justify-content-end">
                                    <div class="input-group" style="width: 60%">
                                        <span class="input-group-text text-body"><i class="fas fa-search"
                                                aria-hidden="true"></i></span>
                                        <input type="text" id="searchInput" class="form-control" placeholder="Buscar...">
                                    </div>
                                    <div class="text-end ms-2">
                                        <a class="btn bg-gradient-dark mb-0" href="javascript:;" data-bs-toggle="modal"
                                            id="btnAgregar" data-bs-target="#modalForm"><i
                                                class="fas fa-plus"></i>&nbsp;&nbsp;Agregar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 3%">
                                            </th>
                                            <th
                                                class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Código
                                            </th>
                                            <th
                                                class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Activo
                                            </th>
                                            <th
                                                class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Bienes Reg.
                                            </th>
                                            <th
                                                class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                V. Act. Acum.
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
                                        @foreach ($activos as $a)
                                            <tr class="tr-link" data-id="{{ $a->idActivo }}">
                                                <td>

                                                    @if (isset($a->imagen))
                                                        <div>
                                                            <img src="../assets/img/activos/{{ $a->imagen }}"
                                                                class="avatar avatar-sm me-3">
                                                        </div>
                                                    @else
                                                        <div
                                                            class="avatar avatar-sm icon bg-gradient-info shadow text-center border-radius-lg">
                                                            <i class="fas fa-cube opacity-10 text-sm"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="px-1">
                                                    <p class="text-xs font-bold mb-0">{{ $a->idActivo }}</p>
                                                </td>
                                                <td class="px-1">
                                                    <p class="text-xs font-weight-bold mb-0">{{ $a->nombre }}</p>
                                                    <p class="text-xxs  mb-0">({{ $a->categoria->nombre }})</p>
                                                </td>
                                                <td class="px-5">
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ $a->bienes->where('estado', 1)->count() }}</p>
                                                </td>
                                                <td class="px-1">
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ '$' . number_format($a->bienes->sum('precio'), 2, '.', ',') }}
                                                    </p>
                                                </td>
                                                <td class="px-1 text-xs">
                                                    <span
                                                        class="badge badge-xs opacity-7 bg-{{ $a->estado == 1 ? 'success' : 'secondary' }} ">
                                                        {{ $a->estado == 1 ? 'activo' : 'inactivo' }}</span>
                                                </td>
                                                <td>
                                                    @if ($a->estado == 1)
                                                        <a role="button" data-bs-toggle="modal"
                                                            data-bs-target="#modalForm" data-id="{{ $a->idActivo }}"
                                                            data-bs-tt="tooltip" data-bs-original-title="Editar"
                                                            class="btnEditar me-2">
                                                            <i class="fas fa-pen text-secondary"></i>
                                                        </a>

                                                        <a role="button" data-bs-toggle="modal"
                                                            data-bs-target="#modalConfirm" data-id="{{ $a->idActivo }}"
                                                            data-bs-tt="tooltip" data-bs-original-title="Deshabilitar"
                                                            class="btnDeshabilitar">
                                                            <i class="fas fa-minus-circle text-secondary"></i>
                                                        </a>
                                                    @else
                                                        <a role="button" data-id="{{ $a->idActivo }}"
                                                            data-bs-tt="tooltip" data-bs-original-title="Habilitar"
                                                            class="btnHabilitar me-2">
                                                            <i class="fas fa-arrow-up text-secondary"></i>
                                                        </a>

                                                        <a role="button" data-bs-toggle="modal"
                                                            data-bs-target="#modalConfirm" data-id="{{ $a->idActivo }}"
                                                            data-bs-tt="tooltip" data-bs-original-title="Eliminar"
                                                            class="btnEliminar">
                                                            <i class="fas fa-trash text-secondary"></i>
                                                        </a>
                                                    @endif
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
                <div class="row mx-1">
                    <a href="{{ url('/activos/categorias') }}"
                        class="btn bg-white text text-transform-none border-radius-lg">
                        <i class="fas fa-tags me-2"></i>
                        <span style="text-transform: none">Gestión de categorías</span>
                    </a>
                </div>

                <div class="row mx-1 dropdown">
                    <a href=""
                        class="btn bg-gradient-dark dropdown-toggle text text-transform-none border-radius-lg"
                        data-bs-toggle="dropdown" id="navbarDropdownMenuLink2">
                        <i class="fas fa-file-contract me-2"></i>
                        <span style="text-transform: none">Informes de depreciación</span>
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
                            <h6 class="mb-0">Activos por Sucursal</h6>
                        </div>
                    </div>
                    <div class="card-body p-3 pb-0">
                        <ul class="list-group">
                            @foreach ($datosSucursales as $resultado)
                                <li
                                    class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                    <div class="d-flex align-items-center">
                                        <button
                                            class="btn btn-icon-only btn-rounded btn-outline-success mb-0 me-3 btn-sm d-flex align-items-center justify-content-center">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </button>
                                        <div class="d-flex flex-column">
                                            <h6 class="mb-1 text-dark font-weight-bold text-sm">{{ $resultado->nombre }}
                                            </h6>
                                            <span class="text-xs">Valor distribuido:</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center text-sm font-weight-bold">
                                        $ {{ number_format($resultado->total, 2) }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @include('activos.modales')
@endsection
