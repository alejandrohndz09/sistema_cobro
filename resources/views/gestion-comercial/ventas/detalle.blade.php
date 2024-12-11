@extends('layouts.user_type.auth')
@section('styles')
    <link rel="stylesheet" href="<?php echo asset('css/extras.css'); ?>" type="text/css">
    <link id="pagestyle" href="{{ asset('assets/css/soft-ui-dashboard.css?v=1.0.3') }}" rel="stylesheet" />
@endsection
@section('scripts')
    <script src="{{ asset('js/tablas.js') }}"></script>
    <script src="{{ asset('js/validaciones/jsDetalleVenta.js') }}"></script>
@endsection
@section('content')
    <div class="container-fluid ">
        <div class="row">
            <div class="col-lg-12">

                <div class="col-lg-12 mb-4">
                    <div class="card border-0 shadow">
                        <div class="row g-0 align-items-center">

                            <div class="col-md-12 mb-4">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="row">
                                                    <h3 class="font-weight-bolder mb-2">Datos generales de la venta
                                                    </h3>
                                                    <div class="col-6">
                                                        <div class="col-lg-6 ms-3 mt-5 mt-lg-0">

                                                            <label class="text-xl mb-0 ms-0">Nombre de cliente:</label>
                                                            <p class="mb-2 mt-0"><i
                                                                    class="fas fa-user text-xs"></i>&nbsp;{{ $datosCliente['nombre'] }}
                                                            </p>

                                                            <label class="text-xl mb-0 ms-0">Teléfono:</label>
                                                            <p class="mb-2 mt-0"><i
                                                                    class="fas fa-phone text-xs"></i>&nbsp;{{ $datosCliente['telefono'] }}
                                                            </p>

                                                            <label class="text-xl mb-0 ms-0">Dirección:</label>
                                                            <p class="mb-2 mt-0"><i
                                                                    class="fas fa-location-arrow text-xs"></i>&nbsp;{{ $datosCliente['direccion'] }}
                                                            </p>

                                                            <label class="text-xl mb-0 ms-0">Tipo:</label>
                                                            <p class="mb-2 mt-0"><i
                                                                    class="fas fa-quote-left text-xs"></i>&nbsp;{{ $datosCliente['tipo'] }}
                                                            </p>

                                                            <label class="text-xl mb-0 ms-0">Fecha:</label>
                                                            <p class="mb-2 mt-0"><i
                                                                    class="fas fa-calendar text-xs"></i>&nbsp;{{ $venta->fecha }}
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="col-6">

                                                        <label class="text-xl mb-0 ms-0">Saldo capital:</label>
                                                        <p class="mb-2 mt-0"><i
                                                                class="fas fa-money-bill text-xs"></i>&nbsp;${{ $venta->SaldoCapital }}
                                                        </p>

                                                        <label class="text-xl mb-0 ms-0">Iva:</label>
                                                        <p class="mb-2 mt-0"><i
                                                                class="fas fa-coins text-xs"></i>&nbsp;{{ $venta->iva }}
                                                        </p>

                                                        <label class="text-xl mb-0 ms-0">Venta:</label>
                                                        <p class="mb-2 mt-0"><i class="fas fa-money-bill text-xs"></i>&nbsp;
                                                            $ {{ $venta->total }}
                                                        </p>

                                                        @if ($venta->tipo === 0)
                                                            <label class="text-xl mb-0 ms-0">Meses:</label>
                                                            <p class="mb-2 mt-0"><i
                                                                    class="fas fa-calendar text-xs"></i>&nbsp;{{ $venta->meses }}
                                                            </p>
                                                        @endif

                                                        <label class="text-xl mb-0 ms-0">Realizado por:</label>
                                                        <p class="mb-2 mt-0"><i
                                                                class="fas fa-handshake text-xs"></i>&nbsp;{{ $venta->empleado->nombres }}
                                                            {{ $venta->empleado->apellidos }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="col-md-12 mb-lg-0 mb-4">
                    <div class="card">
                        <div class="card-header pb-0 p-3">
                            <div class="row">
                                <div class="col-6 d-flex align-items-center">
                                    <h4 class="mb-0">Detalles de venta</h4>
                                </div>
                                <div class="col-6 d-flex align-items-end justify-content-end">
                                    <div class="input-group" style="width: 60%">
                                        <span class="input-group-text text-body"><i class="fas fa-search"
                                                aria-hidden="true"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 9%">
                                            </th>

                                            <th
                                                class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                ID
                                            </th>

                                            <th
                                                class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                nombre producto
                                            </th>

                                            <th
                                                class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Cantidad
                                            </th>
                                            <th
                                                class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                SubTotal
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableBody">
                                        @foreach ($venta->detalle_venta as $c)
                                            <tr class="tr-link" data-id="{{ $c->idDetalleVenta }}">
                                                <td style="width: 9%">
                                                    <div
                                                        class="avatar avatar-sm icon bg-gradient-info shadow text-center border-radius-lg">
                                                        <i class="fas fa-tag opacity-10 text-sm"></i>
                                                    </div>
                                                </td>
                                                <td class="px-1">
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ $c->idDetalleVenta }}
                                                    </p>
                                                </td>
                                                <td class="px-1">
                                                    <p class="text-xs font-weight-bold mb-0">{{ $c->producto->nombre }}</p>
                                                </td>

                                                <td class="px-1">
                                                    <p class="text-xs font-weight-bold mb-0">{{ $c->cantidad }}</p>
                                                </td>
                                                <td class="px-1">
                                                    <p class="text-xs font-weight-bold mb-0">$ {{ $c->subtotal }}</p>
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

    <!-- Modal para mostrar los detalles del producto -->
    <div class="modal fade" id="productoModal" tabindex="-1" aria-labelledby="productoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productoModalLabel">Detalles del Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><br><img id="productoImagen" src="" alt="Imagen del Producto"
                            style="max-width: 100%; height: auto;"></p>
                    <p><strong>Nombre:</strong> <span id="productoNombre"></span></p>
                    <p><strong>Descripción:</strong> <span id="productoDescripcion"></span></p>
                    <p><strong>Cantidad:</strong> <span id="productoCantidad"></span></p>
                    <p><strong>Subtotal:</strong> <span id="productoSubtotal"></span></p>

                </div>
            </div>
        </div>
    </div>
@endsection
