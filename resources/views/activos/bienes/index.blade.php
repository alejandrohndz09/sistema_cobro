@extends('layouts.user_type.auth')
@section('styles')
    <link rel="stylesheet" href="<?php echo asset('css/extras.css'); ?>" type="text/css">
@endsection
@section('scripts')
    <script src="{{ asset('js/tablas.js') }}"></script>
    <script src="{{ asset('js/validaciones/jsBien.js') }}"></script>
@endsection
@section('content')
    <div class="container-fluid ">
        <div class="row">
            <div class="col-lg-9">
                <div class="col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-lg-5">
                                    <div class="bg-white border-radius-lg h-100 position-relative overflow-hidden">
                                        <img src="../assets/img/activos/{{ $activo->imagen }}"
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
                                            <span class="text-xs">({{ $vida_util>1?"{$vida_util} años ":"{$vida_util} año " }}de vida útil).</span>
                                        </p>

                                        <p class="mb-1 mt-0 "><i class="fas fa-cubes text-xs"></i>
                                            {{ $activo->bienes->count()>1? "{$activo->bienes->count()} Bienes registrados.":"{$activo->bienes->count()} Bien registrado." }}</p>
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

                <div class="col-md-12 mb-lg-0 mb-4">
                    <div class="card">
                        <div class="card-header pb-0 p-3">
                            <div class="row">
                                <div class="col-6 d-flex align-items-center">
                                    <h4 class="mb-0">Registro de Bienes</h4>
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
                                <table class="table align-items-center justify-content-center  mb-0">
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
                                                Fecha adquis.
                                            </th>
                                            <th
                                                class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                V. adquisición
                                            </th>
                                            <th
                                                class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                V. actual
                                            </th>
                                            <th
                                                class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                depreciación acum.
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
                                        @foreach ($activo->bienes as $b)
                                            <tr class="tr-link" data-id="{{ $b->idBien }}">
                                                <td>
                                                    <div
                                                        class="avatar avatar-sm icon bg-gradient-info shadow text-center border-radius-lg">
                                                        <i class="fas fa-cube opacity-10 text-sm"></i>
                                                    </div>
                                                </td>
                                                <td class="px-1">
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ $b->departamento->idSucursal . '-' . $b->idDepartamento . '-' . $b->idActivo . '-' . $b->idBien }}
                                                    </p>
                                                </td>
                                                <td class="px-1">
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ $b->fechaAdquisicion->format('d/m/y') }}</p>
                                                    <p class="text-xxs  mb-0"></p>
                                                </td>
                                                <td class="px-1">
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ '$' . number_format($b->precio, 2, '.', ',') }}
                                                    </p>
                                                </td>
                                                @php
                                                    $valor_actual = number_format(
                                                        $b->obtenerValorEnLibros()[0] >= 0
                                                            ? $b->obtenerValorEnLibros()[0]
                                                            : 0,
                                                        2,
                                                        '.',
                                                        ',',
                                                    );
                                                @endphp
                                                <td class="px-1">
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ '$' . $valor_actual }}
                                                    </p>
                                                </td>
                                                <td>
                                                    <div class="d-flex-column align-items-center justify-content-center">
                                                        @php
                                                            $v =$b->obtenerValorEnLibros()[0] >= 0? $b->obtenerValorEnLibros()[0]: 0;
                                                            $val = $b->precio > 0 ? ($v / $b->precio) * 100 : 0; // Evita la división por cero
                                                            $señal =
                                                                $val >= 70
                                                                    ? 'success'
                                                                    : ($val > 40
                                                                        ? 'info'
                                                                        : ($val > 15
                                                                            ? 'warning'
                                                                            : 'danger'));
                                                        @endphp
                                                        <span
                                                            class="me-2 text-xs font-weight-bold">{{ number_format(100 - $val, 2, '.', ',') . '%' }}</span>
                                                        <div>
                                                            <div class="progress">
                                                                <div class="progress-bar bg-gradient-{{ $señal }}"
                                                                    role="progressbar" aria-valuenow="{{ 100 - $val }}"
                                                                    aria-valuemin="0" aria-valuemax="100"
                                                                    style="width:{{ 100 - $val }}%;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-1 text-xs">
                                                    <span
                                                        class="badge badge-xs opacity-7 bg-{{ $b->estado == 1 ? 'success' : 'secondary' }} ">
                                                        {{ $b->estado == 1 ? 'activo' : 'inactivo' }}</span>
                                                </td>
                                                <td>
                                                    @if ($b->estado == 1)
                                                        <a role="button" data-bs-toggle="modal" data-bs-target="#modalForm"
                                                            data-id="{{ $b->idBien }}" data-bs-tt="tooltip"
                                                            data-bs-original-title="Editar" class="btnEditar me-2">
                                                            <i class="fas fa-pen text-secondary"></i>
                                                        </a>

                                                        <a role="button" data-bs-toggle="modal"
                                                            data-bs-target="#modalConfirm" data-id="{{ $b->idBien }}"
                                                            data-bs-tt="tooltip" data-bs-original-title="Deshabilitar"
                                                            class="btnDeshabilitar">
                                                            <i class="fas fa-minus-circle text-secondary"></i>
                                                        </a>
                                                    @else
                                                        <a role="button" data-id="{{ $b->idBien }}"
                                                            data-bs-tt="tooltip" data-bs-original-title="Habilitar"
                                                            class="btnHabilitar me-2">
                                                            <i class="fas fa-arrow-up text-secondary"></i>
                                                        </a>

                                                        <a role="button" data-bs-toggle="modal"
                                                            data-bs-target="#modalConfirm" data-id="{{ $b->idBien }}"
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
                <div class="row mx-1 dropdown">
                    <a href=""
                        class="btn bg-gradient-dark dropdown-toggle text text-transform-none border-radius-lg"
                        data-bs-toggle="dropdown" id="navbarDropdownMenuLink2">
                        <i class="fas fa-file-contract me-2"></i>
                        <span style="text-transform: none">Informes de depreciación</span>
                    </a>
                    <ul class="dropdown-menu w-100" aria-labelledby="navbarDropdownMenuLink2">
                        <li>
                            <a class="dropdown-item" href="#">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    Anual
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    Mensual
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
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
    @include('activos.bienes.modales')
@endsection
