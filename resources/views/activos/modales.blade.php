<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0 ">
                <div class="card card-plain">
                    <div class="card-header pb-0 text-left">
                        <h3 class="font-weight-bolder text-info text-gradient">
                            {{ isset($activo) ? 'Editar Registro' : 'Nuevo Registro' }}
                        </h3>
                        <p class="mb-0">(*) Campos Obligatorios</p>
                    </div>
                    <div class="card-body">
                        <form role="form text-left"
                            action="{{ isset($activo) ? url('activo/update/' . $activo->idActivo) : '' }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @if (isset($activo))
                                @method('PUT')
                            @endif
                            <div class="row mb-4">
                                <div class="col-xl-4">
                                    <input type="hidden"
                                        value="{{ isset($activo) ? old('imageTemp', $activo->imagen) : old('imagenTemp') }}"
                                        id="imagenTemp" name="imagenTemp">
                                    <label id="image-preview" class="custum-file-upload"
                                        style="margin-top:25px; width: auto; height: 75%;
                                    {{ isset($activo)
                                        ? 'background-image: url(' . asset(old('imagenTemp', $activo->imagen)) . ')'
                                        : 'background-image: url(' . old('imagenTemp') . ')' }}"
                                        for="foto" data-bs-pp="tooltip" data-bs-placement="left"
                                        title="Subir imagen">
                                        <div class="icon" id="iconContainer" style="color:#c4c4c4; font-size: 32px">
                                            <i style="height: 55px; padding: 10px" class="fas fa-camera"></i>
                                        </div>
                                        <div class="text">
                                            <span>Subir imagen</span>
                                        </div>
                                        <input type="file" name="foto" id="foto"
                                            accept="image/jpeg,image/png">
                                    </label>
                                    @error('foto')
                                        <span class="text-danger" style="line-height: 0.05px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-xl-4">
                                    <label>Nombre: *</label>
                                    <div class="input-group mb-3">
                                        <input type="text" name="nombre" id="nombre" class="form-control"
                                            placeholder="Nombre"
                                            value="{{ isset($activo) ? old('nombre', $activo->nombre) : old('nombre') }}"
                                            autocomplete="off">
                                    </div>
                                    @error('nombre')
                                        <span class="text-danger text-xs">{{ $message }} <br></span>
                                    @enderror


                                    <label>Categoría: *</label>
                                    <select id="categoria" name="categoria" class="form-control">
                                        <option value=""
                                            {{ old('categoria') == '' && isset($activo) == null ? 'selected' : '' }}>
                                            Seleccione...
                                        </option>
                                        {{-- @php use App\Models\Especie; @endphp
                                        @foreach (Especie::all() as $e)
                                            <option value="{{ $e->idEspecie }}"
                                                {{ isset($activo) ? ($activo->raza->idEspecie == $e->idEspecie ? 'selected' : '') : (old('categoria') == $e->idEspecie ? 'selected' : '') }}>
                                                {{ $e->categoria }}
                                            </option>
                                        @endforeach --}}
                                    </select>

                                    @error('categoria')
                                        <span class="text-danger text-xs">{{ $message }} <br></span>
                                    @enderror
                                </div>
                                <div class="col-xl-4">
                                    <label>Descripción:</label>
                                    <textarea id="descripcion" name="descripcion" class="form-control" placeholder="Ej. Marca, modelo, capacidades, etc."
                                        rows="5" cols="50">{{ isset($activo) ? old('descripcion', $activo->descripcion) : old('descripcion') }}</textarea>
                                    @error('descripcion')
                                        <span class="text-danger text-xs">{{ $message }} <br></span>
                                    @enderror
                                </div>
                            </div>
                            <hr class="my-3">
                            <div class="row">
                                <div class="col-xl-4">
                                    <label>Sucursal: *</label>
                                    <select id="sucursal" name="sucursal" class="form-control">
                                        <option value=""
                                            {{ old('sucursal') == '' && isset($activo) == null ? 'selected' : '' }}>
                                            Seleccione...
                                        </option>
                                        {{-- @php use App\Models\Especie; @endphp
                                        @foreach (Especie::all() as $e)
                                            <option value="{{ $e->idEspecie }}"
                                                {{ isset($activo) ? ($activo->raza->idEspecie == $e->idEspecie ? 'selected' : '') : (old('categoria') == $e->idEspecie ? 'selected' : '') }}>
                                                {{ $e->categoria }}
                                            </option>
                                        @endforeach --}}
                                    </select>

                                    @error('sucursal')
                                        <span class="text-danger text-xs">{{ $message }} <br></span>
                                    @enderror
                                </div>
                                <div class="col-xl-4">
                                    <label>Precio de Adquisición ($): *</label>
                                    <div class="input-group mb-3">
                                        <input type="number" name="precioAdquisiscion" id="precioAdquisiscion"
                                            class="form-control" min="1" step="0.01" placeholder="0.00"
                                            value="{{ isset($activo) ? old('precioAdquisiscion', $activo->precioAdquisiscion) : old('precioAdquisiscion', '0.00') }}"
                                            autocomplete="off">
                                    </div>
                                    @error('precioAdquisiscion')
                                        <span class="text-danger text-xs">{{ $message }} <br></span>
                                    @enderror
                                </div>
                                <div class="col-xl-4">
                                    <label>Fecha de Adquisición: *</label>
                                    <div class="input-group mb-3">
                                        <input type="date" name="fechaAdquisicion" id="fechaAdquisicion"
                                            class="form-control" placeholder="Nombre"
                                            value="{{ isset($activo) ? old('fechaAdquisicion', $activo->precioAdquisiscion) : old('fechaAdquisicion') }}"
                                            autocomplete="off">
                                    </div>
                                    @error('fechaAdquisicion')
                                        <span class="text-danger text-xs">{{ $message }} <br></span>
                                    @enderror
                                </div>

                                <div class="table-responsive px-5">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <th class="text-secondary text-xxs font-weight-bolder opacity-7">
                                                Código
                                            </th>
                                            <th class="text-secondary text-xxs font-weight-bolder opacity-7">
                                                Departamento
                                            </th>
                                            <th class="text-secondary text-xxs font-weight-bolder opacity-7">
                                                Cantidad a asignar
                                            </th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableBody">
                                            <tr>
                                                <td class="ps-4">
                                                    <p class="text-xs font-weight-bold mb-0">DP0001</p>
                                                </td>

                                                <td class="ps-4">
                                                    <p class="text-xs font-weight-bold mb-0">Ventas</p>
                                                </td>
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center justify-content-center">
                                                        <input type="text" class="form-control">
                                                    </div>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td class="ps-4">
                                                    <p class="text-xs font-weight-bold mb-0">DP0002</p>
                                                </td>

                                                <td class="ps-4">
                                                    <p class="text-xs font-weight-bold mb-0">Marketing</p>
                                                </td>
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center justify-content-center">
                                                        <input type="text" class="form-control">
                                                    </div>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td class="ps-4">
                                                    <p class="text-xs font-weight-bold mb-0">DP0003</p>
                                                </td>

                                                <td class="ps-4">
                                                    <p class="text-xs font-weight-bold mb-0">Administrativo</p>
                                                </td>
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center justify-content-center">
                                                        <input type="text" class="form-control">
                                                    </div>
                                                </td>

                                            </tr>
                                        </tbody>
                                    </table>
                                    <div id="pagination" class="d-flex justify-content-center mt-2"></div>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="button" class="btn  bg-gradient-info btn-lg w-100 mt-4 mb-0">Sign
                                    in</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal para ingresar datos para generar el PDF -->
