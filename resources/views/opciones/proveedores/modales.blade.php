<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-mg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card card-plain">
                    <div class="card-header pb-0 text-left">
                        <h3 class="text-dark" id="titulo">Agregar Proveedor</h3>
                        <p class="mb-0">(*) Campos Obligatorios</p>
                    </div>
                    <div class="card-body">
                        <form id="proveedorForm" method="POST">
                            @csrf
                            <input type="hidden" name="_method" id="method" value="POST">
                            
                            <!-- Campo Nombre -->
                            <label for="nombre">Nombre: *</label>
                            <div class="input-group mb-1">
                                <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre del proveedor" required>
                            </div>
                            <span id="error-nombre" class="text-danger text-xs mb-3"></span>

                            <!-- Campo Dirección -->
                            <label for="direccion">Dirección: *</label>
                            <div class="input-group mb-1">
                                <input type="text" name="direccion" id="direccion" class="form-control" placeholder="Dirección del proveedor" required>
                            </div>
                            <span id="error-direccion" class="text-danger text-xs mb-3"></span>

                            <!-- Campo Teléfono -->
                            <label for="telefono">Teléfono: *</label>
                            <div class="input-group mb-1">
                                <input type="text" name="telefono" id="telefono" class="form-control" placeholder="Teléfono del proveedor" required>
                            </div>
                            <span id="error-telefono" class="text-danger text-xs mb-3"></span>

                            <!-- Campo Correo -->
                            <label for="correo">Correo: *</label>
                            <div class="input-group mb-1">
                                <input type="email" name="correo" id="correo" class="form-control" placeholder="Correo electrónico del proveedor" required>
                            </div>
                            <span id="error-correo" class="text-danger text-xs mb-3"></span>

                            <div class="text-end">
                             <button type="button" data-bs-dismiss="modal" style="border-color:transparent" class="btn btn-outline-dark btn-sm mt-4 mb-0">
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

<div class="modal fade" id="modalConfirm" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
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
