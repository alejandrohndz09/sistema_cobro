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
            <div class="col-lg-12">
                <div class="row ">

                    <div class="col-xl-6 mb-xl-0 mb-4">
                        <div class="card card-link">
                            <a href="/gestión-comercial/ventas" class="stretched-link"></a>
                            <div class="card-header mx-4 p-3 text-center">
                                <div class="icon icon-shape icon-lg bg-gradient-danger shadow text-center border-radius-lg">
                                    <i class="fas fa-chart-line opacity-10"></i>
                                </div>
                            </div>
                            <div class="card-body pt-0 p-3 text-center">
                                <h4 class="text-center mb-0">Ventas</h4>
                                <hr class="horizontal dark my-1">
                                <i class="fas fa-circle-info text-xs"></i>
                                <span class="text-xs">Gestión de ventas al crédito y contado.</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-3 mb-4">
                        <div class="card card-link">
                            <a href="/gestión-comercial/cuotas" class="stretched-link"></a>
                            <div class="card-header mx-4 p-3 text-center">
                                <div
                                    class="icon icon-shape icon-lg bg-gradient-success shadow text-center border-radius-lg">
                                    <i class="fas fa-money-bill-wave opacity-10"></i>
                                </div>
                            </div>
                            <div class="card-body pt-0 p-3 text-center">
                                <h4 class="text-center mb-0">Pagos</h4>
                                <hr class="horizontal dark my-1">
                                <i class="fas fa-circle-info text-xs"></i>
                                <span class="text-xs">Gestión de pagos de cuotas.</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-3 mb-4">
                        <div class="card card-link">
                            <a href="/gestión-comercial/clientes" class="stretched-link"></a>
                            <div class="card-header mx-4 p-3 text-center">
                                <div class="icon icon-shape icon-lg bg-gradient-info shadow text-center border-radius-lg">
                                    <i class="fas fa-users opacity-10"></i>
                                </div>
                            </div>
                            <div class="card-body pt-0 p-3 text-center">
                                <h4 class="text-center mb-0">Clientes</h4>
                                <hr class="horizontal dark my-1">
                                <i class="fas fa-circle-info text-xs"></i>
                                <span class="text-xs">Gestión de cartera de clientes.</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-3 mb-4">
                        <div class="card card-link">
                            <a href="/gestión-comercial/inventario" class="stretched-link"></a>
                            <div class="card-header mx-4 p-3 d-flex justify-content-center">
                                <div class="icon icon-shape icon-lg bg-gradient-warning shadow text-center d-flex justify-content-center align-items-center border-radius-lg">
                                    <svg width="25px" height="25px" viewBox="0 0 42 42" version="1.1"
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <title>box-3d-50</title>
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g transform="translate(-2319.000000, -291.000000)" fill="#FFFFFF"
                                                fill-rule="nonzero">
                                                <g transform="translate(1716.000000, 291.000000)">
                                                    <g transform="translate(603.000000, 0.000000)">
                                                        <path class="color-background"
                                                            d="M22.7597136,19.3090182 L38.8987031,11.2395234 C39.3926816,10.9925342 39.592906,10.3918611 39.3459167,9.89788265 C39.249157,9.70436312 39.0922432,9.5474453 38.8987261,9.45068056 L20.2741875,0.1378125 L20.2741875,0.1378125 C19.905375,-0.04725 19.469625,-0.04725 19.0995,0.1378125 L3.1011696,8.13815822 C2.60720568,8.38517662 2.40701679,8.98586148 2.6540352,9.4798254 C2.75080129,9.67332903 2.90771305,9.83023153 3.10122239,9.9269862 L21.8652864,19.3090182 C22.1468139,19.4497819 22.4781861,19.4497819 22.7597136,19.3090182 Z">
                                                        </path>
                                                        <path class="color-background opacity-6"
                                                            d="M23.625,22.429159 L23.625,39.8805372 C23.625,40.4328219 24.0727153,40.8805372 24.625,40.8805372 C24.7802551,40.8805372 24.9333778,40.8443874 25.0722402,40.7749511 L41.2741875,32.673375 L41.2741875,32.673375 C41.719125,32.4515625 42,31.9974375 42,31.5 L42,14.241659 C42,13.6893742 41.5522847,13.241659 41,13.241659 C40.8447549,13.241659 40.6916418,13.2778041 40.5527864,13.3472318 L24.1777864,21.5347318 C23.8390024,21.7041238 23.625,22.0503869 23.625,22.429159 Z">
                                                        </path>
                                                        <path class="color-background opacity-6"
                                                            d="M20.4472136,21.5347318 L1.4472136,12.0347318 C0.953235098,11.7877425 0.352562058,11.9879669 0.105572809,12.4819454 C0.0361450918,12.6208008 6.47121774e-16,12.7739139 0,12.929159 L0,30.1875 L0,30.1875 C0,30.6849375 0.280875,31.1390625 0.7258125,31.3621875 L19.5528096,40.7750766 C20.0467945,41.0220531 20.6474623,40.8218132 20.8944388,40.3278283 C20.963859,40.1889789 21,40.0358742 21,39.8806379 L21,22.429159 C21,22.0503869 20.7859976,21.7041238 20.4472136,21.5347318 Z">
                                                        </path>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </div>
                            </div>
                            <div class="card-body pt-0 p-3 text-center">
                                <h4 class="text-center mb-0">Inventario</h4>
                                <hr class="horizontal dark my-1">
                                <i class="fas fa-circle-info text-xs"></i>
                                <span class="text-xs">Gestión detallada de la entrada y salida de los productos.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