<div class="modal fade" id="PDFmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Datos para PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <form id="pdfForm">
                        <!-- Removemos el action ya que lo haremos mediante AJAX -->
                        <div class="row">
                            <div class="col-12">
                                <label for="empresa">Empresa: </label>
                                <select name="empresa" id="empresa" class="form-control">
                                    <option value="">Seleccione empresa</option>
                                    @foreach ($empresas as $e)
                                        <option value="{{ $e->idEmpresa }}">{{ $e->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 mt-3">
                                <label for="sucursal">Sucursal: </label>
                                <select name="sucursal" id="sucursal" class="form-control">
                                    <option value="">Seleccione sucursal</option>
                                    @foreach ($sucursales as $sucursal)
                                        <option value="{{ $sucursal->idSucursal }}">{{ $sucursal->ubicacion }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 mt-3">
                                <label for="departamento">Departamento: </label>
                                <select name="departamento" id="departamento" class="form-control">
                                    <option value="">Seleccione departamento</option>
                                    @foreach ($departamentos as $depto)
                                        <option value="{{ $depto->idDepartamento }}">{{ $depto->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 mt-3">
                                <label for="activo">Activo: </label>
                                <select name="activo" id="activo" class="form-control">
                                    <option value="">Seleccione activo</option>
                                    @foreach ($activos as $a)
                                        <option value="{{ $a->idActivo }}">{{ $a->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" name="tipo" id="tipo" value="">
                            <!-- Campo oculto para el tipo de depreciación -->
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-default" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn bg-gradient-primary" onclick="submitForm()">Generar PDF</button>
            </div>
        </div>
    </div>
</div>
