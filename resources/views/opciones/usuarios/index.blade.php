@extends('layouts.user_type.auth')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/extras.css') }}" type="text/css">
@endsection
@section('scripts')
    <script src="{{ asset('js/tablas.js') }}"></script>
    <script src="{{ asset('js/validaciones/jsUsuario.js') }}"></script>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="col-md-12 mb-lg-0 mb-4">
                    <div class="card">
                        <div class="card-header pb-0 p-3">
                            <div class="row">
                                <div class="col-6 d-flex align-items-center">
                                    <h4 class="mb-0">Usuarios</h4>
                                </div>
                                <div class="col-6 d-flex align-items-end justify-content-end">
                                    <button id="btnAgregar" class="btn bg-gradient-dark mb-0" data-bs-toggle="modal" data-bs-target="#modalForm">
                                        <i class="fas fa-plus"></i>&nbsp;&nbsp;Agregar
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID Usuario</th>
                                            <th class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Usuario</th>
                                            <th class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Correo</th>
                                            <th class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Contraseña</th>
                                            <th class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Estado</th>
                                            <th class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Empleado</th>
                                            <th class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableBody">
                                        @foreach ($usuarios as $u)
                                            <tr>
                                                <td class="px-1">
                                                    <p class="text-xs font-weight-bold mb-0">{{ $u->idusuario }}</p>
                                                </td>
                                                <td class="px-1">
                                                    <p class="text-xs font-weight-bold mb-0">{{ $u->usuario }}</p>
                                                </td>
                                                <td class="px-1">
                                                    <p class="text-xs font-weight-bold mb-0">{{ $u->email }}</p>
                                                </td>
                                                <td class="px-1">
                                                    <p class="text-xs font-weight-bold mb-0">{{ $u->clave }}</p>
                                                </td>
                                                <td class="px-1 text-sm">
                                                    <span class="badge badge-xs opacity-7 bg-{{ $u->estado == 1 ? 'success' : 'secondary' }}">
                                                        {{ $u->estado == 1 ? 'Activo' : 'Inactivo' }}
                                                    </span>
                                                </td>
                                                <td class="px-1">
                                                 <p class="text-xs font-weight-bold mb-0">{{ $u->nombres ?? 'Sin asignar' }}</p>
                                                </td>


                                                <td>
                                                    @if ($u->estado == 1)
                                                        <a role="button" data-bs-toggle="modal" data-bs-target="#modalForm"
                                                            data-id="{{ $u->idusuario }}" data-bs-tt="tooltip"
                                                            data-bs-original-title="Editar" class="btnEditar me-3">
                                                            <i class="fas fa-pen text-secondary"></i>
                                                        </a>

                                                        <a role="button" data-bs-toggle="modal"
                                                            data-bs-target="#modalConfirm" data-id="{{ $u->idusuario }}"
                                                            data-bs-tt="tooltip" data-bs-original-title="Deshabilitar"
                                                            class="btnDeshabilitar me-3">
                                                            <i class="fas fa-minus-circle text-secondary"></i>
                                                        </a>
                                                    @else 
                                                        <a role="button" data-id="{{ $u->idusuario }}"
                                                            data-bs-tt="tooltip" data-bs-original-title="Habilitar"
                                                            class="btnHabilitar me-3">
                                                            <i class="fas fa-arrow-up text-secondary"></i>
                                                        </a>

                                                        <a role="button" data-bs-toggle="modal"
                                                            data-bs-target="#modalConfirm" data-id="{{ $u->idusuario }}"
                                                            data-bs-tt="tooltip"
                                                            data-bs-original-title="Eliminar"
                                                            class="btnEliminar me-3">
                                                            <i class="fas fa-trash text-secondary"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-conten-center mt-2" id="pagination"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('opciones.usuarios.modales')
@endsection
