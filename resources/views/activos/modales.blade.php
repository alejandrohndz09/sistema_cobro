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
                        <form role="form text-left" enctype="multipart/form-data" id="activoForm">
                            @csrf
                            <input type="hidden" name="_method" id="method" value="POST"> <!-- Para edición -->
                            <div class="row mb-3">
                                <div class="col-xl-4">
                                    <input type="hidden" id="imagenTemp" name="imagenTemp">
                                    <label id="image-preview" class="custum-file-upload mb-1" data-bs-tt="tooltip"
                                        data-bs-original-title="Click para subir imagen" data-bs-placement="bottom"
                                        style="margin-top:25px; width: auto; height: 75%;">
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
                                <div class="col-xl-4">
                                    <label>Nombre: *</label>
                                    <div class="input-group mb-1">
                                        <input type="text" name="nombre" id="nombre" class="form-control"
                                            placeholder="Nombre" autocomplete="off">
                                    </div>
                                    <span id="error-nombre" class="text-danger text-xs mb-3"></span>

                                    <label>Categoría: *</label>
                                    <select id="categoria" name="categoria" class="form-control"></select>
                                    <span id="error-categoria" class="text-danger text-xs mb-3"></span>
                                </div>
                                <div class="col-xl-4">
                                    <label>Descripción:</label>
                                    <textarea id="descripcion" name="descripcion" class="form-control" placeholder="Ej. Marca, modelo, capacidades, etc."
                                        rows="5" cols="50"></textarea>
                                    <span id="error-descripcion" class="text-danger text-xs mb-3"></span>
                                </div>
                            </div>
                            {{-- a partir de acá estarán los datos para adquisición --}}

                            <div class="row" id="panelAdquisicion">
                                <hr class="my-3">
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
                                            <th class="px-0">
                                                <button data-bs-tt="tooltip" id="btnAgregarAd"
                                                    data-bs-original-title="Añadir lote de adquisición."
                                                    class="btn btn-sm bg-gradient-dark mb-0">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </th>
                                        </thead>
                                        <tbody id="tableBodyDepartamentos">
                                            <tr>
                                                <td class="ps-4 col-4">

                                                    <input type="number" name="cantidad[]" style="max-width: 150px"
                                                        placeholder="0" step="1"
                                                        class="form-control inputCantidad">
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
                                                        class="form-control selectDepartamento"
                                                        style="max-width: 150px">
                                                        <option value="">Seleccione</option>
                                                    </select>
                                                    <span id="error-departamento.1"
                                                        class="sp-departamento text-danger text-xs mb-3"></span>
                                                </td>
                                                <td class="ps-0 col-4"></td>
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
