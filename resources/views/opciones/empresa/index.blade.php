@extends('layouts.user_type.auth')
@section('styles')
    <link rel="stylesheet" href="<?php echo asset('css/extras.css'); ?>" type="text/css">
@endsection
@section('scripts')
    <script src="{{ asset('js/tablas.js') }}"></script>
    <script src="{{ asset('js/validaciones/jsEmpresa.js') }}"></script>
    <script src="{{ asset('js/validaciones/jsSucursal.js') }}"></script>
@endsection
@section('content')
    <div class="container-fluid ">
        <div class="row">
            <div class="col-lg-12">

                <div class="col-lg-12 mb-4">
                    <div class="card border-0 shadow">
                        <div class="row g-0 align-items-center">

                            <!-- Imagen -->
                            <div class="col-md-4">
                                <div class="p-4 text-center">
                                    @foreach ($empresas as $empresa)
                                        <label id="image-previewI-{{ $empresa->idEmpresa }}"
                                            class="custum-file-upload position-relative d-inline-block overflow-hidden rounded mb-0"
                                            style="width: 72%; height: 200px;
                                            background-size: cover; background-position: center; background-repeat: no-repeat;
                                            background-image: url('{{ asset($empresa->logo) }}');"
                                            data-bs-toggle="tooltip" data-bs-placement="left" title="Imagen de la Empresa">
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Datos de la Empresa -->
                            <div class="col-md-8">
                                <div class="card-body">
                                    @foreach ($empresas as $empresa)
                                        <div class="mb-4 pb-2 border-bottom" id="card-{{ $empresa->idEmpresa }}">
                                            <h4 class="card-title mb-1" id="empresa-nombre-{{ $empresa->idEmpresa }}">
                                                {{ $empresa->nombre }}</h4>
                                            <p class="text-muted mb-2">ID Empresa: <strong
                                                    id="empresa-id-{{ $empresa->idEmpresa }}">{{ $empresa->idEmpresa }}</strong>
                                            </p>
                                            <p class="text-muted mb-2">NIT: <strong
                                                    id="empresa-nit-{{ $empresa->idEmpresa }}">{{ $empresa->nit }}</strong>
                                            </p>
                                            <!-- Botón de editar -->
                                            @if ($empresa->estado == 1)
                                                <a role="button" data-bs-toggle="modal" data-bs-target="#modalFormE"
                                                    data-id="{{ $empresa->idEmpresa }}"
                                                    class="btnEditarE position-absolute top-0 end-0 mt-3 me-3">
                                                    <i class="fas fa-pen text-secondary"></i>
                                                </a>
                                            @endif
                                        </div>
                                    @endforeach
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
                                    <h6 class="mb-0">Registros Sucursales</h6>
                                </div>
                                <div class="col-6 d-flex align-items-end justify-content-end">
                                    <div class="input-group" style="width: 60%">
                                        <span class="input-group-text text-body"><i class="fas fa-search"
                                                aria-hidden="true"></i></span>
                                        <input type="text" id="searchInput" class="form-control" placeholder="Buscar...">
                                    </div>
                                    <div class="text-end ms-2">
                                        <a id="btnAgregar" class="btn bg-gradient-dark mb-0" href="javascript:;"
                                            data-bs-toggle="modal" data-bs-target="#modalFormS">
                                            <i class="fas fa-plus"></i>&nbsp;&nbsp;Agregar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">

                                    <thead>
                                        <th style="width: 9%">
                                        </th>
                                        <th
                                            class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            ID SUCURSAL
                                        </th>
                                        <th
                                            class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            TELÉFONO
                                        </th>
                                        <th
                                            class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            DIRECCIÓN
                                        </th>
                                        <th
                                            class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            UBICACIÓN
                                        </th>
                                        <th
                                            class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Estado
                                        </th>
                                        <th
                                            class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Action
                                        </th>

                                    </thead>
                                    <tbody id="tableBody">
                                        @foreach ($sucursales as $sucursal)
                                            <tr class="tr-link" data-id="{{ $sucursal->idSucursal }}">
                                                <td style="width: 9%">
                                                    <div
                                                        class="avatar avatar-sm icon bg-gradient-info shadow text-center border-radius-lg">
                                                        <i class="fas fa-tag opacity-10 text-sm"></i>
                                                    </div>
                                                </td>
                                                <td class="px-1">
                                                    <p class="text-xs font-weight-bold mb-0">{{ $sucursal->idSucursal }}
                                                    </p>
                                                </td>
                                                <td class="px-1">
                                                    <p class="text-xs font-weight-bold mb-0">{{ $sucursal->telefono }}</p>
                                                </td>
                                                <td class="px-1">
                                                    <p class="text-xs font-weight-bold mb-0">{{ $sucursal->direccion }}</p>
                                                </td>
                                                <td class="px-1">
                                                    <p class="text-xs font-weight-bold mb-0">{{ $sucursal->ubicacion }}
                                                    </p>
                                                </td>
                                                <td class="px-1 text-sm">
                                                    <span
                                                        class="badge badge-xs opacity-7 bg-{{ $sucursal->estado == 1 ? 'success' : 'secondary' }} ">
                                                        {{ $sucursal->estado == 1 ? 'activo' : 'inactivo' }}</span>
                                                </td>
                                                <td>
                                                    @if ($sucursal->estado == 1)
                                                        <a role="button" data-bs-toggle="modal"
                                                            data-bs-target="#modalFormS"
                                                            data-id="{{ $sucursal->idSucursal }}" data-bs-tt="tooltip"
                                                            data-bs-original-title="Editar" class="btnEditar me-3">
                                                            <i class="fas fa-pen text-secondary"></i>
                                                        </a>

                                                        <a role="button" data-bs-toggle="modal"
                                                            data-bs-target="#modalConfirm"
                                                            data-id="{{ $sucursal->idSucursal }}" data-bs-tt="tooltip"
                                                            data-bs-original-title="Deshabilitar"
                                                            class="btnDeshabilitar me-3">
                                                            <i class="fas fa-minus-circle text-secondary"></i>
                                                        </a>
                                                    @else
                                                        <a role="button" data-id="{{ $sucursal->idSucursal }}"
                                                            data-bs-tt="tooltip" data-bs-original-title="Habilitar"
                                                            class="btnHabilitar me-3">
                                                            <i class="fas fa-arrow-up text-secondary"></i>
                                                        </a>

                                                        <a role="button" data-bs-toggle="modal"
                                                            data-bs-target="#modalConfirm"
                                                            data-id="{{ $sucursal->idSucursal }}" data-bs-tt="tooltip"
                                                            data-bs-original-title="Eliminar" class="btnEliminar me-3">
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
        </div>
        @include('opciones.empresa.modales')
        @include('opciones.empresa.modalesEmpresa')
    @endsection
