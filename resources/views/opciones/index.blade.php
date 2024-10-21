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
                            <a href="/opciones/empresa" class="stretched-link"></a>
                            <div class="card-header mx-4 p-3 text-center">
                                <div class="icon icon-shape icon-lg bg-gradient-dark shadow text-center border-radius-lg">
                                    <i class="fas fa-building opacity-10"></i>
                                </div>
                            </div>
                            <div class="card-body pt-0 p-3 text-center">
                                <h4 class="text-center mb-0">Empresa</h4>
                                <hr class="horizontal dark my-1">
                                <i class="fas fa-circle-info text-xs"></i>
                                <span class="text-xs">Gestión de la información de la empresa, sucursales y departamentos.</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-3 mb-4">
                        <div class="card card-link">
                            <a href="/opciones/empleados" class="stretched-link"></a>
                            <div class="card-header mx-4 p-3 text-center">
                                <div class="icon icon-shape icon-lg bg-gradient-info shadow text-center border-radius-lg">
                                    <i class="fas fa-users opacity-10"></i>  
                                </div>
                            </div>
                            <div class="card-body pt-0 p-3 text-center">
                                <h4 class="text-center mb-0">Empleados</h4>
                                <hr class="horizontal dark my-1">
                                <i class="fas fa-circle-info text-xs"></i>
                                <span class="text-xs">Gestión de empleados por sucursal y departamento.</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-3 mb-4">
                        <div class="card card-link">
                            <a href="/opciones/usuarios" class="stretched-link"></a>
                            <div class="card-header mx-4 p-3 text-center">
                                <div class="icon icon-shape icon-lg bg-gradient-success shadow text-center border-radius-lg">
                                    <i class="fas fa-users-gear opacity-10"></i>
                                </div>
                            </div>
                            <div class="card-body pt-0 p-3 text-center">
                                <h4 class="text-center mb-0">Usuarios</h4>
                                <hr class="horizontal dark my-1">
                                <i class="fas fa-circle-info text-xs"></i>
                                <span class="text-xs">Gestión de usuarios y roles de acceso.</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-3 mb-4">
                        <div class="card card-link">
                            <a href="/opciones/otros" class="stretched-link"></a>
                            <div class="card-header mx-4 p-3 text-center">
                                <div class="icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg">
                                    <i class="fas fa-sliders opacity-10"></i>
                                </div>
                            </div>
                            <div class="card-body pt-0 p-3 text-center">
                                <h4 class="text-center mb-0">Otros</h4>
                                <hr class="horizontal dark my-1">
                                <i class="fas fa-circle-info text-xs"></i>
                                <span class="text-xs">Gestión de parámetros usados en el sistema.</span>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    @include('activos.modales')
@endsection
