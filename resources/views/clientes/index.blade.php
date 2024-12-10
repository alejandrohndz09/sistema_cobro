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
    <script src="{{ asset('js/validaciones/jsCliente.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection
@section('content')
    <div class="container-fluid ">
        <div class="row">
            <div class="col-lg-12">
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
                                    <div class="row mx-3 dropdown">
                                        <a href=""
                                            class="btn bg-gradient-dark dropdown-toggle text text-transform-none border-radius-lg "
                                            data-bs-toggle="dropdown" id="navbarDropdownMenuLink2">
                                            <i class="fas fa-plus me-2"></i>
                                            <span style="text-transform: none"> Agregar</span>
                                        </a>
                                        <ul class="dropdown-menu w-100" aria-labelledby="navbarDropdownMenuLink2">
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    id="btnNatural" data-bs-target="#modalFormNatural">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-person me-2"></i>
                                                        Natural
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    id="btnJuridico" data-bs-target="#modalFormJuridico">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-building me-2"></i>
                                                        Jurídico
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>

                                </div>
                            </div>

                            <div class="collapsenull" id="collapseExample">
                                <div class="row mt-3">
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
                                    <div class="col-4">
                                        <label>Tipo de cartera:</label>
                                        <div class="nav-wrapper position-relative end-0">
                                            <ul class="nav nav-pills nav-fill p-1 bg-gray-100" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link mb-0 px-0 py-1 active"data-bs-toggle="tab"
                                                        data-sort="v-todos" role="tab" aria-selected="true">
                                                        <i class="ni ni-bullet-list-67 text-sm me-2"></i> Todos
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab"
                                                        data-sort="v-A" role="tab" aria-selected="false">
                                                        <i class="fa fa-star text-sm me-2"></i> A
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab"
                                                        data-sort="v-B" role="tab" aria-selected="false">
                                                        <i class="fas fa-star-half-alt text-sm me-2"></i> B
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab"
                                                        data-sort="v-C" role="tab" aria-selected="false">
                                                        <i class="far fa-star text-sm me-2"></i> C
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab"
                                                        data-sort="v-D" role="tab" aria-selected="false">
                                                        <i class="fas fa-close text-sm me-2"></i> D
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
                                                Cliente
                                            </th>
                                            <th style="text-align: left !important;"
                                                class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 dt-type-">
                                                Direccion
                                            </th>
                                            <th
                                                class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Teléfono
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
                                        @foreach ($clientes as $c)
                                            <tr class="tr-link" data-id="{{ $c->id }}">
                                                <td>
                                                    <div
                                                        class="avatar avatar-sm icon bg-gradient-info shadow text-center border-radius-lg">
                                                        <i class="fas fa-arrow-alt-circle-up opacity-10 text-sm"></i>
                                                    </div>
                                                </td>
                                                <td class="px-1">
                                                    <p class="text-xs font-bold mb-0">{{ $c->id }}</p>
                                                </td>
                                                <td class="px-1">
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        <i
                                                            class="fas fa-{{ str_starts_with($c->id, 'CN') ? 'person' : 'building' }} text-xxs"></i>&nbsp;
                                                        {{ $c->nombre }}
                                                    </p>

                                                    <p class="text-xxs mb-0">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;{{ str_starts_with($c->id, 'CN') ? 'Cliente Natural' : 'Cliente Jurídico' }}
                                                    </p>
                                                </td>
                                                <td class="px-1">
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ $c->direccion }}
                                                    </p>
                                                </td>
                                                <td class="px-1">
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ $c->telefono }}
                                                    </p>
                                                </td>
                                                <td class="px-1 text-xs">
                                                    <span
                                                        class="badge badge-xs opacity-7 bg-{{ $c->estado == 1 ? 'success' : 'secondary' }} ">
                                                        {{ $c->estado == 1 ? 'activa' : 'inactiva' }}</span>
                                                </td>
                                                <td>
                                                    @if ($c->estado == 1)
                                                        <a role="button" data-bs-toggle="modal"
                                                            data-bs-target="#modalForm" data-id="{{ $c->id }}"
                                                            data-bs-tt="tooltip" data-bs-original-title="Editar"
                                                            class="btnEditar me-2">
                                                            <i class="fas fa-pen text-secondary"></i>
                                                        </a>

                                                        <a role="button" data-bs-toggle="modal"
                                                            data-bs-target="#modalConfirm" data-id="{{ $c->id }}"
                                                            data-bs-tt="tooltip" data-bs-original-title="Deshabilitar"
                                                            class="btnDeshabilitar">
                                                            <i class="fas fa-minus-circle text-secondary"></i>
                                                        </a>
                                                    @else
                                                        <a role="button" data-id="{{ $c->id }}"
                                                            data-bs-tt="tooltip" data-bs-original-title="Habilitar"
                                                            class="btnHabilitar me-2">
                                                            <i class="fas fa-arrow-up text-secondary"></i>
                                                        </a>

                                                        <a role="button" data-bs-toggle="modal"
                                                            data-bs-target="#modalConfirm" data-id="{{ $c->id }}"
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
        </div>
    </div>
    @include('clientes.modales')
@endsection
