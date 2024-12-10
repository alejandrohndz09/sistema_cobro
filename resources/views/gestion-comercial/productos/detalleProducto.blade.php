@extends('layouts.user_type.auth')
@section('styles')
    <link rel="stylesheet" href="<?php echo asset('assets/css/extras.css'); ?>" type="text/css">
@endsection
@section('scripts')
    {{-- <script src="{{ asset('js/tablas.js') }}"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script> --}}
    <script src="{{ asset('js/validaciones/jsProducto.js') }}"></script>
@endsection
@section('content')
    <div class="container-fluid ">
        <div class="row">
            <div class="col-lg-9 mb-4">
                <div class="card h-100">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="bg-white border-radius-lg h-100 position-relative overflow-hidden">
                                    <img src="{{ asset('assets/img/productos/' . $producto->imagen) }}"
                                        style="width: 100%; height: 100%; object-fit: cover;"
                                        class="position-absolute top-0 start-0">
                                </div>

                            </div>
                            <div class="col-lg-7 ms-auto mt-5 mt-lg-0">
                                <div class="d-flex flex-column h-100">
                                    <h5 class="font-weight-bolder mb-1">{{ $producto->nombre }}</h5>
                                    <p class="mb-0 mt-0 text-bold"><i class="fas fa-tag text-xs"></i>
                                        Stock mínimo: {{ $producto->stockMinimo }}
                                    </p>
                                    <p class="mb-1 mt-0 text-sm"><i class="fas fa-info-circle text-xs"></i> Descripción:
                                    </p>
                                    <div class=" text-xs" style="font-size: 10px !important;">
                                        {!! \Parsedown::instance()->text($producto->descripcion) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 mb-4">
                <div class="card">
                    <div class="card-header mx-4 p-3 text-center">
                        <div class="icon icon-shape icon-lg bg-gradient-info shadow text-center border-radius-lg">
                            <i class="fas fa-box opacity-10"></i>
                        </div>
                    </div>
                    <div class="card-body pt-2 p-3 text-center">
                        <h6 class="text-center mb-0">Existencias</h6>
                        <span class="text-xs">Stock del producto</span>
                        <hr class="horizontal dark my-2">
                        <h3 class="mb-0">{{ $producto->StockTotal }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-12 mb-lg-0 mb-4">
                <div class="card">
                    <div class="card-header pb-0 p-3">
                        <div class="row">
                            <div class="col-6 d-flex align-items-center">
                                <h4 class="mb-0">Registro de movimientos</h4>
                            </div>
                            <div class="col-6 d-flex align-items-end justify-content-end">
                                <div class="input-group" style="width: 60%">
                                    <span class="input-group-text text-body"><i class="fas fa-search"
                                            aria-hidden="true"></i></span>
                                    <input type="text" id="searchInput" class="form-control" placeholder="Buscar...">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th rowspan="2"></th>
                                        <th class="align-text-botton text-uppercase text-secondary text-xxs font-weight-bolder col-1"
                                            rowspan="2">
                                            Fecha</th>
                                        <th class="text-uppercase text-secondary text-xxs text-center font-weight-bolder  col-3"
                                            rowspan="2">
                                            Detalle</th>
                                        <th class="text-uppercase text-secondary text-xxs text-center font-weight-bolder col-4 text-dark"
                                            colspan="3" style="border-right: 1px solid #e9ecef !important;"> <i
                                                class="fas fa-arrow-down text-xxs"></i> &nbsp; Entradas (Compras)</th>
                                        <th class="text-uppercase text-secondary text-xxs text-center font-weight-bolder col-4 text-dark"
                                            colspan="3"> <i class="fas fa-arrow-up text-xxs"></i> &nbsp; Salidas (Ventas)
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Cantidad
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Valor
                                            Unitario</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                            style="border-right: 1px solid #e9ecef !important;">
                                            Valor
                                            Total
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Cantidad
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Valor
                                            Unitario</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Valor
                                            Total
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    @foreach ($kardex as $registro)
                                        <tr class="text-font-weight-bold  text-center text-secondary text-xs producto-row"
                                            data-producto="{{ json_encode($registro) }}">

                                            {{-- Si es una entrada, llenamos las columnas de entrada --}}
                                            @if ($registro->Movimiento == 'Entrada')
                                                <td>
                                                    <div
                                                        class="avatar avatar-sm icon bg-gradient-success shadow text-center border-radius-lg">
                                                        <i class="fas fa-arrow-alt-circle-down opacity-10 text-sm"></i>
                                                    </div>
                                                </td>

                                                <td>{{ \Carbon\Carbon::parse($registro->Fecha)->format('d/m/Y') }}</td>

                                                {{-- Aqui va el detalle  --}}
                                                <td></td>

                                                <td>{{ $registro->Cantidad }}</td>
                                                <td>$ {{ $registro->ValorUnitario }}</td>
                                                <td style="border-right: 1px solid #e9ecef !important;">$
                                                    {{ $registro->ValorTotal }}</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>

                                                {{-- Si es una salida, llenamos las columnas de salida --}}
                                            @elseif ($registro->Movimiento == 'Salida')
                                                <td>
                                                    <div
                                                        class="avatar avatar-sm icon bg-gradient-danger shadow text-center border-radius-lg">
                                                        <i class="fas fa-arrow-alt-circle-up opacity-10 text-sm"></i>
                                                    </div>
                                                </td>

                                                <td>{{ \Carbon\Carbon::parse($registro->Fecha)->format('d/m/Y') }}</td>

                                                {{-- Aqui va el detalle  --}}
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td style="border-left: 1px solid #e9ecef !important;">
                                                    {{ $registro->Cantidad }}</td>
                                                <td>$ {{ $registro->ValorUnitario }}</td>
                                                <td>$ {{ $registro->ValorTotal }}</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('gestion-comercial.productos.modales')
@endsection
