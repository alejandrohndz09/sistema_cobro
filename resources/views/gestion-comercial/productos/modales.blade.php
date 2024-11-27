<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0 ">
                <div class="card card-plain">
                    <div class="card-header pb-0 text-left">
                        <h3 class="text-dark" id="titulo"></h3>
                        <p class="mb-0">(*) Campos Obligatorios</p>
                    </div>
                    <div class="card-body">
                        <form role="form text-left" enctype="multipart/form-data" id="ProductoForm">
                            @csrf
                            <input type="hidden" name="_method" id="method" value="POST"> <!-- Para edición -->
                            <div class="row mb-4">
                                <div class="col-4">
                                    <input type="hidden" id="imagenTemp" name="imagenTemp">
                                    <label id="image-preview" class="custum-file-upload mb-1" data-bs-tt="tooltip"
                                        data-bs-original-title="Click para subir imagen" data-bs-placement="bottom"
                                        style="margin-top:25px; width: auto; height: 90%;">
                                        <div class="icon" id="iconContainer" style="color:#c4c4c4; font-size: 32px">
                                            <i style="height: 55px; padding: 10px" class="fas fa-camera"></i>
                                        </div>
                                        <div id="textImage" class="text">
                                            <span>Subir imagen</span>
                                        </div>
                                        <input type="file" name="imagen" id="imagen"
                                            accept="image/jpeg,image/png,image/jpg">
                                    </label>
                                    <span id="error-imagen" class="text-danger text-xs mb-3"></span>
                                </div>
                                <div class="col-8">
                                    <label>Nombre: *</label>
                                    <div class="input-group mb-1">
                                        <input type="text" name="nombre" id="nombre" class="form-control"
                                            placeholder="Nombre" autocomplete="off">
                                    </div>
                                    <span id="error-nombre" class="text-danger text-xs mb-3"></span>

                                    <br>
                                    <label>Descripción: *</label>
                                    <div class="input-group mb-1">
                                        <textarea id="descripcion" name="descripcion" class="form-control"
                                            placeholder="Ej. caracteristicas, color, tamaño, etc." rows="5" cols="50"></textarea>
                                    </div>
                                    <span id="error-descripcion" class="text-danger text-xs mb-3"></span>

                                </div>
                            </div>
                            <div class="col-12">
                                <label>Stock mínimo: *</label>
                                <div class="input-group mb-1">
                                    <input type="number" min="1" name="stockMinimo" id="stockMinimo"
                                        class="form-control" placeholder="##" autocomplete="off">
                                </div>
                                <span id="error-stockMinimo" class="text-danger text-xs mb-3"></span>
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

<div class="modal fade" id="DetalleKardex" tabindex="-1" role="dialog" aria-labelledby="TitleKardex"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark" id="TitleKardex"></h5>
            </div>
            <div class="modal-body " id="BodyKardex" >
                <!-- Aquí se llenará el contenido dinámico -->
            </div>
        </div>
    </div>
</div>
