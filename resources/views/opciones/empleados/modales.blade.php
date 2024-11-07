<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-mg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0 ">
                <div class="card card-plain">
                    <div class="card-header pb-0 text-left">
                        <h3 class="text-dark" id="titulo"></h3>
                        <p class="mb-0">(*) Campos Obligatorios</p>
                    </div>
                    <div class="card-body">
                        <form role="form text-left" id="empleadoForm">
                            @csrf
                            <input type="hidden" name="_method" id="method" value="POST"> <!-- Para edición -->
                            <div class="row mb-4">
                                <label>DUI: *</label>
                                <div class="input-group mb-1">
                                    <input type="text" name="dui" id="dui" class="form-control"
                                        placeholder="00000000-0" autocomplete="off" 
                                        maxlength="10" title="El formato debe contener 9 dígitos">
                                </div>
                                <span id="error-dui" class="text-danger text-xs mb-3"></span>

                                <label>Nombre: *</label>
                                <div class="input-group mb-1">
                                    <input type="text" name="nombres" id="nombres" class="form-control"
                                        placeholder="Nombre" autocomplete="off">
                                </div>
                                <span id="error-nombres" class="text-danger text-xs mb-3"></span>

                                <label>Apellidos: *</label>
                                <div class="input-group mb-1">
                                    <input type="text" name="apellidos" id="apellidos" class="form-control"
                                        placeholder="Apellidos" autocomplete="off">
                                </div>
                                <span id="error-apellidos" class="text-danger text-xs mb-3"></span>

                                <div class="input-group mb-1">
                                    <div class="col-md-5">
                                        <label for="cargo">Cargo: *</label>
                                        <div class="input-group mb-1">
                                            <input type="text" name="cargo" id="cargo" class="form-control"
                                                placeholder="Cargo" autocomplete="off">
                                        </div>
                                        <span id="error-cargo" class="text-danger text-xs mb-3"></span>
                                    </div>
                                    <div class="col-md-6 ms-auto">
                                        <label for="departamento">Departamento: *</label>
                                        <div class="input-group mb-1">
                                            <select name="idDepartamento" id="idDepartamento" class="form-control">
                                                <option value="">Seleccione</option>
                                            </select>
                                        </div>
                                        <span id="error-departamento"
                                            class="sp-departamento text-danger text-xs mb-3"></span>
                                    </div>
                                </div>

                            </div>

                            <div class="text-end">
                                <button type="reset" data-bs-dismiss="modal" style="border-color:transparent"
                                    class="btn btn-outline-dark btn-sm mt-1 mb-0">
                                    <i class="fas fa-undo text-xs"></i>&nbsp;&nbsp;Cancelar</button>
                                <button type="submit" class="btn btn-icon bg-gradient-success btn-sm mt-1 mb-0">
                                    <i class="fas fa-check text-xs"></i>&nbsp;&nbsp;Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalConfirm" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-mg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0 ">
                <div class="card card-plain">
                    <div class="card-header pb-0 text-left">
                        <h3 class="text-dark">Confirmar operación</h3>
                        <p id="dialogo" class="mb-0">Está a punto de deshabilitar el registro. ¿Desea continuar?
                        </p>
                    </div>
                    <div class="card-body">
                        <form role="form text-left" id="confirmarForm">
                            @csrf
                            <input type="hidden" id="methodC">
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

<script>
    document.getElementById('dui').addEventListener('input', function(e) {
        let dui = e.target.value.replace(/\D/g, ''); // Eliminar caracteres que no sean dígitos
        if (dui.length > 8) {
            dui = dui.slice(0, 8) + '-' + dui.slice(8, 9); // Insertar guion después del octavo dígito
        }
        e.target.value = dui; // Actualizar el valor del campo
    });
</script>
