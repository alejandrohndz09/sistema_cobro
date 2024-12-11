<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0 ">
                <div class="card card-plain">
                    <div class="card-header pb-0 text-left">
                        <h3 class="text-dark" id="titulo">Nueva Compra</h3>
                        <p class="mb-0" id="subtitulo">Código: VT0001</p>
                        <p class="mb-0 text-xs" id="fecha"></p>
                    </div>
                    <div class="card-body">
                        <form role="form text-left" enctype="multipart/form-data" id="compraForm">
                            @csrf
                            <input type="hidden" name="_method" id="method" value="POST"> <!-- Para edición -->
                            <div class="row mb-3">
                                <div class="drop-relative col-xl-6">
                                    <label>Producto: *</label>
                                    <div class="input-group mb-1">
                                        <input type="text" name="producto" id="producto" class="form-control"
                                            placeholder="Buscar producto" autocomplete="off">
                                    </div>
                                    <ul class="dropdown-results px-1" id="dropdown-producto" data-input="#producto">
                                        <!-- Los resultados se agregarán aquí -->
                                    </ul>
                                    <span id="error-producto" class="text-danger text-xs mb-3"></span>
                                    <span id="error-idProducto" class="text-danger text-xs mb-3"></span>
                                </div>
                                <input type="hidden" name="idProducto" id="idProducto">
                                <div class="drop-relative col-xl-6">
                                    <label>Proveedor: *</label>
                                    <div class="input-group mb-1">
                                        <input type="text" name="proveedor" id="proveedor" class="form-control"
                                            placeholder="Buscar proveedor" autocomplete="off">
                                    </div>
                                    <ul class="dropdown-results px-1 text-right" id="dropdown-proveedor"
                                        data-input="#proveedor">
                                        <!-- Los resultados se agregarán aquí -->
                                    </ul>
                                    <span id="error-proveedor" class="text-danger text-xs mb-3"></span>
                                    <span id="error-idProveedor" class="text-danger text-xs mb-3"></span>
                                    <input type="hidden" id="idProveedorr" name="idProveedorr" value="">
                                </div>

                            </div>
                            <div class="row align-items-start mb-3 ">

                                <div class="drop-relative col-xl-6">
                                    <label>Precio Unitario: *</label>
                                    <div class="input-group mb-1">
                                        <input type="number" name="precioUnitario" id="precioUnitario"
                                            class="form-control" placeholder="$0" autocomplete="off">
                                    </div>

                                    <span id="error-producto" class="text-danger text-xs mb-3"></span>
                                </div>
                                <!-- Información del Producto -->
                                <div class="col-xl-6">
                                    <div class="row align-items-end">
                                        <!-- Cantidad del Producto -->
                                        <div class="col-4">
                                            <label>Cantidad: *</label>
                                            <div class="input-group mb-1">
                                                <input type="number" placeholder="0" step="1" min="1"
                                                    name="cantiddad" id="cantidad" class="form-control" placeholder="0"
                                                    autocomplete="off">
                                            </div>
                                        </div>
                                        <!-- detalle del Producto -->
                                        <div class="col-6 detalles-prod" style="display: none">
                                            <div class="row mb-0">
                                                <div class="d-flex ">
                                                    <label class="mb-0">Unidades en inventario:</label>
                                                    <p class="text-sm text-dark mb-0 ms-1" id="stockTotal"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Botón de agregar -->
                                        <div class="col-2 px-0">
                                            <button data-bs-tt="tooltip" id="btnAgregarDet"
                                                data-bs-original-title="Añadir producto."
                                                class="btn bg-gradient-dark mb-1">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <span id="error-cantidad" class="text-danger text-xs mb-3"></span>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <p class="mt-1 mb-2 text-xs">
                                    <i class="fas fa-circle-info"></i>
                                    &nbsp;<strong>Detalle de compra:</strong>
                                    Aquí se muestran los productos añadidos a la compra con su cantidad y subtotal.
                                </p>
                            </div>

                            <div class="row">
                                <div id="tableDepartamentos" class="table-responsive ">
                                    <table class="table mb-3 align-items-center">
                                        <thead>
                                            <th class="ps-1 text-dark text-xs font-weight-bolder ">
                                                Producto
                                            </th>
                                            <th class="text-dark text-xs font-weight-bolder ">
                                                Cantidad
                                            </th>
                                            <th class="text-dark text-xs font-weight-bolder ">
                                                Precio Unitario
                                            </th>
                                            <th class="text-dark text-xs font-weight-bolder ">
                                                Subtotal
                                            </th>
                                            <th></th>
                                        </thead>
                                        <tbody id="tableBodyDetalle">

                                        </tbody>
                                    </table>
                                    <span id="error-detalles" class="text-danger text-xs mb-3"></span>
                                    <div class="row">
                                        {{-- <div class="d-flex justify-content-end mb-0">
                                            <label class="text-sm">Total:</label>
                                            <p class="text-sm text-dark mb-1 ms-1" id="total">$0.00</p>
                                        </div> --}}
                                        <div class="d-flex justify-content-end mb-0">
                                            <label class="text-lg">Total a pagar:</label>
                                            <p class="text-lg text-dark mb-1 ms-1" id="totalCompra">$0.00</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end ">
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


<div class="modal fade" id="modalShow" tabindex="-1" role="dialog" aria-labelledby="modalShowLabel"
    aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0 ">
                <div class="card card-plain">

                    <div class="card-body">
                        <form role="form text-left" enctype="multipart/form-data" id="modalShowLabel">
                            @csrf
                            <input type="hidden" name="_method" id="method" value="POST">
                            <!-- Para edición -->

                            <div class="row mb-0">
                                <p class="mt-1 mb-2 text-xs">
                                    <i class="fas fa-circle-info"></i>
                                    &nbsp;<strong>Detalles:</strong>
                                    Productos adquiridos en la compra.
                                </p>
                            </div>

                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table mb-3 align-items-center" id="detalles-compra">
                                        <thead>
                                            <th class="text-dark text-xs font-weight-bolder">
                                                Producto
                                            </th>
                                            <th class="text-dark text-xs font-weight-bolder ">
                                                Precio Unitario
                                            </th>
                                            <th class="text-dark text-xs font-weight-bolder ">
                                               Cantidad
                                            </th>
                                            <th class="text-dark text-xs font-weight-bolder ">
                                                Subtotal
                                            </th>
                                        </thead>
                                        <tbody>
                                            <!-- Las filas se insertarán dinámicamente aquí -->
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row">
                                    <div class="d-flex justify-content-end">
                                        <label class="text-lg">Total:</label>
                                        <p class="text-lg text-dark mb-1 ms-1" id="totalC">$0.00</p>
                                    </div>
                                </div>

                            </div>

                            <div class="text-end ">
                                <button type="reset" data-bs-dismiss="modal" style="border-color:transparent"
                                    class="btn btn-outline-dark btn-sm mt-4 mb-0">
                                    <i class="fas fa-undo text-xs"></i>&nbsp;&nbsp;Cerrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <!-- Modal donde se mostrarán los detalles -->
<div class="modal fade" id="modalShow" tabindex="-1" aria-labelledby="modalShowLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalShowLabel">Detalles de la compra</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table" id="detalles-compra">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Monto</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div> --}}
