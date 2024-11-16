<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>
        Cuentas Claras System
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link rel="stylesheet"
        href="{{ url('https://cdn.jsdelivr.net/npm/sweetalert2@10.3.5/dist/sweetalert2.min.css') }}" />
    <!-- Nucleo Icons -->
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    {{-- <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script> --}}
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="../assets/css/soft-ui-dashboard.css?v=1.0.3" rel="stylesheet" />
</head>

<body class="bg-gradient-dark"style="padding: 3%;">

    <div class="row" style="height:80vh;justify-content: center; align-content: center">



        <div class="col-xl-4">
            <div class="card bg-white">
                <div class="card-header pb-0 text-left">
                    <div class="d-flex align-items-center justify-content-end text-dark font-weight-bold">
                        <img src="../assets/img/logo-ct.png" height="30px;" style="padding-bottom: 3px">
                        <h2 class="ms-1 text-sm">Cuentas Claras System</h2>
                    </div>
                    <hr>
                    <h3 class="text-dark text-gradient mb-0" id="titulo">Bienvenido.</h3>
                    <p class="text-sm mb-0">Por favor, ingrese sus credenciales.</p>
                </div>
                <div class="card-body">
                    <form role="form text-left" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="method" value="POST"> <!-- Para edición -->
                        <div class="row mb-4">
                            <label>Usuario:</label>
                            <div class="input-group mb-1">
                                <input type="text" name="usuario" value="{{ old('usuario') }}" id="usuario" class="form-control"
                                    placeholder="Usuario" autocomplete="off">
                            </div>
                            @error('usuario')
                                <span class="text-danger  text-xs mb-3">{{ $message }}</span>
                            @enderror


                            <label>Contraseña:</label>
                            <div class="input-group mb-1">
                                <input type="password" name="contraseña" id="contraseña" class="form-control"
                                    placeholder="Contraseña" autocomplete="off">
                            </div>
                            @error('contraseña')
                                <span class="text-danger  text-xs mb-3">{{ $message }}</span>
                            @enderror
                            <div class="d-flex flex-grow-1 " style="margin-left:5px; gap:3px">
                                <input type="checkbox" id="mostrarClave">
                                <span class="text-sm">Mostrar contraseña</span>
                            </div>


                        </div>
                        <button type="submit" class="btn btn-icon bg-gradient-dark mt-3 mb-0 w-100">Iniciar
                            sesión</button>
                        {{-- <div class="d-flex mt-3 justify-content-center">
                            <p style="text-align: end; margin-bottom: 0;"><a class=""
                                    style="text-decoration: none; " data-bs-toggle="modal" data-bs-target="#recuperar"
                                    href="">¿Olvidó su
                                    contraseña?</a>
                            </p>
                        </div> --}}

                    </form>
                </div>
            </div>
        </div>
    </div>
    <div style="text-align: center">
        <p>©️2024, Sistema final - Análisis Financiero</p>
    </div>
    <div class="modal fade" id="recuperar" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <form action="/recuperarClaveMail" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 style="margin-left: auto; margin-right: auto;">Recuperar clave</h5>
                    </div>

                    <div class="modal-body text-center px-5">
                        <div
                            style="margin: 0; display: flex;flex-direction: column; align-items: center; justify-content: center ">

                            <div class="inputContainer mt-4 mb-2">
                                <input type="email" id="correo" name="correo"
                                    placeholder="ejemplo@email.com"class="inputField">
                                <label class="inputFieldLabel" for="raza">Ingrese un correo electrónico asociado al
                                    miembro:</label>
                                <i class="inputFieldIcon fas fa-user"></i>

                            </div>
                            <div
                                style="margin: 0; display: flex; align-items: center;width:auto; color:#867596; font-size: 14px ">
                                <i class="fas fa-circle-info" style="margin-right: 3px;"></i>
                                Se enviará un código de seguridad al correo indicado.
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer" style="display:flex; justify-content: center; gap:40px">
                        <button id="confirmar" type="submit" class="button button-pri">
                            <i class="svg-icon fas fa-check"></i>
                            <span class="lable">Confirmar</span></button>
                        <button type="button" class="button button-red" data-bs-dismiss="modal"> <i
                                class="svg-icon fas fa-xmark"></i>
                            <span class="lable">Cancelar</span> </button>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="{{ url('https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js') }}"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
    </script>

    <script src="{{ url('https://cdn.jsdelivr.net/npm/sweetalert2@10.3.5/dist/sweetalert2.min.js') }}"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            // iconColor: 'white',
        });
    </script>
    @if (session('alert'))
        <script>
            Toast.fire({
                icon: "{{ session('alert')['type'] }}",
                title: "{{ session('alert')['message'] }}",
            });
        </script>
        @php
            session()->forget('alert');
        @endphp
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var checkbox = document.getElementById('mostrarClave');
            var claveInput = document.getElementById('contraseña');

            checkbox.addEventListener('change', function() {
                if (checkbox.checked) {
                    // Si el checkbox está marcado, cambia el tipo del input a 'text'
                    claveInput.type = 'text';
                } else {
                    // Si el checkbox está desmarcado, vuelve a establecer el tipo del input a 'password'
                    claveInput.type = 'password';
                }
            });
        });
    </script>
</body>

</html>
