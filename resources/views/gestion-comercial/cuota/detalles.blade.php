@extends('layouts.user_type.auth')
@section('styles')
    <link rel="stylesheet" href="<?php echo asset('css/extras.css'); ?>" type="text/css">
    <link id="pagestyle" href="{{ asset('assets/css/soft-ui-dashboard.css?v=1.0.3') }}" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
@section('scripts')
    <script src="{{ asset('js/tablas.js') }}"></script>
    <script src="{{ asset('js/validaciones/jsCuota.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                                            <div class="col-lg-4">

                                                <div
                                                    class="bg-gradient-dark border-radius-lg h-100 d-flex p-5 justify-content-center">
                                                    @if ($clienteTipo === 'natural')
                                                        <i class="fas fa-person opacity-10 text-xl text-white"
                                                            style="font-size: 10rem"></i>
                                                    @elseif($clienteTipo === 'juridico')
                                                        <i class="fas fa-building opacity-10 text-xl text-white"
                                                            style="font-size: 10rem"></i>
                                                    @else
                                                        <i class="fas fa-question opacity-10 text-xl text-white"
                                                            style="font-size: 10rem"></i>
                                                    @endif
                                                </div>


                                            </div>
                                            <div class="col-lg-6 ms-3 mt-5 mt-lg-0">

                                                <div class="d-flex flex-column h-100">
                                                    <label class="text-xl mb-0 ms-0">Cliente:</label>
                                                    <p class="mb-2 mt-0 "><i class="fas fa-user text-xs"></i>
                                                        &nbsp;{{ $ventaData->cliente }}
                                                    </p>
                                                    <label class="text-xl mb-0 ms-0">Código:</label>
                                                    <p class="mb-2 mt-0 "><i class="fas fa-hashtag text-xs"></i>
                                                        &nbsp;{{ $ventaData->idVenta }}
                                                    </p>
                                                    <label class="text-xl mb-0 ms-0">Teléfono:</label>
                                                    <p class="mb-2 mt-0 "><i class="fas fa-phone text-xs"></i>
                                                        &nbsp;{{ $ventaData->telefono }}
                                                    </p>
                                                    <label class="text-xl mb-0 ms-0">Dirección:</label>
                                                    <p class="mb-2 mt-0 "><i class="fas fa-location-arrow text-xs"></i>
                                                        &nbsp;{{ $ventaData->direccion }}
                                                    </p>
                                                    <label class="text-xl mb-0 ms-0">Total venta:</label>
                                                    <p class="mb-2 mt-0 "><i class="fas fa-money-bill text-xs"></i>
                                                        &nbsp;{{ $ventaData->total }}
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

                <div class="col-lg-12">
                    <div class="col-md-12 mb-lg-0 mb-4">
                        <div class="card">
                            <div class="card-header pb-0 p-3">
                                <div class="row">
                                    <div class="col-6 d-flex align-items-center">
                                        <h4 class="mb-0">Cuotas de la Venta</h4>
                                    </div>

                                    <div class="d-flex justify-content-end mb-3">
                                        <!-- Botón para generar cuotas automáticamente -->
                                        <button id="btnGenerarCuotas" class="btn btn-dark mb-3">Generar Cuotas</button>

                                    </div>


                                </div>
                            </div>
                            <div class="card-body p-3">
                                <div class="table-responsive">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Fecha Pago</th>
                                                <th>Fecha Límite</th>
                                                <th>Monto</th>
                                                <th>Mora</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableBody">
                                            <!-- La tabla estará vacía hasta que se generen cuotas -->
                                        </tbody>
                                    </table>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        @include('gestion-comercial.cuota.modal')

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                actualizarEstadosYMostrarCuotas(); // Actualiza estados y muestra cuotas
            });
        </script>
    @endsection

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const idVenta = "{{ $ventaData->idVenta }}"; // El ID de la venta disponible en la vista
            mostrarDatos(); // Carga las cuotas para esta venta
        });
    </script>
