<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0 ">
                <div class="card card-plain">
                    <div class="card-header pb-0 text-left">
                        <h3 class="text-dark" id="titulo">Nueva Venta</h3>
                        <p class="mb-0" id="subtitulo">Código: VT0001</p>
                        <p class="mb-0 text-xs" id="fecha"></p>
                    </div>
                    <div class="card-body">
                        <form role="form text-left" enctype="multipart/form-data" id="ventaForm">
                            @csrf
                            <input type="hidden" name="_method" id="method" value="POST"> <!-- Para edición -->
                            <div class="row mb-3">
                                <div class="drop-relative col-xl-6">
                                    <label>Cliente: *</label>
                                    <div class="input-group mb-1">
                                        <input type="text" name="cliente" id="cliente" class="form-control"
                                            placeholder="Buscar cliente" autocomplete="off">
                                    </div>
                                    <ul class="dropdown-results px-1" id="dropdown-cliente" data-input="#cliente">
                                        <!-- Los resultados se agregarán aquí -->
                                    </ul>
                                    <span id="error-cliente" class="text-danger text-xs mb-3"></span>
                                    <span id="error-idCliente" class="text-danger text-xs mb-3"></span>
                                </div>
                                <input type="hidden" name="idCliente" id="idCliente">
                                <div class="col-xl-6">
                                    <label>Tipo de Venta: *</label>
                                    <div class="input-group mb-1">
                                        <select name="tipo" id="tipo" class="form-control">
                                            <option selected ="Contado">Contado</option>
                                            <option value="Crédito">Crédito</option>
                                        </select>
                                    </div>
                                    <span id="error-tipo" class="text-danger text-xs mb-3"></span>
                                </div>
                            </div>
                            {{-- <div class="row mb-3 align-items-center detalles-prod">
                                <div class="col-6">
                                    <label>Plazo (meses): *</label>
                                    <div class="input-group mb-1">
                                        <input type="number" name="plazo" id="plazo" class="form-control"
                                            placeholder="0" step="1" min="1" autocomplete="off">
                                    </div>
                                    <span id="error-plazo" class="text-danger text-xs mb-3"></span>
                                </div>

                                <div class="col-6">
                                    <label>Cuota:</label>
                                    <h6 class="text-xl text-dark mb-0 ms-1" id="cuota">$0.00</h6>
                                </div>
                            </div> --}}
                            <div class="row align-items-start mb-3 ">
                                <!-- Elección del Producto -->
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
                                                <div class="d-flex mb-0">
                                                    <label>Precio de Venta:</label>
                                                    <p class="text-sm text-dark mb-0 ms-1" id="precioVenta"></p>
                                                </div>
                                                <div class="d-flex ">
                                                    <label class="mb-0">Unidades Disponibles:</label>
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
                                    &nbsp;<strong>Detalle de venta:</strong>
                                    Aquí se muestran los productos añadidos a la venta con su cantidad y subtotal.
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
                                        <div class="d-flex justify-content-end mb-0">
                                            <label class="text-sm">Total:</label>
                                            <p class="text-sm text-dark mb-1 ms-1" id="total">$0.00</p>
                                        </div>
                                        <div class="d-flex justify-content-end mb-0">
                                            <label class="text-sm">Iva (14%):</label>
                                            <p class="text-sm text-dark mb-1 ms-1" id="iva">$0.00</p>
                                        </div>
                                        <div class="d-flex justify-content-end mb-0">
                                            <label class="text-lg">Total a pagar:</label>
                                            <p class="text-lg text-dark mb-1 ms-1" id="totalVenta">$0.00</p>
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
<!-- Modal para ingresar datos para generar el PDF -->
{{-- <div class="modal fade" id="PDFmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
</div> --}}
