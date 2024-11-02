<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0 ">
                <div class="card card-plain">
                    <div class="card-header pb-0 text-left">
                        <h3 class="text-dark" id="titulo"></h3>
                        <p class="mb-0">(*) Campos Obligatorios</p>
                    </div>
                    <div class="card-body">
                        <form role="form text-left" enctype="multipart/form-data" id="bienForm">
                            @csrf
                            <input type="hidden" name="_method" id="method" value="POST"> <!-- Para edición -->
                            <div class="row" id="panelAdquisicion">
                                <div class="col-xl-6">
                                    <label>Precio de Adquisición ($): *</label>
                                    <div class="input-group mb-1">
                                        <input type="number" name="precioAdquisicion" id="precioAdquisicion"
                                            class="form-control" step="0.01" placeholder="0.00" autocomplete="off">
                                    </div>
                                    <span id="error-precioAdquisicion" class="text-danger text-xs mb-3"></span>
                                </div>
                                <div class="col-xl-6">
                                    <label>Fecha de Adquisición: *</label>
                                    <div class="input-group mb-1">
                                        <input type="date" name="fechaAdquisicion" id="fechaAdquisicion"
                                            class="form-control" autocomplete="off">
                                    </div>
                                    <span id="error-fechaAdquisicion" class="text-danger text-xs mb-3"></span>
                                </div>
                                <div class="col-xl-6">
                                    <label>Sucursal: *</label>
                                    <div class="input-group mb-1">
                                        <select id="sucursal" name="sucursal"
                                            class="form-control selectSucursal"></select>
                                    </div>
                                    <span id="error-sucursal" class="text-danger text-xs mb-3"></span>
                                </div>
                                <div class="col-xl-6">
                                    <label>Departamento: *</label>

                                    <select id="departamento" name="departamento"
                                        class="form-control selectDepartamento">
                                        <option value="">Seleccione</option>
                                    </select>
                                </div>
                                <span id="error-departamento" class="text-danger text-xs mb-3"></span>
                            </div>
                            <div class="col-xl-12">

                                <p class="mt-1 mb-2 text-xs">
                                    <i class="fas fa-circle-info"></i>
                                    &nbsp;<strong>Detalle de Adquisición:</strong>
                                    Defina cuantas unidades de este activo se adquirirán y en
                                    donde serán distribuidas.
                                </p>
                            </div>

                            <div id="tableDepartamentos" class="table-responsive ">
                                <table class="table mb-0 align-items-center">
                                    <thead>
                                        <th class="text-dark text-xs font-weight-bolder ">
                                            Cantidad: *
                                        </th>
                                        <th class="text-dark text-xs font-weight-bolder ">
                                            Sucursal: *
                                        </th>
                                        <th class="text-dark text-xs font-weight-bolder ">
                                            Departamento: *
                                        </th>
                                        <th></th>
                                    </thead>
                                    <tbody id="tableBodyDepartamentos">
                                        <tr>
                                            <td class="ps-4 col-4">

                                                <input type="number" name="cantidad[]" style="max-width: 150px"
                                                    placeholder="0" step="1" class="form-control inputCantidad">
                                                <span id="error-cantidad.1"
                                                    class="sp-cantidad text-danger text-xs mb-3"></span>

                                            </td>
                                            <td class="ps-4 col-4">
                                                <select id="sucursal" name="sucursal[]"
                                                    class="form-control selectSucursal"
                                                    style="max-width: 150px"></select>
                                                <span id="error-sucursal.1"
                                                    class="sp-sucursal text-danger text-xs mb-3"></span>
                                            </td>

                                            <td class="ps-4 col-4">
                                                <select id="departamento" name="departamento[]"
                                                    class="form-control selectDepartamento" style="max-width: 150px">
                                                    <option value="">Seleccione</option>
                                                </select>
                                                <span id="error-departamento.1"
                                                    class="sp-departamento text-danger text-xs mb-3"></span>
                                            </td>

                                            <td class="ps-0 col-4">
                                                <a role="button" data-bs-tt="tooltip" data-bs-original-title="Añadir"
                                                    class="btnAgregarAd me-2">
                                                    <i class="fas fa-plus text-secondary"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div id="pagination" class="d-flex justify-content-center mt-2"></div>
                            </div>
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

<div class="modal fade" id="modalConfirm" tabindex="-1" role="dialog" aria-labelledby="modal-form"
    aria-hidden="true">
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
