<div class="modal fade" id="modalFormNatural" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card card-plain">
                    <div class="card-header pb-0 text-left">
                        <h3 class="text-dark" id="titulo"></h3>
                        <p class="mb-0">(*) Campos Obligatorios</p>
                    </div>
                    <div class="card-body">
                        <form role="form text-left" enctype="multipart/form-data" id="clienteFormNatural">
                            @csrf
                            <input type="hidden" name="_method" id="method" value="POST">
                            <input type="hidden" name="tipoNatural" id="tipoNatural" value="">
                            <div class="row mb-3">
                                <!-- DUI, Nombres, Apellidos -->
                                <div class="col-xl-4">
                                    <label>DUI: *</label>
                                    <div class="input-group mb-1">
                                        <input type="text" name="dui" id="dui" class="form-control"
                                            placeholder="DUI" autocomplete="off" oninput="validarDui(this)">
                                    </div>
                                    <span id="error-dui" class="text-danger text-xs mb-3"></span>
                                </div>
                                <div class="col-xl-4">
                                    <label>Nombres: *</label>
                                    <div class="input-group mb-1">
                                        <input type="text" name="nombres" id="nombres" class="form-control"
                                            placeholder="Nombres" autocomplete="off">
                                    </div>
                                    <span id="error-nombres" class="text-danger text-xs mb-3"></span>
                                </div>
                                <div class="col-xl-4">
                                    <label>Apellidos: *</label>
                                    <div class="input-group mb-1">
                                        <input type="text" name="apellidos" id="apellidos" class="form-control"
                                            placeholder="Apellidos" autocomplete="off">
                                    </div>
                                    <span id="error-apellidos" class="text-danger text-xs mb-3"></span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <!-- Teléfono, Ingresos, Egresos -->
                                <div class="col-xl-4">
                                    <label>Teléfono: *</label>
                                    <div class="input-group mb-1">
                                        <input type="text" name="telefono" id="telefono" class="form-control"
                                            oninput="validarInput(this)" placeholder="Teléfono" autocomplete="off">
                                    </div>
                                    <span id="error-telefono" class="text-danger text-xs mb-3"></span>
                                </div>
                                <div class="col-xl-4">
                                    <label>Ingresos: *</label>
                                    <div class="input-group mb-1">
                                        <input type="number" name="ingresos" id="ingresos" class="form-control"
                                            step="0.01" placeholder="Ingresos" autocomplete="off">
                                    </div>
                                    <span id="error-ingresos" class="text-danger text-xs mb-3"></span>
                                </div>
                                <div class="col-xl-4">
                                    <label>Egresos: *</label>
                                    <div class="input-group mb-1">
                                        <input type="number" name="egresos" id="egresos" class="form-control"
                                            step="0.01" placeholder="Egresos" autocomplete="off">
                                    </div>
                                    <span id="error-egresos" class="text-danger text-xs mb-3"></span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <!-- Lugar de trabajo, Dirección -->
                                <div class="col-xl-6">
                                    <label>Lugar de trabajo: *</label>
                                    <div class="input-group mb-1">
                                        <input type="text" name="lugarTrabajo" id="lugarTrabajo"
                                            class="form-control" placeholder="Lugar de trabajo" autocomplete="off">
                                    </div>
                                    <span id="error-lugarTrabajo" class="text-danger text-xs mb-3"></span>
                                </div>
                                <div class="col-xl-6">
                                    <label>Dirección: *</label>
                                    <div class="input-group mb-1">
                                        <input type="text" name="direccion" id="direccion" class="form-control"
                                            placeholder="Dirección" autocomplete="off">
                                    </div>
                                    <span id="error-direccion" class="text-danger text-xs mb-3"></span>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="reset" data-bs-dismiss="modal" style="border-color:transparent"
                                    class="btn btn-outline-dark btn-sm mt-4 mb-0">
                                    <i class="fas fa-undo text-xs"></i>&nbsp;&nbsp;Cancelar
                                </button>
                                <button type="submit" class="btn btn-icon bg-gradient-success btn-sm mt-4 mb-0">
                                    <i class="fas fa-check text-xs"></i>&nbsp;&nbsp;Guardar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalFormJuridico" tabindex="-1" role="dialog" aria-labelledby="modal-form"
    aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full-height modal-lg"
        role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card card-plain">
                    <div class="card-header pb-0 text-left">
                        <h3 class="text-dark" id="titulo2"></h3>
                        <p class="mb-0">(*) Campos Obligatorios</p>
                    </div>
                    <div class="card-body">
                        <form role="form text-left" enctype="multipart/form-data" id="clienteFormJuridico">
                            @csrf
                            <input type="hidden" name="_method" id="method2" value="POST">
                            <input type="hidden" name="tipoJuridico" id="tipoJuridico" value="">
                            <div class="row mb-3">
                                <!-- NIT, nombre de la empresa, direccion, telefono -->
                                <div class="col-xl-4">
                                    <label>NIT: *</label>
                                    <div class="input-group mb-1">
                                        <input type="text" name="nit" id="nit" class="form-control"
                                            placeholder="0000-000000-000-0" autocomplete="off" oninput="validarInputNit(this)">
                                    </div>
                                    <span id="error-nit" class="text-danger text-xs mb-3"></span>
                                </div>
                                <div class="col-xl-4">
                                    <label>Nombre de la empresa: *</label>
                                    <div class="input-group mb-1">
                                        <input type="text" name="nombreEmpresa" id="nombreEmpresa"
                                            class="form-control" placeholder="nombreEmpresa" autocomplete="off">
                                    </div>
                                    <span id="error-nombreEmpresa" class="text-danger text-xs mb-3"></span>
                                </div>

                                <div class="col-xl-4">
                                    <label>Teléfono: *</label>
                                    <div class="input-group mb-1">
                                        <input type="text" name="telefonoJuridico" id="telefonoJuridico" oninput="validarInput(this)"
                                            class="form-control" placeholder="+(503) 0000-0000" autocomplete="off">
                                    </div>
                                    <span id="error-telefonoJuridico" class="text-danger text-xs mb-3"></span>
                                </div>

                                <div class="col-xl-6">
                                    <label>Dirección: *</label>
                                    <textarea id="direccionJuridico" name="direccionJuridico" class="form-control"
                                        placeholder="Lugar, residencia, colonia, etc." rows="2" cols="30"></textarea>
                                    <span id="error-direccionJuridico" class="text-danger text-xs mb-3"></span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <h5 class="text-dark">Datos Financieros</h5>
                                <hr class="my-3 mt-1">
                                <!-- ventas netas, activo corriente, inventario, costo de ventas -->
                                <div class="col-xl-3">
                                    <label>Ventas netas: *</label>
                                    <div class="input-group mb-1">
                                        <input type="number" name="ventasNetas" id="ventasNetas"
                                            class="form-control" step="0.01" placeholder="$0.00"
                                            autocomplete="off">
                                    </div>
                                    <span id="error-ventasNetas" class="text-danger text-xs mb-3"></span>
                                </div>
                                <div class="col-xl-3">
                                    <label>Activo corriente: *</label>
                                    <div class="input-group mb-1">
                                        <input type="number" name="activoCorriente" id="activoCorriente"
                                            class="form-control" step="0.01" placeholder="$0.00"
                                            autocomplete="off">
                                    </div>
                                    <span id="error-activoCorriente" class="text-danger text-xs mb-3"></span>
                                </div>
                                <div class="col-xl-3">
                                    <label>Inventario: *</label>
                                    <div class="input-group mb-1">
                                        <input type="number" name="inventario" id="inventario" class="form-control"
                                            step="0.01" placeholder="$0.00" autocomplete="off">
                                    </div>
                                    <span id="error-inventario" class="text-danger text-xs mb-3"></span>
                                </div>
                                <div class="col-xl-3">
                                    <label>Costo de ventas: *</label>
                                    <div class="input-group mb-1">
                                        <input type="number" name="costoVentas" id="costoVentas"
                                            class="form-control" step="0.01" placeholder="$0.00"
                                            autocomplete="off">
                                    </div>
                                    <span id="error-costoVentas" class="text-danger text-xs mb-3"></span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <!-- pasivo corriente,cuentas por cobrar, cuentas por pagar -->
                                <div class="col-xl-4">
                                    <label>Pasivo corriente: *</label>
                                    <div class="input-group mb-1">
                                        <input type="number" name="pasivoCorriente" id="pasivoCorriente"
                                            class="form-control" step="0.01" placeholder="$0.00"
                                            autocomplete="off">
                                    </div>
                                    <span id="error-pasivoCorriente" class="text-danger text-xs mb-3"></span>
                                </div>
                                <div class="col-xl-4">
                                    <label>Cuentas por cobrar: *</label>
                                    <div class="input-group mb-1">
                                        <input type="number" name="cuentasCobrar" id="cuentasCobrar"
                                            class="form-control" step="0.01" placeholder="$0.00"
                                            autocomplete="off">
                                    </div>
                                    <span id="error-cuentasCobrar" class="text-danger text-xs mb-3"></span>
                                </div>
                                <div class="col-xl-4">
                                    <label>Cuentas por pagar: *</label>
                                    <div class="input-group mb-1">
                                        <input type="number" name="cuentasPagar" id="cuentasPagar"
                                            class="form-control" step="0.01" placeholder="$0.00"
                                            autocomplete="off">
                                    </div>
                                    <span id="error-cuentasPagar" class="text-danger text-xs mb-3"></span>
                                </div>
                            </div>
                            <div class="row mb-1 text-center ">
                                <!-- Para estado de resultados -->
                                <div class="col-xl-6">
                                    <input type="hidden" id="imagenTempResultados" name="imagenTempResultados">
                                    <label id="image-preview-resultados" class="custum-file-upload mb-1"
                                        data-bs-tt="tooltip" data-bs-original-title="Click para subir documento"
                                        data-bs-placement="bottom" style="margin-top:25px; width: auto; height: 50%">
                                        <div class="icon" id="iconContainerResultados"
                                            style="color:#c4c4c4; font-size: 32px">
                                            <i style="height: 55px; padding: 10px" class="fas fa-file"></i>
                                        </div>
                                        <div id="textImageResultados" class="text">
                                            <span>Subir estado de resultados</span>
                                        </div>
                                        <input type="file" name="estadoResultados" id="estadoResultados"
                                            accept="application/pdf">
                                    </label>
                                    <span id="error-estadoResultados" class="text-danger text-xs mb-3"></span>
                                </div>

                                <!-- Para balance general -->
                                <div class="col-xl-6">
                                    <input type="hidden" id="imagenTemp" name="imagenTemp">
                                    <label id="image-preview-balance" class="custum-file-upload mb-1"
                                        data-bs-tt="tooltip" data-bs-original-title="Click para subir imagen"
                                        data-bs-placement="bottom" style="margin-top:25px; width: auto; height: 50%">
                                        <div class="icon" id="iconContainerBalance"
                                            style="color:#c4c4c4; font-size: 32px">
                                            <i style="height: 55px; padding: 10px" class="fas fa-file"></i>
                                        </div>
                                        <div id="textImageBalance" class="text">
                                            <span>Subir balance general</span>
                                        </div>
                                        <input type="file" name="balanceGeneral" id="balanceGeneral"
                                            accept="application/pdf">
                                    </label>
                                    <span id="error-balanceGeneral" class="text-danger text-xs mb-3"></span>
                                </div>

                            </div>
                            <div class="text-end">
                                <button type="reset" data-bs-dismiss="modal" style="border-color:transparent"
                                    class="btn btn-outline-dark btn-sm mt-4 mb-0">
                                    <i class="fas fa-undo text-xs"></i>&nbsp;&nbsp;Cancelar
                                </button>
                                <button type="submit" class="btn btn-icon bg-gradient-success btn-sm mt-4 mb-0">
                                    <i class="fas fa-check text-xs"></i>&nbsp;&nbsp;Guardar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalConfirm" tabindex="-1" role="dialog" aria-labelledby="modal-form"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-mg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0 ">
                <div class="card card-plain">
                    <div class="card-header pb-0 text-left">
                        <h3 class="text-dark">Confirmar operación</h3>
                        <p id="dialogoC" class="mb-0">Está a punto de deshabilitar el registro. ¿Desea continuar?
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