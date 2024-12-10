<div class="modal fade" id="modalFormE" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-mg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0 ">
                <div class="card card-plain">
                    <div class="card-header pb-0 text-left">
                        <h3 class="text-dark" id="titulo"></h3>
                        <p class="mb-0">(*) Campos Obligatorios</p>
                    </div>
                    <div class="card-body">
                        <form role="form text-left" id="empresaForm" method="POST">
                            @csrf
                            <input type="hidden" name="_method" id="method" value="PUT"> <!-- Para edición -->
                            <div class="row mb-3">
                                <!-- Contenedor Padre para Centrar -->
                                <div class="d-flex justify-content-center align-items-center">
                                    <!-- Imagen -->
                                    <div class="col-xl-8">
                                        <input type="hidden"
                                            value="{{ isset($activo) ? old('imageTemp', $activo->imagen) : old('imagenTemp') }}"
                                            id="imagenTemp" name="imagenTemp">
                                        <label id="image-preview"
                                            class="custum-file-upload d-flex justify-content-center align-items-center"
                                            style="margin-top:15px; width: 250px; height: 250px;
                                            {{ isset($activo)
                                                ? 'background-image: url(' . asset(old('imagenTemp', $activo->imagen)) . ')'
                                                : 'background-image: url(' . old('imagenTemp') . ')' }}"
                                            for="logo" data-bs-pp="tooltip" data-bs-placement="left"
                                            title="Subir imagen">
                                            <div class="icon" id="iconContainer"
                                                style="color:#c4c4c4; font-size: 32px">
                                                <i style="height: 55px; padding: 10px" class="fas fa-camera"></i>
                                            </div>
                                            <div class="text">
                                                <span>Subir imagen</span>
                                            </div>
                                            <input type="file" name="logo" id="logo"
                                                accept="image/jpeg,image/png">
                                        </label>
                                        @error('logo')
                                            <span class="text-danger" style="line-height: 0.05px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>


                                <label>NIT: *</label>
                                <div class="input-group mb-1">
                                    <input type="text" name="nit" id="nit" class="form-control"
                                        placeholder="NIT" autocomplete="off" oninput="validarInputNit(this)">
                                </div>
                                <span id="error-nit" class="text-danger text-xs mb-3"></span>

                                <label>Nombre: *</label>
                                <div class="input-group mb-1">
                                    <input type="text" name="nombre" id="nombre" class="form-control"
                                        placeholder="Nombre" autocomplete="off">
                                </div>
                                <span id="error-nombre" class="text-danger text-xs mb-3"></span>
                            </div>

                            <div class="text-end">
                                <button type="reset" data-bs-dismiss="modal" style="border-color:transparent"
                                    class="btn btn-outline-dark btn-sm mt-4 mb-0">
                                    <i class="fas fa-undo text-xs"></i>&nbsp;&nbsp;Cancelar</button>
                                <button type="submit" class="btn btn-icon bg-gradient-success btn-sm mt-4 mb-0">
                                    <i class="fas fa-check text-xs"></i>&nbsp;&nbsp;Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEConfirm" tabindex="-1" role="dialog" aria-labelledby="modal-form"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-mg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0 ">
                <div class="card card-plain">
                    <div class="card-header pb-0 text-left">
                        <h3 class="text-dark">Confirmar operación</h3>
                        <p id="dialogo" class="mb-0">Está a punto de deshabilitar el registro. ¿Desea continuar?</p>
                    </div>
                    <div class="card-body">
                        <form role="form text-left" id="confirmarForm">
                            @csrf
                            <input type="hidden" id="methodE">
                            <div class="text-end">
                                <button type="reset" data-bs-dismiss="modal" style="border-color:transparent"
                                    class="btn btn-outline-dark btn-sm mt-4 mb-0">
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
