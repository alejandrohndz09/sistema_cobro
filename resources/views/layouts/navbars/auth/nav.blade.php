<!-- Navbar -->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
    navbar-scroll="true">
    <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <!-- Primer nivel: página principal -->
                <li class="breadcrumb-item text-sm">
                    <a class="opacity-5 text-dark" href="{{ url('/') }}">Inicio</a>
                </li>

                <!-- Generar los demás niveles dinámicamente -->
                @php
                    $segments = Request::segments(); // Obtener segmentos de la URL
                    $url = ''; // Para construir las URLs de los enlaces del breadcrumb
                @endphp

                @foreach ($segments as $index => $segment)
                    @php
                        // Construir la URL acumulando los segmentos
                        $url .= '/' . $segment;
                    @endphp

                    @if ($index + 1 < count($segments))
                        {{-- Si no es el último segmento --}}
                        <li class="breadcrumb-item text-sm">
                            <a class="opacity-5 text-dark" href="{{ url($url) }}">
                                {{ ucwords(str_replace('-', ' ', $segment)) }} {{-- Reemplazar '-' por espacios --}}
                            </a>
                        </li>
                    @else
                        {{-- Último segmento (el actual, sin enlace) --}}
                        <li class="breadcrumb-item text-sm text-dark active text-capitalize" aria-current="page">
                            {{ ucwords(str_replace('-', ' ', $segment)) }}
                        </li>
                    @endif
                @endforeach
            </ol>

            <!-- Mostrar el título de la página basado en el último segmento con un botón de retroceso -->
            <div class="d-flex align-items-center">
                @if (!Request::is('inicio'))
                    @php
                        // Construir la URL del penúltimo segmento
                        $previousUrl = url('/' . implode('/', array_slice($segments, 0, -1)));
                    @endphp
                     <a href="{{ $previousUrl }}" class="btn btn-icon-only btn-rounded bg-transparent btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left"></i> 
                    </a>
                @endif
                <h4 class="font-weight-bolder  text-capitalize">
                    {{ ucwords(str_replace('-', ' ', end($segments))) }}
                </h4>
            </div>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4 d-flex justify-content-end" id="navbar">
            <ul class="navbar-nav  justify-content-end">
                <li class="nav-item d-flex align-items-center">
                    <a  href="javascript:;"  data-bs-toggle="modal" data-bs-target="#modalLogout"data-bs-toggle=""="nav-link text-body font-weight-bold px-0">
                        <i class="fa fa-user me-sm-1"></i>
                        <span class="d-sm-inline d-none">Cerrar sesión</span>
                    </a>
                    <div class="modal fade" id="modalLogout" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-mg" role="document">
                            <div class="modal-content">
                                <div class="modal-body p-0 ">
                                    <div class="card card-plain">
                                        <div class="card-header pb-0 text-left">
                                            <h3 class="text-dark">Confirmar operación</h3>
                                            <p id="dialogo" class="mb-0">Está a punto de cerrar sesión. ¿Desea continuar?</p>
                                        </div>
                                        <div class="card-body">
                                            <form role="form text-left" action="{{url('/logout')}}" method="POST">
                                                @csrf
                                                <input type="hidden" id="methodC">
                                               <div class="text-end">
                                                    <button type="reset" data-bs-dismiss="modal"
                                                        style="border-color:transparent" class="btn btn-outline-dark btn-sm mt-4 mb-0">
                                                        <i class="fas fa-times text-xs"></i>&nbsp;&nbsp;No</button>
                                                    <button type="submit" class="btn btn-icon bg-gradient-danger btn-sm mt-4 mb-0">
                                                        <i class="fas fa-check text-xs"></i>&nbsp;&nbsp;Sí</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </a>
                </li>
                <li class="nav-item px-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0">
                        <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
                    </a>
                </li>
                <li class="nav-item dropdown pe-2 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bell cursor-pointer"></i>
                    </a>
                    <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4"
                        aria-labelledby="dropdownMenuButton">
                        <li class="mb-2">
                            <a class="dropdown-item border-radius-md" href="javascript:;">
                                <div class="d-flex py-1">
                                    <div class="my-auto">
                                        <img src="../assets/img/team-2.jpg" class="avatar avatar-sm  me-3 ">
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            <span class="font-weight-bold">New message</span> from Laur
                                        </h6>
                                        <p class="text-xs text-secondary mb-0">
                                            <i class="fa fa-clock me-1"></i>
                                            13 minutes ago
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a class="dropdown-item border-radius-md" href="javascript:;">
                                <div class="d-flex py-1">
                                    <div class="my-auto">
                                        <img src="../assets/img/small-logos/logo-spotify.svg"
                                            class="avatar avatar-sm bg-gradient-dark  me-3 ">
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            <span class="font-weight-bold">New album</span> by Travis Scott
                                        </h6>
                                        <p class="text-xs text-secondary mb-0">
                                            <i class="fa fa-clock me-1"></i>
                                            1 day
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item border-radius-md" href="javascript:;">
                                <div class="d-flex py-1">
                                    <div class="avatar avatar-sm bg-gradient-secondary  me-3  my-auto">
                                        <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1"
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <title>credit-card</title>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF"
                                                    fill-rule="nonzero">
                                                    <g transform="translate(1716.000000, 291.000000)">
                                                        <g transform="translate(453.000000, 454.000000)">
                                                            <path class="color-background"
                                                                d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z"
                                                                opacity="0.593633743"></path>
                                                            <path class="color-background"
                                                                d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z">
                                                            </path>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            Payment successfully completed
                                        </h6>
                                        <p class="text-xs text-secondary mb-0">
                                            <i class="fa fa-clock me-1"></i>
                                            2 days
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->
