@extends('layouts.user_type.auth')
@section('styles')
    <link rel="stylesheet" href="<?php echo asset('css/extras.css'); ?>" type="text/css">
@endsection
@section('scripts')
    {{-- <script src="{{ asset('js/tablas.js') }}"></script>
    <script src="{{ asset('js/validaciones/jsActivo.js') }}"></script> --}}
@endsection
@section('content')
    <div class="container-fluid ">
        <div class="row ">
            <div class="col-lg-12">
                <div class="row ">
                  <div class="col-md-6 col-sm-3 mb-4">
                  <div class="card card-link">
                      <a href="/gestión-comercial" class="stretched-link"></a>
                      <div class="card-header mx-4 p-3 text-center">
                          <div
                              class="icon icon-shape icon-lg bg-gradient-dark shadow text-center border-radius-lg">
                              <i class="fas fa-money-bill-alt opacity-10"></i>
                          </div>
                      </div>
                      <div class="card-body pt-0 p-3 text-center">
                          <h4 class="text-center mb-0">Gestión Comercial</h4>
                          <hr class="horizontal dark my-1">
                          <i class="fas fa-circle-info text-xs"></i>
                          <span class="text-xs">
                            Administra las ventas, productos y clientes de la empresa.</span>
                      </div>
                  </div>
              </div>
              <div class="col-md-6 col-sm-3 mb-4">
                  <div class="card card-link">
                      <a href="/activos" class="stretched-link"></a>
                      <div class="card-header mx-4 p-3 text-center">
                          <div
                              class="icon icon-shape icon-lg bg-gradient-success shadow text-center border-radius-lg">
                              <i class="fas fa-cubes opacity-10"></i>
                          </div>
                      </div>
                      <div class="card-body pt-0 p-3 text-center">
                          <h4 class="text-center mb-0">Gestión de Activo Fijo</h4>
                          <hr class="horizontal dark my-1">
                          <i class="fas fa-circle-info text-xs"></i>
                          <span class="text-xs">Controla, registra y realiza el seguimiento de los activos fijos de la empresa, incluyendo su depreciación.</span>
                      </div>
                  </div>
              </div>
                </div>
            </div>
        </div>
    </div>
@endsection
